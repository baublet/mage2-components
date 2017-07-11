<?php

require_once(dirname(__FILE__) . '/../Model/Base.php');
require_once(dirname(__FILE__) . '/../Model/Basic/Button.php');
require_once('Cache.php');
require_once('Memory.php');
require_once(dirname(__FILE__) . '/../Model/ComponentManager.test.php');

$cm = new Rsc\Components\Model\ComponentManager();
$cache = new Rsc\Components\Cache\Memory();

$cm->setCacheHandler($cache);

$btn = $cm->create('Basic\Button');
$btn->setVariables(['text' => 'test']);
$btn->render();

assert($cache->has(json_encode($btn)));

$btn2 = $cm->create('Basic\Button');
$btn2->setVariables(['text' => 'test']);
$btn2->render();

assert($cache->has(json_encode($btn)));
assert($cache->has(json_encode($btn2)));

$btn->setVariables(['text' => 'test2']);
assert(!$cache->has(json_encode($btn)));

$btn->render();
assert($cache->has(json_encode($btn)));

assert($cache->get(json_encode($btn)) == $btn->render());
