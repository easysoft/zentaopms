#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->getScanTasks();
timeout=0
cid=0

- 测试完整参数调用 >> 0
- 测试repo2 plan2空参数返回0 >> 2,2,empty,0,0
- 测试默认参数 >> 0
- 测试repo1 plan2多参数返回0 >> 1,2,1|2,2,0
- 测试不同参数 >> 0

*/

$test = new codescanModelTest();

r($test->getscantasksTest(1, 1, array(1, 2, 3))) && p() && e('0');
r($test->getscantasksTest(2, 2, array())) && p() && e('0');
r($test->getscantasksTest(0, 0, array(1))) && p() && e('0');
r($test->getscantasksTest(1, 2, array(1, 2))) && p() && e('0');
r($test->getscantasksTest(2, 1, array(1, 2, 3, 4))) && p() && e('0');
