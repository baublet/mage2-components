<?php

namespace Rsc\Components\Group;
use Rsc\Components\Base;

class FormElement extends Base
{
    protected $file = 'Group/FormElement.phtml';
    protected $requiredVariables = ['labelText', 'element'];

    public function render($variables = [])
    {
        if(count($variables) > 0)
        {
            $this->setVariables($variables);
        }
        $element = $this->getVariable('element');
        /**
         * Create a unique element/label id/for relationship between the element
         * and the label.
         */
        if($element->getVariable('id') !== null)
        {
            $this->setVariable('for', $element->getVariable('id'));
        }
        else
        {
            $id = uniqid('label_');
            $this->setVariable('for', $id);
            $element->setVariable('id', $id);
        }
        if(isset($this->variables['labelClass']))
        {
            $this->setVariable('labelClass', $labelClass);
        }
        return parent::render();
    }
}
