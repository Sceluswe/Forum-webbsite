<?php

namespace Anax\Forum;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormAnswerModel extends \Anax\HTMLForm\CFormModel
{
    /**
    * Get a form for creating a question
    *
    * @return the HTML code of the form.
    */
    public function createAnswerForm($values, $scope, $callback)
    {
        // Create form.
        $this->create([], [
            'questionid' => [
				'type'          => 'hidden',
				'required'      => true,
				'validation'    => ['not_empty'],
				'value'         => $values['questionid']
			],
			'content' => [
				'type'          => 'textarea',
				'required'      => true,
				'class'         => 'cform-textarea',
				'validation'    => ['not_empty'],
				'value'         => ''
			],
			'submit' => [
				'type'          => 'submit',
				'class'         => 'cform-submit',
                'callback'      => $callback,
                "callback-args" => [$scope],
				'value'         => 'Post answer'
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
