#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);
su('admin');

/**

title=测试 codescanModel->getScanPlan();
timeout=0
cid=0

- 计划ID和代码库ID都有值时返回数组或对象 @1
- 第二组计划ID和代码库ID返回数组或对象 @1
- 默认参数返回数组或对象 @1
- 只传计划ID返回数组或对象 @1
- 只传代码库ID返回数组或对象 @1

*/

$test = new codescanModelTest();

r(is_array($result = $test->getScanPlanTest(1, 1)) || is_object($result)) && p() && e('1');
r(is_array($result = $test->getScanPlanTest(2, 2)) || is_object($result)) && p() && e('1');
r(is_array($result = $test->getScanPlanTest(0, 0)) || is_object($result)) && p() && e('1');
r(is_array($result = $test->getScanPlanTest(1, 0)) || is_object($result)) && p() && e('1');
r(is_array($result = $test->getScanPlanTest(0, 1)) || is_object($result)) && p() && e('1');
