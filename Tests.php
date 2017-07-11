<?php

function rglob($pattern, $flags = 0) {
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
    }
    return $files;
}

$tests = rglob(realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '**/*.test.php');
$testsCount = count($tests);

echo "Running {$testsCount} tests...\n\n";

foreach($tests as $test)
{
    $basename = basename($test);
    echo "- {$basename}\n\n";
    require($test);
}

echo "Finished.";
