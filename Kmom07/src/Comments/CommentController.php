<?php
namespace Anax\Comments;

/**
* To attach comments-flow to a page or some content.
*
*/
class CommentController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable,
		\Anax\MVC\TRedirectHelpers;
	
	/**
	* Initialize the controller.
	*
	* @return void
	*/
	public function initialize($table = null)
	{
		$obj = new \Anax\Comments\Comment();
		$this->comments = $obj;
		$this->comments->setDI($this->di);
	}
    
	/**
	* Create redirects and return them.
	* 
	* @return array containing redirects.
	*/
	public function redirects()
	{
		return $values = [
            'Comment/removeAll', 
            'Comment/setup', 
            'Comment/update/', 
            'Comment/delete/',
            'Comment/add'
		];
	}
    
    /**
	* Reset a comment section.
	* 
	* @return void.
	*/
	public function setupAction()
	{			
		if($this->comments->initializeTable())
		{
			$all = $this->comments->findAll();
			
			$this->theme->setTitle("All comments");
			$this->views->add('comment/comments-list-all', [
				'comments' => $all,
				'title' => "Comment section reset!",
				'redirect' => $this->redirects()
			]);
		}
	}
	
    /**
	* View all comments.
	*
	* @return void.
	*/
    public function viewAction()
    {
		$this->initialize();
		$this->comments->setSource($this->request->getRoute());

		// Get all comments.
		$all = $this->comments->findAll();
		
		$this->comments->setRedirect($this->request->getRoute());
		
        $this->views->add('comment/comments-list-all', [
            'comments' 	=> $all,
			'title' => "All Comments",
			'redirect'	=> $this->redirects(),
        ]);
    }
	
	/**
    * Add a comment.
    *
    * @return void.
    */
    public function addAction()
    {	
		// Render form.
        $this->utility->renderDefaultPage("Create a Comment", $this->getUserForm());
    }
    
	/**
	* Update a comment.
	* 
	* @return void.
	*/
	public function updateAction($id = null)
	{
		if(!isset($id))
		{
			die("Missing id.");
		}
		
		$comment = $this->comments->find($id);
		
		// Add values for a comment
		$values = [
			'name' 		=> $comment->name,
			'email'		=> $comment->email,
			'web' 		=> $comment->web,
			'content' 	=> $comment->content,
			'timestamp' => $comment->timestamp
		];
		
		// Render form.
        $this->utility->renderDefaultPage("Edit Comment", $this->getUserForm($values));
	}
	
	/**
    * Remove a comment.
    *
    * @param int, id of the comment to remove.
    *
    * @return void.
    */
    public function deleteAction($id = null)
    {
		if(!isset($id))
		{
			die("Missing id.");
		}
		
		$res = $this->comments->delete($id);
		
        $this->utility->createRedirect($this->comments->getRedirect());
    }
    
	/**
    * Remove all comments.
    *
    * @return void.
    */
    public function removeAllAction()
    {
        $this->comments->createCommentTable();
		
        $this->utility->createRedirect($this->comments->getRedirect());
    }
    
	/**
	* Get a form for creating and updating a comment.
	*
	* @param optional, values to be put into the textfields.
	*
	* @return the HTML code of the form.
	*/
	private function getUserForm($values = null)
	{
		// Initiate object instance.
		$form = new \Mos\HTMLForm\CForm();
		
		// Create form.
		$form = $form->create([], [
			'name' => [
				'type' 		 => 'text',
				'label' 	 => 'Your name:',
				'required' 	 => true,
				'class' 	 => 'cform-textbox',
				'validation' => ['not_empty'],
				'value' 	 => !empty($values['name']) ? $values['name'] : ''
			],
			'email' => [
				'type'       => 'text',
				'required'   => true,
				'class' 	 => 'cform-textbox',
				'validation' => ['not_empty', 'email_adress'],
				'value' 	 => !empty($values['email']) ? $values['email'] : ''
			],
			'web' => [
				'type'       => 'text',
				'required'   => true,
				'class' 	 => 'cform-textbox',
				'value' 	 => !empty($values['web']) ? $values['web'] : ''
			],
			'content' => [
				'type'       => 'textarea',
				'required'   => true,
				'class' 	 => 'cform-textbox cform-textarea',
				'value' 	 => !empty($values['content']) ? $values['content'] : ''
			],
			'submit' => [
			'type' 		=> 'submit',
			'class' 	=> 'cform-submit',
			'callback'  => [$this, 'callbackSubmit'],
			'value'		=> 'Submit user'
			]
		]);
		
		// Check the status of the form
		$form->check([$this, 'callbackSuccess'], [$this, 'callbackFail']);
				
		return $form->getHTML();
	}
	
	/**
    * Callback for submit-button success.
    *
    * @param CForm object, the form to be submitted.
    *
    * @return boolean.
    */
	public function callbackSubmit($form)
    {			
		// Save form.
		$form->saveInSession = true;
		$now = gmdate('Y-m-d H:i:s');
			$this->comments->save([
				'name' 		=> $form->Value('name'),
				'email' 	=> $form->Value('email'),
				'web' 		=> $form->Value('web'),
				'content'	=> $form->Value('content'),
				'timestamp' => $now,
				'ip'		=> $this->request->getServer('REMOTE_ADDR')
			]);

        $this->utility->createRedirect($this->comments->getRedirect());
		
        return true;
    }
    
    /**
    * Format a unix timestamp to display its age (5 days ago, 1 day ago, just now etc.).
    *
    * @param   int     $timestamp,  unix timestamp
    * @return  string
    */
	function elapsedTime($timestamp) 
	{
		$elapsedTime = ""; // returnvalue
		
		$time = time() - $timestamp;
		$years = ($time / 31556926) >= 1 ? floor($time / 31556926) : 0;
		if($years > 1)
		{
			$time = $time - $years * 31556926;
			$elapsedTime .= "{$years} years ";
		}
		else if($years == 1)
		{
			$time = $time - 31556926;
			$elapsedTime .= "{$years} year ";
		}
		
		$months = ($time / 2629743) >= 1 ? floor($time / 2629743) : 0;
		if($months > 1)
		{
			$time = $time - $months * 2629743;
			$elapsedTime .= "{$months} months";
		}
		else if($months == 1)
		{
			$time = $time - 2629743;
			$elapsedTime .= "{$months} month";
		}
		
		$days =	($time / 86400) >= 1 ? floor($time / 86400) : 0;
		if($days > 1)
		{
			$time = $time - $days * 86400;
			$elapsedTime .= "{$days} days ";
		}
		else if($days == 1)
		{
			$time = $time - 86400;
			$elapsedTime .= "{$days} day ";
		}
		
		$hours = floor($time / 3600) >= 1 ? floor($time / 3600) : 0;
		if($hours > 1)
		{
			$time = $time - $hours * 3600;
			$elapsedTime .= "{$hours} hours ";
		}
		else if($hours == 1)
		{
			$time = $time - 3600;
			$elapsedTime .= "{$hours} hour ";
		}
		
		$minutes = ($time / 60) >= 1 ? floor($time / 60) : 0;
		if($minutes > 1)
		{
			$elapsedTime .= "{$minutes} minutes ";
		}
		else if($minutes == 1)
		{
			$time = $time - 60;
			$elapsedTime .= "{$minutes} minute ";
		}
		
		if($years == 0 && $months == 0 && $days == 0 && $hours == 0 && $minutes == 0)
		{
			$elapsedTime = "Just now.";
		}
		else
		{
			$elapsedTime .= "ago.";
		}
		return $elapsedTime;
	}
}
