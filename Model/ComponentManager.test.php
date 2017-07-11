<?php

require_once('ComponentManager.php');

$componentsDirectory = realpath(join(
    DIRECTORY_SEPARATOR,
    [dirname(__FILE__)]
));

$auto = spl_autoload_register(function ($class) use ($componentsDirectory) {
    $qualifiedName = str_replace('Rsc\Components\Model', '', $class);
    $qualifiedName = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $qualifiedName);
    $fileName = $componentsDirectory . $qualifiedName . '.php';
    include_once $fileName;
}, true);

assert($auto);

$cm = new Rsc\Components\Model\ComponentManager();

assert( get_class($cm->create('Basic\Button')) == 'Rsc\Components\Model\Basic\Button');
