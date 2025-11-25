#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::buildActionsList();
timeout=0
cid=17659

- 执行$result @11
- 执行$result @1
- 执行$result @1
- 执行$result @1
- 执行$result @1
- 执行$result @1
- 执行$result @1
- 执行$result @1
- 执行$result @1
- 执行$result @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplanzen.unittest.class.php';

su('admin');

$productplanTest = new productplanZenTest();

// 构造计划对象用于测试
$plan = new stdClass();
$plan->id = 1;
$plan->status = 'wait';
$plan->product = 1;
$plan->branch = '0';

$result = $productplanTest->buildActionsListTest($plan);

r(count($result)) && p() && e('11');
r(in_array('start', $result)) && p() && e('1');
r(in_array('finish', $result)) && p() && e('1');
r(in_array('close', $result)) && p() && e('1');
r(in_array('activate', $result)) && p() && e('1');
r(in_array('createExecution', $result)) && p() && e('1');
r(in_array('linkStory', $result)) && p() && e('1');
r(in_array('linkBug', $result)) && p() && e('1');
r(in_array('edit', $result)) && p() && e('1');
r(in_array('delete', $result)) && p() && e('1');