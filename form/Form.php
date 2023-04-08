<?php

namespace app\core\form;

use app\core\Model;

/**
 * Class Form.
 * 
 * Renders form opening and closing.
 * 
 * @package app\core\form
 */
class Form
{
    /**
     * Renders the form opening tag.
     * 
     * @param string $action URL the form should point to.
     * @param string $method POST or GET form method
     * @return \app\core\form\Form 
     */
    public static function begin(string $action, string $method)
    {
        echo sprintf('<form action="%s" method="%s">', $action, $method);
        return new Form();
    }

    /**
     * Closes a form.
     * 
     * @return void 
     */
    public static function end()
    {
        echo '</form>';
    }

    /**
     * Creates a new field group for the model and attribute specified.
     * 
     * @param \app\core\Model $model 
     * @param string $attribute Field name.  MUST be identical to the database field name.
     * @return \app\core\form\InputField 
     */
    public function field(Model $model, string $attribute)
    {
        return new InputField($model, $attribute);
    }
}
