#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::responseAfterRunCase();
timeout=0
cid=0

- 执行testtaskTest模块的responseAfterRunCaseTest方法，参数是'fail', $preAndNext1, $run1, 1, 1  @error:
- 执行testtaskTest模块的responseAfterRunCaseTest方法，参数是'pass', $preAndNext2, $run2, 2, 1  @success
- 执行testtaskTest模块的responseAfterRunCaseTest方法，参数是'pass', $preAndNext3, $run3, 3, 1  @success
- 执行testtaskTest模块的responseAfterRunCaseTest方法，参数是'blocked', $preAndNext4, $run4, 5, 1  @success
- 执行testtaskTest模块的responseAfterRunCaseTest方法，参数是'pass', $preAndNext5, $run5, 6, 1  @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

su('admin');

$testtaskTest = new testtaskZenTest();

$run1 = new stdclass();
$run1->id = 1;
$run1->task = 1;

$preAndNext1 = new stdclass();
$preAndNext1->pre = null;
$preAndNext1->next = null;

r($testtaskTest->responseAfterRunCaseTest('fail', $preAndNext1, $run1, 1, 1)) && p() && e('error: ');

$run2 = new stdclass();
$run2->id = 2;
$run2->task = 2;

$preAndNext2 = new stdclass();
$preAndNext2->pre = null;
$preAndNext2->next = null;

r($testtaskTest->responseAfterRunCaseTest('pass', $preAndNext2, $run2, 2, 1)) && p() && e('success');

$run3 = new stdclass();
$run3->id = 3;
$run3->task = 3;

$nextCase = new stdclass();
$nextCase->id = 4;
$nextCase->case = 4;
$nextCase->version = 1;

$preAndNext3 = new stdclass();
$preAndNext3->pre = null;
$preAndNext3->next = $nextCase;

r($testtaskTest->responseAfterRunCaseTest('pass', $preAndNext3, $run3, 3, 1)) && p() && e('success');

$run4 = new stdclass();
$run4->id = 5;
$run4->task = 4;

$preAndNext4 = new stdclass();
$preAndNext4->pre = null;
$preAndNext4->next = $nextCase;

r($testtaskTest->responseAfterRunCaseTest('blocked', $preAndNext4, $run4, 5, 1)) && p() && e('success');

$run5 = new stdclass();
$run5->id = 6;

$preAndNext5 = new stdclass();
$preAndNext5->pre = null;
$preAndNext5->next = null;

r($testtaskTest->responseAfterRunCaseTest('pass', $preAndNext5, $run5, 6, 1)) && p() && e('success');