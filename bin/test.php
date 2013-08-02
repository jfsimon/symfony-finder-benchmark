<?php

$run = function ($name, \Closure $test) {
    xhprof_enable();
    for ($i = 0; $i < 12220; $i++) { $test(); }
    $data = xhprof_disable();
    $time = array_sum(array_map(function (array $entry) { return $entry['wt']; }, $data));
    echo "$name: $time\n";
};

$run('SplFileInfo', function () { new \SplFileInfo(__FILE__); });
