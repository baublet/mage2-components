<?php

require_once(dirname(__FILE__) . '/../Base.php');
require_once('FormElement.php');
require_once('FormGroup.php');
require_once(dirname(__FILE__) . '/../Input/Text.php');

$in1 = new Rsc\Components\Input\Text();
$in1->setVariables(['name' => 'test']);
$in2 = new Rsc\Components\Input\Text();
$in2->setVariables(['name' => 'test2']);

$fe1 = new Rsc\Components\Group\FormElement();
$fe1->setVariables(['labelText' => 'Test Input 1', 'element' => $in1]);

$fe2 = new Rsc\Components\Group\FormElement();
$fe2->setVariables(['labelText' => 'Test Input 2', 'element' => $in2]);

$fg = new Rsc\Components\Group\FormGroup();
$fg->setVariables(['elements' => [$fe1, $fe2], 'groupLabel' => 'Form Group']);

$rendered = $fg->render();

assert( (bool) stristr($rendered, 'for="label_') );
assert( (bool) stristr($rendered, 'Test Input 1') );
assert( (bool) stristr($rendered, 'Test Input 2') );
assert( (bool) stristr($rendered, 'type="text"') );
assert( (bool) stristr($rendered, 'Form Group') );
