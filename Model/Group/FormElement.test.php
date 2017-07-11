<?php

require_once(dirname(__FILE__) . '/../Base.php');
require_once(dirname(__FILE__) . '/../Input/Text.php');
require_once('FormElement.php');

$in = new Rsc\Components\Input\Text();
$in->setVariables(['name' => 'test']);

$fe = new Rsc\Components\Group\FormElement();
$fe->setVariables(['labelText' => 'Test Input', 'element' => $in]);

$rendered = $fe->render();

assert( (bool) stristr($rendered, 'Test Input') );
assert( (bool) stristr($rendered, 'type="text"') );
