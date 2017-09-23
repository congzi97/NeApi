<?php

exit;
class staticTest {
    public function test() {
        $i = 0;
        $i++;
    }

    public static function testStatic() {
        $i = 0;
        $i++;
    }
}

$start = microtime(true);
$test = new staticTest();
for($i=0;$i<50000;$i++) {
    $test->test();
}
echo (microtime(true) - $start) ."\n";

$start = microtime(true);
for($i=0;$i<50000;$i++) {
    staticTest::testStatic();
}
echo microtime(true) - $start;
