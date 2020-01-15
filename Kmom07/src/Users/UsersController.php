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
    }



    /**
    * Displays the currently logged in user and links to its profile.
    *
    * @return void.
    */
    public function statusAction()
    {
        $userlink = "<p>You are currently not logged in: <a href=\"" . $this->url->create($this->redirect["login"]) . "\">Login</a></p>";

        if ($this->users->isUserLoggedIn()) {
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
    public function profileAction($id)
    {
        $this->initialize();

        $this->menuAction();

        $user = $this->users->find($id);

        if (!empty($user)) {
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
        } else {
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

        $obj = new \Anax\Users\CFormUserModel();
        $this->utility->renderDefaultPage("Create User", $obj->createUserForm($this, function ($form, $scope) {
            $scope->users->save([
                'acronym' 	=> strtolower($form->Value('acronym')),
                'email' 	=> $form->Value('email'),
                'name' 		=> $form->Value('name'),
                'password' 	=> md5($form->Value('password')),
                'created' 	=> $scope->users->created,
                'updated'	=> isset($scope->users->updated) ? $scope->users->updated : null,
                'active' 	=> gmdate('Y-m-d H:i:s')
            ]);

            $scope->utility->createRedirect($scope->redirect["profile"] . $scope->users->id);

            return true;
        }));
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
        if ($this->users->isUserAdmin($this->users->currentUser(), ['admin'])) {
            if ($this->users->initializeTable('user')) {
                $this->menuAction();

                $this->theme->setTitle("Create Table");
                $this->views->add($this->template["list-all"], [
                    'admin'	=> $this->users->isUserAdmin($this->users->currentUser(), ['admin']),
                    'users' => $this->users->findAll(),
                    'title' => "Table Successfully created!",
                    'redirect' => $this->redirect
                ]);
            }
        } else {
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
        if (!isset($id)) {
            die("Missing id.");
        }

        $this->menuAction();

        $user = $this->users->find($id);
        $this->users->updated = gmdate('Y-m-d H:i:s');

        $obj = new \Anax\Users\CFormUserModel();

        $callback = function ($form, $scope) {
            $scope->users->save([
                'acronym' 	=> strtolower($form->Value('acronym')),
                'email' 	=> $form->Value('email'),
                'name' 		=> $form->Value('name'),
                'password' 	=> md5($form->Value('password')),
                'created' 	=> $scope->users->created,
                'updated'	=> isset($scope->users->updated) ? $scope->users->updated : null,
                'active' 	=> gmdate('Y-m-d H:i:s')
            ]);

            $scope->utility->createRedirect($scope->redirect["profile"] . $scope->users->id);

            return true;
        };

        $this->utility->renderDefaultPage("Update User", $obj->createUserForm($this, $callback, [
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
        if (!isset($id)) {
            die("Missing id.");
        }

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
        if (!isset($id)) {
            die("Missing id.");
        }

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
        if (!isset($id)) {
            die("Missing id.");
        }

        $user = $this->users->find($id);
        $user->deleted = gmdate("Y-m-d H:i:s");
        $user->save();

        //Create a url and redirect to the updated object.
        $this->utility->createRedirect($this->redirect["profile"] . $id);
    }



    /**
    * Function that logs in a user and stores the currently logged in user in session.
    *
    * @return void
    */
    public function loginAction()
    {
        $this->dispatcher->forwardTo("Users", "status");

        $obj = new \Anax\Users\CFormUserModel();
        $callback = function ($form, $scope) {
            $success = false;

            // Get acronym from form and escape it and hash the password.
            $acronym = strtolower($scope->escaper->escapeHTML($form->Value('acronym')));
            $password = md5($form->Value('password'));

            if ($scope->users->validateUser($acronym, $password)) {
                $scope->users->findByAcronym($form->Value('acronym'));

                if ($scope->users->update(['active' => gmdate('Y-m-d H:i:s')])) {
                    $scope->users->loginUser($acronym);
                    $success = true;
                }

                $scope->utility->createRedirect($scope->redirect["profile"] . $scope->users->id);
            }

            return $success;
        };

        (!$this->users->isUserLoggedIn())
            ? $this->utility->renderDefaultPage($this->redirect["login"], $obj->createUserLoginForm($this, $callback))
            : $this->utility->createRedirect($this->redirect["logout"]);
    }



    /**
    * Function presents a logout form to the user.
    *
    * @return void
    */
    public function logoutAction()
    {
        $this->dispatcher->forwardTo("Users", "status");

        $obj = new \Anax\Users\CFormUserModel();
        $this->utility->renderDefaultPage("Logout", $obj->createUserLogoutForm($this, function ($form, $scope) {
            $success = false;

            if ($this->users->isUserLoggedIn()) {
                $this->users->logoutUser();
                $success = true;

                $this->utility->createRedirect($this->redirect["login"]);
            }

            return $success;
        }));
    }
}
