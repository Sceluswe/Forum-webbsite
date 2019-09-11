<?php

namespace Anax\Users;

/**
* A controller for users and admin related events.
*
*/
class UsersController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable,
		\Anax\MVC\TRedirectHelpers;



    // All redirect links used.
    private $redirect = [
        "default"       => "Users/",
        "login"         => "Users/login",
        "logout"        => "Users/logout",
        "profile"       => "Users/profile/",
        "update"        => "Users/update/",
        "delete"        => "Users/delete/",
        "softDelete"    => "Users/softDelete/",
        "restore"       => "Users/restore/",
        "list-all"      => "Users/List-all"
    ];

    // All templates links used.
    private $template = [
        "list-all"      => "Users/List-all",
        "menu"          => "Users/menu",
        "none"          => "Users/none",
        "view"          => "Users/view"
    ];



	/**
	* Initialize the controller.
	*
	* @return void
	*/
	public function initialize()
	{
		$this->users = new \Anax\Users\User();
		$this->users->setDI($this->di);
		$this->di->session();

		$this->questions = new \Anax\Forum\Question();
		$this->questions->setDI($this->di);
		//$this->questions->setSource('question');
	}



    /**
    * Displays the currently logged in user and links to its profile.
    *
    * @return void.
    */
    public function statusAction()
    {
        $userlink = "<p>You are currently not logged in: <a href=\"" . $this->url->create($this->redirect["login"]) . "\">Login</a></p>";

        if($this->users->isUserLoggedIn())
        {
            $user = $this->users->findByAcronym($this->users->currentUser());

            // Create a link to the currently logged in user.
            $userlink = "<p>You are currently logged in as: <a href=\""
                . $this->url->create("{$this->redirect["profile"]}{$user->id}") . "\">"
                . ucfirst($user->acronym) . "</a></p>";
        }

        $this->utility->renderDefaultPage("", $userlink);
    }



    /**
    * Create an options menun view.
    *
    * @return void
    */
    public function menuAction()
    {
        $this->views->add($this->template["menu"], [
            'values' => ['Add', 'List-all', 'List-active', 'List-trash'],
            'url'	 => $this->redirect["default"]
        ]);
    }



    /**
    * List user with id.
    *
    * @param int $id of user to display
    *
    * @return void
    */
    public function profileAction($id = null)
    {
        $this->initialize();

        $this->menuAction();

        $user = $this->users->find($id);

        if(!empty($user))
        {
            $this->theme->setTitle("View user with id");

            $this->views->add($this->template["view"], [
                'admin'	=> $this->users->isUserAdmin($this->users->currentUser(), ['admin', $user->acronym]),
                'superadmin' => $this->users->isUserAdmin($this->users->currentUser(), ['admin']),
                'user' => $user,
                'title' => "View user: " . $user->name,
                'redirect' => $this->redirect
            ]);

            $this->dispatcher->forward([
                'controller'=> 'Forum',
                'action'	=> 'score',
                'params'	=> ['id' => $id]
            ]);

            $this->dispatcher->forward([
                'controller' => 'Forum',
                'action'	 => 'userQuestions',
                'params' 	 => ['id' => $id]
            ]);
        }
        else
        {
            $this->views->add($this->template["none"], []);
        }
    }



    /**
    * Cheat function to add a new user in a simplified way.
    *
    * @return void
    */
    public function addAction()
    {
        $this->initialize();

        $this->menuAction();

        $this->users->created = gmdate('Y-m-d H:i:s');

        $this->utility->renderDefaultPage("Create User", $this->getUserForm());
    }



    /**
    * List all users.
    *
    * @return void
    */
    public function listAction()
    {
        $this->initialize();

        $this->menuAction();

        $this->theme->setTitle("List all users");
        $this->views->add($this->template["list-all"], [
            'admin'	=> $this->users->isUserAdmin($this->users->currentUser(), ['admin']),
            'users' => $this->users->findAll(),
            'title' => "View all users",
            'redirect' => $this->redirect
        ]);
    }



    /**
	* List all soft deleted users.
	*
	* @return void
	*/
	public function deletedAction()
	{
		$this->initialize();

		$this->menuAction();

		$this->theme->setTitle("Users that are deleted");
		$this->views->add($this->redirect["list-all"], [
			'admin'	=> $this->users->isUserAdmin($this->users->currentUser(), ['admin']),
			'users' => $this->users->findSoftDeleted(),
			'title' => "View all users",
			'redirect' => $this->redirect
		]);
	}



	/**
	* List all active users (not soft deleted).
	*
	* $return void
	*/
	public function activeAction()
	{
		$this->menuAction();

		$this->theme->setTitle("Users that are active");
		$this->views->add($this->redirect["list-all"], [
			'admin'	=> $this->users->isUserAdmin($this->users->currentUser(), ['admin']),
			'users' => $this->users->findActive(),
			'title' => "View all users",
			'redirect' => $this->redirect
		]);
	}



    /**
    * Create a database and initialize two users.
    *
    * @return void
    */
    public function setupAction()
    {
        if($this->users->isUserAdmin($this->users->currentUser(), ['admin']))
        {
            if($this->users->initializeTable('user'))
            {
                $this->menuAction();

                $this->theme->setTitle("Create Table");
                $this->views->add($this->template["list-all"], [
                    'admin'	=> $this->users->isUserAdmin($this->users->currentUser(), ['admin']),
                    'users' => $this->users->findAll(),
                    'title' => "Table Successfully created!",
                    'redirect' => $this->redirect
                ]);
            }
        }
        else
        {
            $this->listAction();
        }
    }



    /**
    * Update the information of a user.
    *
    * @id int, id of the user to update.
    *
    * @return void
    */
	public function updateAction($id = null)
	{
		if(!isset($id))
			die("Missing id.");

		$this->menuAction();

		$user = $this->users->find($id);
		$this->users->updated = gmdate('Y-m-d H:i:s');

        $this->utility->renderDefaultPage("Update User", $this->getUserForm([
			'acronym' 	=> $user->acronym,
			'email'		=> $user->email,
			'name' 		=> $user->name,
			'password'	=> $user->password
		]));
	}



    /**
    * Restores a soft-deleted user to active.
    *
    * @id int, id of the user to restore.
    *
    * @return void
    */
	public function restoreAction($id = null)
	{
		if(!isset($id))
			die("Missing id.");

		$user = $this->users->find($id);
		$user->deleted = null;
		$user->active = gmdate("Y-m-d H:i:s");
		$user->save();

		//Create a url and redirect to the updated object.
        $this->utility->createRedirect($this->redirect["profile"] . $id);
	}



	/**
	* Delete user.
	*
	* $param int $id of the user to delete.
	*
	* @return void.
	*/
	public function deleteAction($id = null)
	{
		if(!isset($id))
			die("Missing id.");

		$this->users->delete($id);

        $this->utility->createRedirect($this->redirect["list-all"]);
	}



	/**
	* Soft delete User.
	*
	* @param integer, id of the user to soft-delete.
	*
	* @return void.
	*/
	public function softDeleteAction($id = null)
	{
		if(!isset($id))
			die("Missing id.");

		$user = $this->users->find($id);
		$user->deleted = gmdate("Y-m-d H:i:s");
		$user->save();

		//Create a url and redirect to the updated object.
        $this->utility->createRedirect($this->redirect["profile"] . $id);
	}



    /**
    * Callback for login-button success.
    *
    * @return boolean.
    */
	public function loginSubmit($form)
    {
		$success = false;

        // Get acronym from form and escape it and hash the password.
		$acronym = strtolower($this->escaper->escapeHTML($form->Value('acronym')));
		$password = md5($form->Value('password'));

		// Ask the module if user is valid.
		if($this->users->validateUser($acronym, $password))
		{
            // Fetch the user into model and update active time.
            $this->users->findByAcronym($form->Value('acronym'));

			if($this->users->update(['active' => gmdate('Y-m-d H:i:s')]))
			{	// Save user in session.
				$this->users->loginUser($acronym);
				$success = true;
			}

            $this->utility->createRedirect($this->redirect["profile"] . $this->users->id);
		}

        return $success;
    }



	/**
    * Callback for submit-button.
    *
    * @param CForm object, the form used.
    *
    * @return boolean.
    */
    public function loginSuccess($form)
    {
        $form->AddOutput("<p><i>You've been logged in.</i></p>");
        return false;
    }



    /**
    * Callback for submit-button.
    *
    * @param CForm object, the form used.
    *
    * @return boolean.
    */
    public function loginFail($form)
    {
        $form->AddOutput("<p><i>Invalid login information.</i></p>");
        return false;
    }



    /**
    * Get a form for logging in a user.
    *
    * @return the HTML code of the form.
    */
    private function getLoginForm()
    {
        $form = new \Mos\HTMLForm\CForm();

        $form = $form->create([], [
            'acronym' => [
                'type' 		 => 'text',
                'required' 	 => true,
                'class' 	 => 'cform-textbox',
                'validation' => ['not_empty']
            ],
            'password' => [
                'type' 		 => 'password',
                'required' 	 => true,
                'class' 	 => 'cform-textbox',
                'validation' => ['not_empty']
            ],
            'submit' => [
                'type' 		=> 'submit',
                'class' 	=> 'cform-submit',
                'callback'  => [$this, 'loginSubmit'],
                'value'		=> 'Login'
            ],
        ]);

        // Check the status of the form
        $form->check([$this, 'loginSuccess'], [$this, 'loginFail']);

        return $form->getHTML();
    }



	/**
	* Function that logs in a user and stores the currently logged in user in session.
    *
	* @return void
	*/
	public function loginAction()
	{
        $this->dispatcher->forwardTo("Users", "status");

        (!$this->users->isUserLoggedIn())
            ? $this->utility->renderDefaultPage($this->redirect["login"], $this->getLoginForm())
            : $this->utility->createRedirect($this->redirect["logout"]);
	}



    /**
    * Callback for login-button success.
    *
    * @param CForm object, the form used.
    *
    * @return boolean.
    */
	public function logoutSubmit($form)
    {
		$success = false;

		// Check if user is logged in.
		if($this->users->isUserLoggedIn())
		{
			// Log the user out.
			$this->users->logoutUser();
			$success = true;

            $this->utility->createRedirect($this->redirect["login"]);
		}

        return $success;
    }



    /**
    * Callback for submit-button.
    *
    * @param CForm object, the form used.
    *
    * @return boolean.
    */
    public function logoutSuccess($form)
    {
        $form->AddOutput("<p><i>You've been logged out.</i></p>");
        return false;
    }



    /**
    * Callback for submit-button.
    *
    * @param CForm object, the form used.
    *
    * @return boolean.
    */
    public function logoutFail($form)
    {
        $form->AddOutput("<p><i>You're not logged in.</i></p>");
        return false;
    }



	/**
	* Get a form for logging out a user.
	*
	* @return the HTML code of the form.
	*/
	private function getLogoutForm()
	{
		$form = new \Mos\HTMLForm\CForm();

		$form = $form->create([], [
			'logout' => [
				'type' 		 => 'submit',
				'class' 	 => 'cform-submit',
				'callback'  => [$this, 'logoutSubmit'],
				'value'		=> 'Logout'
			]
		]);

		// Check the status of the form.
		$form->check([$this, 'logoutSuccess'], [$this, 'logoutFail']);

		return $form->getHTML();
	}



	/**
	* Function presents a logout form to the user.
    *
	* @return void
	*/
	public function logoutAction()
	{
        $this->dispatcher->forwardTo("Users", "status");

        $this->utility->renderDefaultPage("Logout", $this->getLogoutForm());
	}



    /**
    * Callback for submit-button success.
    *
    * @param CForm object, the form used.
    *
    * @return boolean.
    */
	public function callbackSubmit($form)
    {
		// Save form.
		$form->saveInSession = true;

		$this->users->save([
			'acronym' 	=> strtolower($form->Value('acronym')),
			'email' 	=> $form->Value('email'),
			'name' 		=> $form->Value('name'),
			'password' 	=> md5($form->Value('password')),
			'created' 	=> $this->users->created,
			'updated'	=> isset($this->users->updated) ? $this->users->updated : null,
			'active' 	=> gmdate('Y-m-d H:i:s')
		]);

        $this->utility->createRedirect($this->redirect["profile"] . $this->users->id);

        return true;
    }



    /**
    * Callback for submit-button.
    *
    * @param CForm object, the form used.
    *
    * @return boolean.
    */
    public function callbackSuccess($form)
    {
        $form->AddOutput("<p><i>User Created.</i></p>");
        return false;
    }



    /**
    * Callback for submit-button.
    *
    * @param CForm object, the form used.
    *
    * @return boolean.
    */
    public function callbackFail($form)
    {
        $form->AddOutput("<p><i>DoSubmitFail(): Form was submitted but I failed to process/save/validate it</i></p>");
        return false;
    }



	/**
	* Get a form for creating and updating a user.
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
			'acronym' => [
				'type' 		=> 'text',
				'required' 	=> true,
				'class' 	=> 'cform-textbox',
				'validation'=> ['not_empty'],
				'value' 	=> !empty($values['acronym']) ? $values['acronym'] : ''
			],
			'email' => [
				'type'       => 'text',
				'required'   => true,
				'class' 	 => 'cform-textbox',
				'validation' => ['not_empty', 'email_adress'],
				'value' 	 => !empty($values['email']) ? $values['email'] : ''
			],
			'name' => [
				'type' 		 => 'text',
				'label' 	 => 'Your name:',
				'required' 	 => true,
				'class' 	 => 'cform-textbox',
				'validation' => ['not_empty'],
				'value' 	 => !empty($values['name']) ? $values['name'] : ''
			],
			'password' => [
				'type' 		 => !empty($values['password']) ? 'hidden' : 'password',
				'required' 	 => true,
				'class' 	 => 'cform-textbox',
				'value' 	 => !empty($values['password']) ? $values['password'] : ''
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
}
