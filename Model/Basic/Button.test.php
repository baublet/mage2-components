<?php

require_once(dirname(__FILE__) . '/../Base.php');
require_once('Button.php');

$btn1 = new Rsc\Components\Model\Basic\Button();
$btn1->setVariables(['text' => 'test']);

$rendered = $btn1->render();

assert( (bool) stristr($rendered, 'test') );
assert( (bool) stristr($rendered, '<input') );

$btn1->setVariables([
    'url' => 'http://www.google.com',
    'text' => 'test'
]);

$rendered = $btn1->render();

assert( (bool) stristr($rendered, 'test') );
assert( (bool) stristr($rendered, 'http://www.google.com') );
assert( (bool) stristr($rendered, 'href="') );
