<?php

namespace ns;

require __DIR__.'/../src/bootstrap.php';

use Symfony\Component\Yaml\Yaml;

class test {
    function a() { usleep(10); $s = 'hello'; for($i=1;$i<1000;$i++) $s = sha1($s); }
    function b() { $this->a(); }
    function c() { $this->a(); $this->a(); $this->a(); $this->a(); }
    function d() {
        xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
        $this->b(); $this->c();
        echo Yaml::dump(xhprof_disable());
    }
}

$t = new test();
$t->d();
