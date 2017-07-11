<?php

namespace Rsc\Components;

use Rsc\Components\Cache\Cache;

class Base implements \JsonSerializable
{

    // The base directory of our view/frontend/Components directory
    protected   $baseDir = null,
                $file = null,
                $templatePath = null,
                $variables = [],
                $cacheHandler = null;

    // Variables required for this component to be rendered. Leave an empty
    // array if you don't want any requirements.
    protected $requiredVariables = [];

    public function __construct($templateDirectory = null)
    {
        if($templateDirectory !== null)
        {
            $this->setDirectory($templateDirectory);
        }
        else
        {
            $this->setDirectory(
                realpath(
                    join( DIRECTORY_SEPARATOR,
                         [dirname(__FILE__), '..', 'view', 'frontend', 'Components']
                     )
                )
            );
        }
        $this->setFile($this->file);
    }

    public function jsonSerialize()
    {
        return [ 'filePath' => $this->templatePath,
                 'data' => $this->variables ];
    }

    public function setDirectory($dir)
    {
        if(!file_exists($dir))
        {
            throw new \Exception('Cannot find component base directory ' . $dir);
        }
        $this->baseDir = $dir;
        return $this;
    }

    public function setFile($file)
    {
        $path = $this->baseDir . DIRECTORY_SEPARATOR . $file;
        if(!file_exists($path))
        {
            throw new \Exception('Cannot find component file ' . $path);
        }
        $this->templatePath = $path;
        $this->file = $file;
        return $this;
    }

    public function setCacheHandler(Cache $cacheHandler)
    {
        $this->cacheHandler = $cacheHandler;
    }

    protected function hasCacheHandler()
    {
        return !$this->cacheHandler == null;
    }

    protected function isCached()
    {
        return $this->cacheHandler->has($this->cacheKey());
    }

    protected function saveCachedVersion($rendered)
    {
        $this->cacheHandler->set($this->cacheKey(), $rendered);
    }

    protected function cachedVersion()
    {
        return $this->cacheHandler->get($this->cacheKey());
    }

    /**
     * A representation of this component's variables and values in a simplistic
     * key form for the purposes of caching.
     * @return string
     */
    protected function cacheKey()
    {
        return json_encode($this);
    }

    public function setVariables($variables)
    {
        $this->variables = $variables;
        return $this;
    }

    public function setVariable($key, $value)
    {
        $this->variables[$key] = $value;
        return $this;
    }

    public function getVariable($key)
    {
        if(isset($this->variables[$key])) {
            return $this->variables[$key];
        }
        return null;
    }

    protected function cleanRequire($templatePath, $templateVariables = [])
    {
        if(is_array($templateVariables))
        {
            extract($templateVariables);
        }
        require $templatePath;
    }

    public function requireVariables()
    {
        foreach($this->requiredVariables as $key)
        {
            if(isset($this->variables[$key])) continue;
            throw new \Exception(get_class($this) . ' requires template variable ' . $key . ' to be set before rendering.');
        }
    }

    public function render($variables = [])
    {
        if(count($this->requiredVariables) > 0)
        {
            $this->requireVariables();
        }

        if(count($variables) > 0)
        {
            $this->setVariables($variables);
        }

        if($this->hasCacheHandler() && $this->isCached())
        {
            return $this->cachedVersion();
        }

        ob_start();
        $this->cleanRequire($this->templatePath, $this->variables);
        $rendered = ob_get_clean();

        if($this->hasCacheHandler())
        {
            $this->saveCachedVersion($rendered);
        }

        return $rendered;
    }

    /**
     * Attribute and element helpers
     */

    /**
     * Prints a variable with the value as <attributeName>="<attributeValue">
     * @param string $variable
     */
    protected function attr($variable)
    {
        echo "{$variable}=\"{$this->variables[$variable]}\"";
    }

    /**
     * Prints a variable with the value as <attributeName>="<attributeValue">
     * if the attribute exists in the Component's variables. If the variable
     * does not exist, it does not render anything.
     * @param string $variable
     */
    protected function attrIf($variable)
    {
        if(isset($this->variables[$variable]))
        {
            $this->attr($variable);
        }
    }

    /**
     * Prints a template variable as an attribute in the same as $this->attributeId(),
     * but instead of hiding the attribute if the variable is not set, this will
     * print a default value.
     * @param  string $variable
     * @param  mixed $default
     */
    protected function attrDefault($variable, $default)
    {
        $value = isset($this->variables[$variable]) ? $this->variables[$variable] : $default;
        echo "{$variable}=\"{$value}\"";
    }

    /**
     * Prints a template variable as an attribute with a portion of content pre-
     * pended to the value. For example, if you want all of your input boxes to
     * have a base class name of "textInput", you would use this function to
     * let devs set other custom classes to it, without having to constantly
     * add it.
     *
     * <?php $this->attrPlus('class', 'textInput'); ?>
     *
     * @param  string $variable Variable to turn into an attribute
     * @param  string $base     The base attribute value. Spaces are automatically prepended
     * @return [type]           [description]
     */
    protected function attrPlus($variable, $base)
    {
        $value = isset($this->variables[$variable]) ? "$base {$this->variables[$variable]}" : $base;
        echo "{$variable}=\"{$value}\"";
    }

    /**
     * Prints $html if $condition is true. Useful shorthand for simple markup
     * with conditions to prevent tons of indentations.
     */
    protected function echoIf($condition, $html)
    {
        if($condition) echo $html;
    }
}
