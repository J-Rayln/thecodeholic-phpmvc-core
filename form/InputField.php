<?php

namespace app\core\form;

use app\core\Model;

/**
 * Class InputField.
 * 
 * Generates and renders form fields.
 * 
 * @package app\core\form
 */
class InputField extends BaseField
{
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';

    public string $type;

    /**
     * Main constructor.
     * 
     * @param \app\core\Model $model 
     * @param string $attribute 
     * @return void 
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }

    /**
     * Sets the input form field type to "password".
     * 
     * @return $this 
     */
    public function passwordField()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function renderInput(): string
    {
        return sprintf(
            '<input type="%s" class="form-control%s" id="%s" name="%s" value="%s">',
            $this->type,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->attribute,
            $this->attribute,
            $this->model->{$this->attribute}
        );
    }
}
