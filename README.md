# RSC Components

A slim, discreet, reusable component library and framework to ease interoperability between various parts of a Magento2 installation.

## Components

For the purposes of this extension, a component is any portion of HTML markup that will be rendered onto the page using an assortment of PHP variables, and which *may* in the future, be reused. If you think there is even a chance that your markup will be reused, make it a component.

## Structure

Components all inherit the Base component, and are grouped into various categories:

```
Model/Basic         Basic components, such as buttons or links
Model/Groups        Components that use other components in their rendering, such as FormGroups
Model/Input         Components related to user input
```

Components' templates reside in the extension's `view/frontend/Components` directory. When you create new components, please place your new components in its corresponding directory.

## Creating a New Component

First thing's first: we want this component to be tested. If it's not tested, it's not working. So figure out where in the directory (`Components/Model`) your component belongs (create a new one if you want), and create the `MyComponent.test.php` file.

### Testing

We have eschewed a bulky testing framework for testing these components, since the goal is to be lightweight and ensure basic functionality tests at the PHP level. For ensuring more robust UI testing and hunting down regressions, the component library is intended to be displayed on a styleguide or pattern library.

When you create your own tests, test the most basic unit available that you are fairly sure will always be the responsibility of this component.

For example, if you make a link component, you may not be sure that the link will have a `class`, `title`, or `id` attribute. But because it is a link, you can be pretty certain that it will have a `href` attribute, and contain some content text. So let's test those.

```php
<?php
// Basic/Link.test.php

require_once(dirname(__FILE__) . '/../Base.php');
require_once('Link.php');

$a = new Rsc\Components\Model\Basic\Link();
$a->setVariables(['href' => 'http://www.google.com', 'text' => 'test']);

$rendered = $a->render();

// Basic assertions are good enough for this use case
assert( (bool) stristr($rendered, '<a') );
assert( (bool) stristr($rendered, 'href="') );
assert( (bool) stristr($rendered, 'http://www.google.com') );
assert( (bool) stristr($rendered, 'test') );
```

This basic test will ensure that what we output is indeed a link, that it contains a `href` attribute, that the link itself appears somewhere in the rendered output, and the same for the link text.

Now you can test your component by either CDing into the directory and running `php Link.test.php`, or in the Components base directory, run `php Tests.php`, which will test all of your components.

### Component Class

Creating a component is as simple as creating both the class and the template that the class renders. To continue our link example:

```php
<?php
// Components/Model/Basic/Link.php

namespace Rsc\Components\Model\Basic;
use Rsc\Components\Base;

class Link extends Base
{
                      // Template file, relative to Components/view/frontend/Components
    protected $file = 'Basic/Link.phtml';
                      // Variables required to render this component without an exception
    protected $requiredVariables = ['url', 'text'];
}
```

This is a simple component that needs no special handling of variables before being rendered. If you want information on what you would do to add pre-rendering customizations, see `Components/Model/Base.php`, and some of the built-in components.

### Templates

Templates are standard `.phtml` files that render markup given a handful of variables passed to the component via the `setVariable($key, $value)` or the `setVariables($variables = [])` classes. The script extracts the variables so that, e.g., `['url' => 'http://www.google.com']` becomes just `$url = 'http://www.google.com';`.

```php
<!-- Components/view/frontend/Components/Basic/Link.phtml -->
<a href="<?php echo $url; ?>"><?php echo $text; ?></a>
```

### Template Helper Methods

The base class provides you with a slew of helpers to shorten your components and elevate display logic out of your components as much as possible.

```
/**
 * Prints a variable with the value as <attributeName>="<attributeValue">
 * @param string $variable
 */
protected function attr($variable)

/**
 * Prints a variable with the value as <attributeName>="<attributeValue">
 * if the attribute exists in the Component's variables. If the variable
 * does not exist, it does not render anything.
 * @param string $variable
 */
protected function attrIf($variable)

/**
 * Prints a template variable as an attribute in the same as $this->attributeId(),
 * but instead of hiding the attribute if the variable is not set, this will
 * print a default value.
 * @param  string $variable
 * @param  mixed $default
 */
protected function attrDefault($variable, $default)

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
 */
protected function attrPlus($variable, $base)

/**
 * Prints $html if $condition is true. Useful shorthand for simple markup
 * with conditions to prevent tons of indentations.
 */
protected function echoIf($condition, $html)
```

For examples of where these are used, see the templates provided in the default components.

## Using Components

Components are intended to be used *beyond the scope of Magento2*, and should not themselves require anything except raw data that they turn into rendered HTML.

Therefore, there are three ways to call components:

### Indirectly, from within Templates and Blocks

This plugin attaches a reference to the primary instance of the `ComponentManager` to every Magento2 block, so that if you are in a block context (that is, most of Magento2 where you may use components), you can simply call this class via `$this->ComponentManager->...`.

### Indirectly, via Magento2 Dependency Injection

If you prefer to configure and render your components prior to the Magento2 core template `.phtml` files, and defer it to somewhere outside of a block or template context, you can always access the component manager by injecting it via [Dependency Injection in Magento2](http://devdocs.magento.com/guides/v2.0/extension-dev-guide/build/di-xml-file.html).

The alias for `Components/Model/ComponentManager.php` is `component_manager`. So you would create the following `di.xml` for your module:

```xml
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
    <type name="Namespace\MyModule\MyClass">
        <arguments>
            ...
            <argument name="config" xsi:type="object">component_manager</argument>
            ...
        </arguments>
    </type>
</config>
```

### Directly (not preferred)

If you, for some reason, need to use a component outside of the context of Magento2, you can easily call these components by merely creating a new instance of the `Components/Model/ComponentManager()` class. This class is a factory for creating and handling the entire component library.

Because we can cache certain components, however, this method is not preferred within the context of Magento2.
