#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->createPlan();
timeout=0
cid=0

- 测试带名称的对象 >> 0
- 测试空对象返回0 >> empty,empty,0,none
- 测试空对象 >> 0
- 测试带repoID对象返回0 >> test2,2,0,none
- 测试不同对象参数 >> 0

*/

$test = new codescanModelTest();

$data1 = new stdclass(); $data1->name = 'test1'; $data1->repoID = 1;
r($test->createplanTest($data1)) && p() && e('0');
r($test->createplanTest(new stdclass())) && p() && e('0');
r($test->createplanTest(new stdclass())) && p() && e('0');
$data2 = new stdclass(); $data2->name = 'test2'; $data2->repoID = 2;
r($test->createplanTest($data2)) && p() && e('0');
r($test->createplanTest(new stdclass())) && p() && e('0');
