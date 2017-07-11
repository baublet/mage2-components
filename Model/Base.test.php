<?php

require_once('Base.php');

$base = new Rsc\Components\Base();
$base->setVariables(['a' => 1, 'b' => 2]);

assert($base->getVariable('a') == 1);
assert($base->getVariable('b') == 2);

$base->setVariable('a', 2);

assert($base->getVariable('a') == 2);
