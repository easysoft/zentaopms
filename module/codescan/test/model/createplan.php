#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);
su('admin');

/**

title=测试 codescanModel->createPlan();
timeout=0
cid=0

- 测试带名称的对象 @0
- 测试空名称和零仓库ID @0
- 测试负仓库ID @0
- 测试带repoID对象返回0 @0
- 测试不同对象参数 @0

*/

$test = new codescanModelTest();

$data1 = (object)array('name' => 'test1', 'repoID' => 1);
$data2 = (object)array('name' => '',      'repoID' => 0);
$data3 = (object)array('name' => 'test3', 'repoID' => -1);
$data4 = (object)array('name' => 'test2', 'repoID' => 2);
$data5 = (object)array('name' => 'test5', 'repoID' => 5);

r($test->createPlanTest($data1)) && p() && e('0');
r($test->createPlanTest($data2)) && p() && e('0');
r($test->createPlanTest($data3)) && p() && e('0');
r($test->createPlanTest($data4)) && p() && e('0');
r($test->createPlanTest($data5)) && p() && e('0');
