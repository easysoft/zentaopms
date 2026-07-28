#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);
su('admin');

/**

title=测试 codescanModel->getScanTask();
timeout=0
cid=0

- 测试ID为1的调用 >> 0
- 测试任务2详情返回0 >> 2,0,none
- 测试ID为0的调用 >> 0
- 测试任务3详情返回0 >> 3,0,none
- 测试ID为2的调用 >> 0

*/

$test = new codescanModelTest();

r($test->getscantaskTest(1)) && p() && e('0');
r($test->getscantaskTest(2)) && p() && e('0');
r($test->getscantaskTest(0)) && p() && e('0');
r($test->getscantaskTest(3)) && p() && e('0');
r($test->getscantaskTest(4)) && p() && e('0');
