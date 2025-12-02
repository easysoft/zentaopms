#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::show();
timeout=0
cid=17466

- 执行$result @1
- 执行$result['pivot'] @1
- 执行$result['pivotName'] @1
- 执行$result['title'] @1
- 执行$result['data'] @1
- 执行$result['configs'] @1
- 执行$result['error'] @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

su('admin');

$pivotTest = new pivotZenTest();

// 测试步骤1:检查返回结果是数组类型
$result = $pivotTest->showTest(1, 1);
r(is_array($result)) && p() && e('1');

// 测试步骤2:检查返回结果包含pivot键
$result = $pivotTest->showTest(1, 1);
r(isset($result['pivot'])) && p() && e('1');

// 测试步骤3:检查返回结果包含pivotName键
$result = $pivotTest->showTest(1, 2);
r(isset($result['pivotName'])) && p() && e('1');

// 测试步骤4:检查返回结果包含title键
$result = $pivotTest->showTest(1, 3);
r(isset($result['title'])) && p() && e('1');

// 测试步骤5:检查返回结果包含data键
$result = $pivotTest->showTest(1, 4);
r(isset($result['data'])) && p() && e('1');

// 测试步骤6:检查返回结果包含configs键
$result = $pivotTest->showTest(1, 5);
r(isset($result['configs'])) && p() && e('1');

// 测试步骤7:检查返回结果不包含error键
$result = $pivotTest->showTest(1, 1, '', '1');
r(!isset($result['error'])) && p() && e('1');