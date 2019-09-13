<?php

namespace Anax\Forum;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormQuestionModel extends \Mos\HTMLForm\CFormModel
{
    /**
    * Get a form for creating a question
    *
    * @return the HTML code of the form.
    */
    public function createQuestionForm($scope, $callback)
    {
        // Initiate object instance.
        //$form = new \Mos\HTMLForm\CForm();
        //var_dump($scope);

        // Create form.
        $this->create([], [
            'title' => [
                'type'          => 'text',
                'required'      => true,
                'class'         => 'cform-textbox',
                'validation'    => ['not_empty'],
                'value'         => ''
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
                'value'         => 'Post question'
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
