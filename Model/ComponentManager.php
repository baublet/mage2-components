<?php

namespace Rsc\Components\Model;

use Rsc\Components\Cache\Cache;

class ComponentManager
{
    protected $componentsPath       = null,
              $componentNamspace    = 'Rsc\\Components\\Model\\',
              $cacheHandler         = null;

    public function __construct($cacheHandler = false)
    {
        if($this->componentsPath == null)
        {
            $this->componentsPath = realpath(dirname(__FILE__));
        }
        if($cacheHandler !== false)
        {
            $this->setCacheHandler($cacheHandler);
        }
    }

    public function setCacheHandler(Cache $cacheHandler)
    {
        $this->cacheHandler = $cacheHandler;
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
        if($this->cacheHandler !== null)
        {
            $component->setCacheHandler($this->cacheHandler);
        }
        return $component;
    }
}
