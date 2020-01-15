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



    // All redirect links used.
    private $redirect = [
        "setup"         => "Comment/setup",
        "create"        => "Comment/create",
        "add"           => "Comment/add/",
        "delete"        => "Comment/delete/",
        "deleteAll"     => "Comment/deleteAll",
        "list-all"      => "Comment/list-all"
    ];

    // All templates links used.
    private $template = [
        "list-all"  => "list-all"
    ];



    /**
    * Initialize the controller.
    *
    * @return void.
    */
    public function initialize()
    {
        $this->comments = new \Anax\Comments\Comment();
        $this->comments->setDI($this->di);
    }



    /**
    * Reset a comment section.
    *
    * @return void.
    */
    public function setupAction()
    {
        if ($this->comments->initializeTable()) {
            $this->theme->setTitle("All comments");
            $this->views->add($this->template["list-all"], [
                'comments' => $this->comments->findAll(),
                'title' => "Comment section reset!",
                'redirect' => $this->redirect
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
        $this->comments->setRedirect($this->request->getRoute());

        $this->views->add($this->template["list-all"], [
            'comments'  => $this->comments->findAll(),
            'title'     => "All Comments",
            'redirect'	=> $this->redirect
        ]);
    }



    /**
    * Add a comment.
    *
    * @return void.
    */
    public function addAction()
    {
        $this->utility->renderDefaultPage("Create a Comment", $this->getCommentForm());
    }



    /**
    * Update a comment.
    *
    * @param int, the database id of the comment to update.
    *
    * @return void.
    */
    public function updateAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id.");
        }

        $comment = $this->comments->find($id);

        $this->utility->renderDefaultPage("Edit Comment", $this->getCommentForm([
            'name' 		=> $comment->name,
            'email'		=> $comment->email,
            'web' 		=> $comment->web,
            'content' 	=> $comment->content,
            'timestamp' => $comment->timestamp
        ]));
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
        if (!isset($id)) {
            die("Missing id.");
        }

        $this->comments->delete($id);

        $this->utility->createRedirect($this->comments->getRedirect());
    }



    /**
    * Remove all comments.
    *
    * @return void.
    */
    /*public function deleteAllAction()
    {
        $this->comments->createCommentTable();

        $this->utility->createRedirect($this->comments->getRedirect());
    }*/



    /**
    * Reset a comment section.
    *
    * @return void.
    */
    /*public function setupAction()
    {
        if($this->comments->initializeTable())
        {
            $this->theme->setTitle("All comments");
            $this->views->add($this->template["list-all"], [
                'comments' => $this->comments->findAll(),
                'title' => "Comment section reset!",
                'redirect' => $this->redirect
            ]);
        }
    }*/



    /**
    * Get a form for creating and updating a comment.
    *
    * @param optional, values to be put into the textfields.
    *
    * @return the HTML code of the form.
    */
    private function getCommentForm($values = null)
    {
        $form = new \Mos\HTMLForm\CForm();

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

        // Check the status of the form.
        $form->check([$this, 'callbackSuccess'], [$this, 'callbackFail']);

        return $form->getHTML();
    }



    /**
    * Callback for submit-button success.
    *
    * @param CForm object, the form to be submitted.
    *
    * @return boolean true.
    */
    public function callbackSubmit($form)
    {
        $form->saveInSession = true;

        $this->comments->save([
            'name' 		=> $form->Value('name'),
            'email' 	=> $form->Value('email'),
            'web' 		=> $form->Value('web'),
            'content'	=> $form->Value('content'),
            'timestamp' => gmdate('Y-m-d H:i:s'),
            'ip'		=> $this->request->getServer('REMOTE_ADDR')
        ]);

        $this->utility->createRedirect($this->comments->getRedirect());

        return true;
    }
}
