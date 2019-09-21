<?php

namespace Anax\Users;

/**
* Anax base class for wrapping sessions.
*
*/
class CFormUserModel extends \Anax\HTMLForm\CFormModel
{
    /**
    * Get a form for creating a question
    *
    * @return the HTML code of the form.
    */
    public function createUserForm($scope, $callback, $values=null)
    {
        $this->create([], [
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
    			'type' 		    => 'submit',
    			'class' 	    => 'cform-submit',
    			'callback'      => $callback,
                "callback-args" => [$scope],
    			'value'		    => 'Submit user'
			]
        ]);

        // Check the status of the form.
        $this->check([$this, 'callbackSuccess'], [$this, 'callbackFail']);

        return $this->getHTML();
    }

    public function createUserLoginForm($scope, $callback)
    {
        $this->create([], [
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
                'type'          => 'submit',
                'class'         => 'cform-submit',
                'callback'      => $callback,
                "callback-args" => [$scope],
                'value'         => 'Login'
            ]
        ]);

        // Check the status of the form.
        $this->check([$this, 'callbackSuccess'], [$this, 'callbackFail']);

        return $this->getHTML();
    }

    public function createUserLogoutForm($scope, $callback)
    {
        $this->create([], [
            'logout' => [
                'type'          => 'submit',
                'class'         => 'cform-submit',
                'callback'      => $callback,
                "callback-args" => [$scope],
                'value'         => 'Logout'
            ]
        ]);

        // Check the status of the form.
        $this->check([$this, 'callbackSuccess'], [$this, 'callbackFail']);

        return $this->getHTML();
    }

    public function callbackSubmit()
    {
        // Leave this empty.
    }
}
