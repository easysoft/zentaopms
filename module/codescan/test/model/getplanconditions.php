#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->getPlanConditions();
timeout=0
cid=0

- 测试两个参数都有值 >> 0
- 测试repo2 plan2条件返回0 >> 2,2,0,none
- 测试默认参数 >> 0
- 测试repo1空plan条件返回0 >> 1,0,0,none
- 测试不同参数组合 >> 0

*/

$test = new codescanModelTest();

r($test->getplanconditionsTest(1, 1)) && p() && e('0');
r($test->getplanconditionsTest(2, 2)) && p() && e('0');
r($test->getplanconditionsTest(0, 0)) && p() && e('0');
r($test->getplanconditionsTest(1, 0)) && p() && e('0');
r($test->getplanconditionsTest(0, 1)) && p() && e('0');
