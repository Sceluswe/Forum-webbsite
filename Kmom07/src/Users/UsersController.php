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
		
		$obj = new \Anax\Forum\Question();
		$this->questions = $obj;
		$this->questions->setDI($this->di);
		//$this->questions->setSource('question');
	}
	
	/**
	* Function that logs in a user and stores the currently logged in user in session.
	*
	* @param id of the user to be logged in. 
	*
	*/
	public function loginAction()
	{	
		if(!$this->users->isUserLoggedIn())
		{
			// Render form.
			$this->theme->setTitle("Login");
			$this->views->add('default/page', [
				'title' => "Login",
				'content' => $this->getLoginForm(),
			]);
		}
		else
		{
			$url = $this->url->create('Users/Logout');
			$this->response->redirect($url);
		}
	}
	
	/**
	* Function that logs in a user and stores the currently logged in user in session.
	*
	* @param id of the user to be logged in. 
	*
	*/
	public function logoutAction()
	{	
		$content = "<p>You're currently logged in as user: " . ucfirst($this->users->currentUser()) . "</p>";
		
		// Render form.
		$this->theme->setTitle("Logout");
		$this->views->add('default/page-2', [
			'title' 	=> "Logout",
			'content' 	=> $content,
			'content2' 	=> $this->getLogoutForm(),
		]);
	}
	
	/**
	* Add an options menu as view.
	* 
	* @return void
	*/
	public function menuAction()
	{
		$values = ['Add', 'List-all', 'List-active', 'List-trash'];
		$this->views->add('users/menu', [
			'values' => $values,
			'url'	 => 'Users/'
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
				
				$all = $this->users->findAll();
				
				$this->theme->setTitle("Create Table");
				$this->views->add('users/list-all', [
					'admin'	=> $this->users->isUserAdmin($this->users->currentUser(), ['admin']),
					'users' => $all,
					'title' => "Table Successfully created!",
					'redirect' => [
						"Users/id/",
						"Users/update/",
						"Users/softDelete/",
						"Users/restore/"]
				]);
			}
		}
		else
		{
			$this->listAction();
		}
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
	 
		$all = $this->users->findAll();
	 
		$this->theme->setTitle("List all users");
		$this->views->add('users/list-all', [
			'admin'	=> $this->users->isUserAdmin($this->users->currentUser(), ['admin']),
			'users' => $all,
			'title' => "View all users",
			'redirect' => [
			"Users/id/",  
			"Users/update/", 
			"Users/softDelete/", 
			"Users/restore/"]
		]);
	}
	
	/**
	* List user with id.
	*
	* @param int $id of user to display
	*
	* @return void
	*/
	public function idAction($id = null)
	{
		$this->initialize();
		
		$this->menuAction();
		
		$user = $this->users->find($id);
		
		if(!empty($user))
		{
			$this->theme->setTitle("View user with id");
		
			$this->views->add('users/view', [
				'admin'	=> $this->users->isUserAdmin($this->users->currentUser(), ['admin', $user->acronym]),
				'superadmin' => $this->users->isUserAdmin($this->users->currentUser(), ['admin']),
				'user' => $user,
				'title' => "View user: " . $user->name,
				'redirect' => [
				"Users/update/",
				"Users/softDelete/",
				"Users/restore/",
				"Users/delete/"]
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
			$this->views->add('users/none', []);
		}
	}
	 
	/**
	 * Cheat function to add a new user in a simplified way.
	 * Add new user.
	 *
	 * @param string $acronym of user to add.
	 *
	 * @return void
	 */
	public function addAction()
	{
		$this->initialize();
		
		$this->menuAction();
		
		$this->users->created = gmdate('Y-m-d H:i:s');
		
		// Render form.
		$this->theme->setTitle("Create User");
		$this->views->add('default/page', [
			'title' => "Create a user",
			'content' => $this->getUserForm()
		]);
	}
	
	public function restoreAction($id = null)
	{
		if(!isset($id))
		{
			die("Missing id.");
		}
		
		$now = gmdate("Y-m-d H:i:s");
		
		$user = $this->users->find($id);
		
		$user->deleted = null;
		$user->active = $now;
		$user->save();
		
		//Create a url to the updated object.
		$url = $this->url->create('Users/id/' . $id);
		$this->response->redirect($url);
	}
	
	public function updateAction($id = null)
	{
		if(!isset($id))
		{
			die("Missing id.");
		}
		
		$this->menuAction();
		
		$user = $this->users->find($id);
		
		$this->users->updated = gmdate('Y-m-d H:i:s');
		
		$values = [
			'acronym' 	=> $user->acronym,
			'email'		=> $user->email,
			'name' 		=> $user->name,
			'password'	=> $user->password,
		];
		
		// Render form.
		$this->theme->setTitle("Update User");
		$this->views->add('default/page', [
			'title' => "Update a user",
			'content' => $this->getUserForm($values)
		]);
	}
	
	/**
	* Delete user.
	*
	* $param integer $id of user to delete.
	*
	* @return void
	*/
	public function deleteAction($id = null)
	{
		if(!isset($id))
		{
			die("Missing id.");
		}
		
		$res = $this->users->delete($id);
		
		$url = $this->url->create('Users/list');
		$this->response->redirect($url);
	}
	
	/*
	* Soft delete User.
	*
	* @param integer, id for the User to delete.
	*
	* @return void.
	*/
	public function softDeleteAction($id = null)
	{
		if(!isset($id))
		{
			die("Missing id.");
		}
		
		$now = gmdate("Y-m-d H:i:s");
		
		$user = $this->users->find($id);
		
		$user->deleted = $now;
		$user->save();
		
		//Create a url to the updated object.
		$url = $this->url->create('Users/id/' . $id);
		$this->response->redirect($url);
	}
	
	/**
	* List all active and not deleted users.
	*
	* $return void
	*/
	public function activeAction()
	{
		$this->menuAction();
		
		$all = $this->users->query()
			->where('active is NOT NULL')
			->andWhere('deleted is NULL')
			->execute();
			
		$this->theme->setTitle("Users that are active");
		$this->views->add('users/list-all', [
			'admin'	=> $this->users->isUserAdmin($this->users->currentUser(), ['admin']),
			'users' => $all,
			'title' => "View all users",
			'redirect' => [
			"Users/id/",  
			"Users/update/", 
			"Users/softDelete/",
			"Users/restore/"]
		]);
	}
	
	/**
	* List all deleted users.
	*
	* @return void
	*/
	public function deletedAction()
	{
		$this->initialize();
		
		$this->menuAction();
		
		$all = $this->users->query()
			->where('deleted is NOT NULL')
			->execute();
			
		$this->theme->setTitle("Users that are deleted");
		$this->views->add('users/list-all', [
			'admin'	=> $this->users->isUserAdmin($this->users->currentUser(), ['admin']),
			'users' => $all,
			'title' => "View all users",
			'redirect' => [
			"Users/id/",  
			"Users/update/", 
			"Users/softDelete/", 
			"Users/restore/"]
		]);
	}
	
	/*
	* Get a form for logging in a user.
	*
	* @param optional, values to be put into the textfields.
	*
	* @return the HTML code of the form.
	*/
	private function getLoginForm()
	{
		// Initiate object instance.
		$form = new \Mos\HTMLForm\CForm();
		
		// Create form.
		$form = $form->create([], [
			'acronym' => [
				'type' 		 => 'text',
				'required' 	 => true,
				'class' 	 => 'cform-textbox',
				'validation' => ['not_empty'],
			],
			'password' => [
				'type' 		 => 'password',
				'required' 	 => true,
				'class' 	 => 'cform-textbox',
				'validation' => ['not_empty'],
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
     * Callback for login-button success.
     *
     */
	public function loginSubmit($form)
    {		
		$success = false;
		// Get acronym from form and escape it.
		$acronym = strtolower($this->escaper->escapeHTML($form->Value('acronym')));
		// Hash the password.
		$password = md5($form->Value('password'));
		//$password = strtolower($form->Value('password'));
		// Ask the module if user is valid.
		if($this->users->validateUser($acronym, $password))
		{
			// If the user is valid, get the user from the db.
			$this->users->id = $this->users->findByColumn('acronym', $form->Value('acronym'))[0]->id;

			// Update the users active variable.
			$form->saveInSession = true;
			$now = gmdate('Y-m-d H:i:s');
			$updated = $this->users->update([
				'id'		=> $this->users->id,
				'active' 	=> $now,
			]);
			
			if($updated)
			{	// Save user in session.
				$this->users->loginUser($acronym);
				$success = true;
			}
			
			$url = $this->url->create('Users/id/' . $this->users->id);
			$this->response->redirect($url);
		}

        return $success;
    }
	
	/**
     * Callback for submit-button.
     *
     */
    public function loginSuccess($form)
    {
        $form->AddOutput("<p><i>You've been logged in.</i></p>");
        return false;
    }

    /**
     * Callback for submit-button.
     *
     */
    public function loginFail($form)
    {
        $form->AddOutput("<p><i>Invalid login information.</i></p>");
        return false;
    }
	
	/*
	* Get a form for logging out a user.
	*
	* @param optional, values to be put into the textfields.
	*
	* @return the HTML code of the form.
	*/
	private function getLogoutForm()
	{
		// Initiate object instance.
		$form = new \Mos\HTMLForm\CForm();
		
		// Create form.
		$form = $form->create([], [
			'logout' => [
				'type' 		 => 'submit',
				'class' 	 => 'cform-submit',
				'callback'  => [$this, 'logoutSubmit'],
				'value'		=> 'Logout'
			],
		]);
		
		// Check the status of the form.
		$form->check([$this, 'logoutSuccess'], [$this, 'logoutFail']);
				
		return $form->getHTML();
	}
	
	/**
     * Callback for login-button success.
     *
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
			
			$url = $this->url->create('Users/Login');
			$this->response->redirect($url);
		}

        return $success;
    }
	
	 /**
     * Callback for submit-button.
     *
     */
    public function logoutSuccess($form)
    {
        $form->AddOutput("<p><i>You've been logged out.</i></p>");
        return false;
    }

    /**
     * Callback for submit-button.
     *
     */
    public function logoutFail($form)
    {
        $form->AddOutput("<p><i>You're not logged in.</i></p>");
        return false;
    }

	/*
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
	
	/**
     * Callback for submit-button success.
     *
     */
	public function callbackSubmit($form)
    {			
		// Save form.
		$form->saveInSession = true;
		$now = gmdate('Y-m-d H:i:s');
			$this->users->save([
				'acronym' 	=> strtolower($form->Value('acronym')),
				'email' 	=> $form->Value('email'),
				'name' 		=> $form->Value('name'),
				'password' 	=> md5($form->Value('password')),
				'created' 	=> $this->users->created,
				'updated'	=> isset($this->users->updated) ? $this->users->updated : null,
				'active' 	=> $now,
			]);

		$url = $this->url->create('Users/id/' . $this->users->id);
		$this->response->redirect($url);
		
        return true;
    }
	
	 /**
     * Callback for submit-button.
     *
     */
    public function callbackSuccess($form)
    {
        $form->AddOutput("<p><i>User Created.</i></p>");
        return false;
    }

    /**
     * Callback for submit-button.
     *
     */
    public function callbackFail($form)
    {
        $form->AddOutput("<p><i>DoSubmitFail(): Form was submitted but I failed to process/save/validate it</i></p>");
        return false;
    }
}