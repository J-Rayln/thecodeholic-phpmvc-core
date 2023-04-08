<?php

namespace app\core\form;

use app\core\Model;

abstract class BaseField
{
    public Model $model;
    public string $attribute;

    /**
     * Main constructor.
     * 
     * @param \app\core\Model $model 
     * @param string $attribute 
     * @return void 
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    abstract public function renderInput(): string;

    /**
     * Renders the form field group for input fields.
     * 
     * @return string 
     */
    public function __toString()
    {
        // If the field has errors, render the invalid feedback div.
        $errorMsg = $this->model->hasError($this->attribute) ? sprintf(
            '<div class="invalid-feedback">%s</div>',
            $this->model->getFirstError($this->attribute)
        ) : '';

        return sprintf(
            '
            <div class="col mb-3">
                <label for="%s" class="form-label">%s</label>
                %s
                %s
            </div>
        ',
            $this->attribute,
            $this->model->getLabel($this->attribute),
            $this->renderInput(),
            $errorMsg
        );
    }
}
