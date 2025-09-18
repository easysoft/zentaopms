#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::buildViewActions();
timeout=0
cid=0

- 执行productplanTest模块的buildViewActionsTest方法，参数是$testPlan1  @array
- 执行productplanTest模块的buildViewActionsTest方法，参数是$testPlan2  @array
- 执行productplanTest模块的buildViewActionsTest方法，参数是$testPlan3  @array
- 执行productplanTest模块的buildViewActionsTest方法，参数是$testPlan4  @array
- 执行productplanTest模块的buildViewActionsTest方法，参数是$testPlan5  @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplanzen.unittest.class.php';

su('admin');

$productplanTest = new productplanZenTest();

$testPlan1 = new stdClass();
$testPlan1->id = 1;
$testPlan1->status = 'wait';
$testPlan1->parent = 0;
$testPlan1->product = 1;
$testPlan1->branch = 0;

$testPlan2 = new stdClass();
$testPlan2->id = 2;
$testPlan2->status = 'doing';
$testPlan2->parent = 0;
$testPlan2->product = 1;
$testPlan2->branch = 0;

$testPlan3 = new stdClass();
$testPlan3->id = 3;
$testPlan3->status = 'done';
$testPlan3->parent = 0;
$testPlan3->product = 1;
$testPlan3->branch = 0;

$testPlan4 = new stdClass();
$testPlan4->id = 4;
$testPlan4->status = 'closed';
$testPlan4->parent = 0;
$testPlan4->product = 1;
$testPlan4->branch = 0;

$testPlan5 = new stdClass();
$testPlan5->id = 5;
$testPlan5->status = 'wait';
$testPlan5->parent = -1;
$testPlan5->product = 1;
$testPlan5->branch = 0;

r(gettype($productplanTest->buildViewActionsTest($testPlan1))) && p() && e('array');
r(gettype($productplanTest->buildViewActionsTest($testPlan2))) && p() && e('array');
r(gettype($productplanTest->buildViewActionsTest($testPlan3))) && p() && e('array');
r(gettype($productplanTest->buildViewActionsTest($testPlan4))) && p() && e('array');
r(gettype($productplanTest->buildViewActionsTest($testPlan5))) && p() && e('array');