#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);
su('admin');

/**

title=测试 codescanModel->getScanPlans();
timeout=0
cid=0

- 返回数组或对象 @1
- 带page参数返回数组或对象 @1
- 带limit参数返回数组或对象 @1
- 带分页参数返回数组或对象 @1
- 重复调用返回数组或对象 @1

*/

$test = new codescanModelTest();

$result = null;
r(is_array($result = $test->getScanPlansTest(1, array())) || is_object($result)) && p() && e('1');
r(is_array($result = $test->getScanPlansTest(2, array('page' => 1))) || is_object($result)) && p() && e('1');
r(is_array($result = $test->getScanPlansTest(3, array('limit' => 10))) || is_object($result)) && p() && e('1');
r(is_array($result = $test->getScanPlansTest(4, array('page' => 1, 'limit' => 5))) || is_object($result)) && p() && e('1');
r(is_array($result = $test->getScanPlansTest(1, array())) || is_object($result)) && p() && e('1');
