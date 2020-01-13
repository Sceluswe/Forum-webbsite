<?php

namespace Anax\Forum;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormTagModel extends \Anax\HTMLForm\CFormModel
{
    /**
    * Get a form for creating a tag.
    *
    * @return the HTML code of the form.
    */
    public function createTagForm($values, $scope, $callback)
    {
        $this->create([], [
            'name' => [
                'type'          => ($values) ? 'hidden' : 'text',
                'required'      => true,
                'validation'    => ['not_empty'],
                'value'         => ($values) ? $values['name'] : ''
            ],
            'submit' => [
                'type'      => 'submit',
                'class'     => 'cform-submit',
                'callback'  => $callback,
                "callback-args" => [$scope],
                'value'     => ($values) ? "Add tag: {$values['name']}" : "Create tag"
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
