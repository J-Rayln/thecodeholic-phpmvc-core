<?php

namespace app\core\form;

/**
 * Class TextareaField.
 * 
 * Generates and renders form fields.
 * 
 * @package app\core\form
 */
class TextareaField extends BaseField
{
    public function renderInput(): string
    {
        return sprintf(
            '<textarea class="form-control%s" id="%s" name="%s">%s</textarea>',
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->attribute,
            $this->attribute,
            $this->model->{$this->attribute}
        );
    }
}
