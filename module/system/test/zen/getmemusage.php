#!/usr/bin/env php
<?php

/**

title=测试 systemZen::getMemUsage();
timeout=0
cid=0

- 执行systemTest模块的getMemUsageTest方法，参数是$metrics1 属性color @gray
- 执行systemTest模块的getMemUsageTest方法，参数是$metrics2 属性color @var(--color-secondary-500)
- 执行systemTest模块的getMemUsageTest方法，参数是$metrics3 属性color @var(--color-warning-500)
- 执行systemTest模块的getMemUsageTest方法，参数是$metrics4 属性color @var(--color-important-500)
- 执行systemTest模块的getMemUsageTest方法，参数是$metrics5 属性color @var(--color-danger-500)

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$systemTest = new systemZenTest();

$metrics1 = new stdClass();
$metrics1->rate = 0;
$metrics1->usage = 0;
$metrics1->capacity = 8192000;

$metrics2 = new stdClass();
$metrics2->rate = 30;
$metrics2->usage = 2457600;
$metrics2->capacity = 8192000;

$metrics3 = new stdClass();
$metrics3->rate = 60;
$metrics3->usage = 4915200;
$metrics3->capacity = 8192000;

$metrics4 = new stdClass();
$metrics4->rate = 75;
$metrics4->usage = 6144000;
$metrics4->capacity = 8192000;

$metrics5 = new stdClass();
$metrics5->rate = 90;
$metrics5->usage = 7372800;
$metrics5->capacity = 8192000;

r($systemTest->getMemUsageTest($metrics1)) && p('color') && e('gray');
r($systemTest->getMemUsageTest($metrics2)) && p('color') && e('var(--color-secondary-500)');
r($systemTest->getMemUsageTest($metrics3)) && p('color') && e('var(--color-warning-500)');
r($systemTest->getMemUsageTest($metrics4)) && p('color') && e('var(--color-important-500)');
r($systemTest->getMemUsageTest($metrics5)) && p('color') && e('var(--color-danger-500)');