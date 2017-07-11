<?php

namespace Rsc\Components\Model;

class ComponentManager
{
    protected $componentsPath       = null,
              $componentNamspace    = 'Rsc\\Components\\Model\\';

    public function __construct()
    {
        if($this->componentsPath == null)
        {
            $this->componentsPath = realpath(dirname(__FILE__));
        }
    }

    protected function componentToPath($component)
    {
        return  $this->componentsPath . DIRECTORY_SEPARATOR .
                str_replace(
                    ['\\', '/'],
                    DIRECTORY_SEPARATOR,
                    $component
                ) . '.php';
    }

    public function create($name, $variables = [])
    {
        $componentClass = $this->componentNamspace . $name;
        if(!class_exists($componentClass, true))
        {
            throw new \Exception('Unable to load component ' .
                                    $name . ' using class ' .
                                    $componentClass);
        }

        $component = new $componentClass();
        $component->setVariables($variables, $variables);
        return $component;
    }
}
