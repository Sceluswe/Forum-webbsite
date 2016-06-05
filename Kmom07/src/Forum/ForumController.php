<?php
namespace Anax\Forum;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class ForumController implements \Anax\DI\IInjectionAware
{
	use \Anax\DI\TInjectable,
		\Anax\MVC\TRedirectHelpers;
		
		
	/**
	* Initialize the controller.
	*
	* @return void
	*/
	public function initialize()
	{
		$obj = new \Anax\Forum\Question();
		$this->questions = $obj;
		$this->questions->setDI($this->di);
		$this->questions->setSource('question');
		
		$obj = new \Anax\Forum\Answer();
		$this->answers = $obj;
		$this->answers->setDI($this->di);
		$this->answers->setSource('answer');
		
		$obj = new \Anax\Forum\Comment();
		$this->comments = $obj;
		$this->comments->setDI($this->di);
		$this->comments->setSource('comment');
		
		$this->users = new \Anax\Users\User();
		$this->users->setDI($this->di);
		$this->di->session();
	}
	
	/**
	* Return redirects.
	* 
	* @return array containing redirects.
	*/
	public function redirects()
	{
		return $values = [
		'Forum/removeAll', 
		'Forum/setup', 
		'Forum/update/', 
		'Forum/delete/',
		'Forum/add',
		'user' => 'Users/id/',
		'question' => 'Forum/id/'
		];
	}
	
	/**
	* Function that displays all questions on the database.
	*
	*/
	public function menuAction()
	{
		// Get all questions.
		$result = $this->questions->findAll();
		
		foreach($result as $item)
		{
			// Format timestamp
			$item->timestamp = $this->time_elapsed($item->timestamp);
		}
		
		$userlink = "You are currently not logged in. <a href='" . $this->url->create("Users/Login") . "'>Login</a>";
		if($this->users->isUserLoggedIn())
		{
			$user = $this->users->findByColumn('acronym', $this->users->currentUser());
			$userlink = "You are currently logged in as: <a href='" 
				. $this->url->create("Users/id/{$user[0]->id}") 
				. "'>" . ucfirst($user[0]->acronym) . "</a>";
		}
		
		$this->theme->setTitle("All Questions");
		$this->views->add('forum/forum-menu', [
			'questions' => $result,
			'title' 	=> "All questions",
			'redirect' 	=> $this->redirects(),
			'user'		=> $userlink,
		]);
	}
	
	/**
	* Function that displays all questions posted by the user with the parameterized id.
	*
	* @param The database id of the user.
	*
	*/
	public function userQuestionsAction($id)
	{
		$result = $this->questions->findByColumn('userid', $id);
		
		if(!empty($result))
		{
			foreach($result as $item)
			{
				$item->timestamp = $this->time_elapsed($item->timestamp);
			}
			
			$this->theme->setTitle("All Questions");
			$this->views->add('forum/forum-menu', [
				'questions' => $result,
				'title' => "Questions asked by this user",
				'redirect' => $this->redirects()
			]);
		}
	}
	
	public function idAction($id)
	{
		// Get question.
		$question = $this->questions->find($id);
		// Check if the question did indeed exist.
		if(!empty($question))
		{	
			$question = $this->formatTimestamp($question);
			// If question is not empty, get the comments to that question.
			$questionComments = $this->comments->findQuestioncomments($id);

			// Check if comments do exist otherwise set it to an empty array.
			$questionComments = !empty($questionComments)
				? $this->formatTimestamp($questionComments) : array();
			
			// If question is not empty, get the answers to that question.
			$answers = $this->answers->findByColumn('questionid', $id);

			$answers = !empty($answers)
				? $this->formatTimestamp($answers) : array();
			
			// Initialize answerComments array with an empty array, in case there are no comments.
			$answerComments = array();
			
			// Make sure answer does indeed exist.
			if(!empty($answers))
			{	// For each answer, find the corresponding comments.
				foreach($answers as $item)
				{	// Get the comments to the current answer.
					$answerComments[$item->id] = $this->comments->findAnswerComments($item->id);
				}
				// Format timestamp of answerComments.
				foreach($answerComments as $comments)
				{
					$comments = $this->formatTimestamp($comments);
				}
			}
			
			// Set the title of the browser tab.
			$this->theme->setTitle($question->title);
			$this->views->add('forum/forum-question', [
				'redirect' => $this->redirects(),
				'question'  		=> $question,
				'questionComments'  => $questionComments,
				'answers'		 	=> $answers,
				'answerComments' 	=> $answerComments
			]);
		}
	}	
	
	/*
	* Function to format the timestamp of question, answer and comments.
	*
	*/
	public function formatTimestamp($arr)
	{
		if(is_array($arr))
		{
			foreach($arr as $item)
			{
				$item->timestamp = $this->time_elapsed($item->timestamp);
			}
		}
		else
		{
			$arr->timestamp = $this->time_elapsed($arr->timestamp);
		}
		
		return $arr;
	}
	
	/**
	* Format a unix timestamp to display its age (5 days ago, 1 day ago, just now etc.).
	*
	* @param   int     $timestamp,  unix timestamp
	* @return  string
	*/
	public function time_elapsed($secs)
	{
		$elapsedtime;
		if($secs == 0)
		{
			$elapsedtime = "Just now.";
		}
		else
		{
			$secs = time() - $secs;
			$bit = array(
				'y' => $secs / 31556926 % 12,
				'd' => $secs / 86400 % 7,
				'h' => $secs / 3600 % 24,
				'm' => $secs / 60 % 60,
				's' => $secs % 60
				);
				
			foreach($bit as $k => $v)
				if($v > 0)$ret[] = $v . $k;
			
			$elapsedtime = join(' ', $ret);
		}
			
		return $elapsedtime;
    }
}