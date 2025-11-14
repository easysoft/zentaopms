#!/usr/bin/env php
<?php

/**

title=测试 metricTao::getObjectsWithPager();
timeout=0
cid=17175

- 测试scope为system时返回false @0
- 测试scope为product时返回产品ID列表 @0
- 测试scope为project时返回项目ID列表 @0
- 测试scope为execution时返回执行ID列表 @0
- 测试scope为user时返回用户账号列表 @0
- 测试scope为repo时返回代码库ID列表 @0
- 测试不同参数下scope为repo时返回代码库ID列表 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('metric')->loadYaml('getobjectswithpager/metric', false, 2)->gen(10);
zenData('metriclib')->loadYaml('getobjectswithpager/metriclib', false, 2)->gen(30);
zenData('product')->loadYaml('getobjectswithpager/product', false, 2)->gen(10);
zenData('user')->loadYaml('getobjectswithpager/user', false, 2)->gen(10);
zenData('repo')->loadYaml('getobjectswithpager/repo', false, 2)->gen(10);

$projectTable = zenData('project');
$projectTable->id->range('1-20');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5,执行1,执行2,执行3,执行4,执行5,执行6,执行7,执行8,执行9,执行10,执行11,执行12,执行13,执行14,执行15');
$projectTable->type->range('project{5},sprint{10},stage{3},kanban{2}');
$projectTable->status->range('wait,doing,suspended,closed');
$projectTable->deleted->range('0{10},1{10}');
$projectTable->gen(20);

su('admin');

$metricTest = new metricTaoTest();

$systemMetric = new stdClass();
$systemMetric->code = 'test_metric_code_1';
$systemMetric->scope = 'system';
$systemMetric->dateType = 'day';

$productMetric = new stdClass();
$productMetric->code = 'test_metric_code_2';
$productMetric->scope = 'product';
$productMetric->dateType = 'day';

$projectMetric = new stdClass();
$projectMetric->code = 'test_metric_code_3';
$projectMetric->scope = 'project';
$projectMetric->dateType = 'day';

$executionMetric = new stdClass();
$executionMetric->code = 'test_metric_code_4';
$executionMetric->scope = 'execution';
$executionMetric->dateType = 'day';

$userMetric = new stdClass();
$userMetric->code = 'test_metric_code_5';
$userMetric->scope = 'user';
$userMetric->dateType = 'day';

$repoMetric = new stdClass();
$repoMetric->code = 'test_metric_code_9';
$repoMetric->scope = 'repo';
$repoMetric->dateType = 'day';

$query = array('dateType' => 'day');

r($metricTest->getObjectsWithPagerTest($systemMetric, $query)) && p() && e('0');          // 测试scope为system时返回false
r(count($metricTest->getObjectsWithPagerTest($productMetric, $query))) && p() && e('0');  // 测试scope为product时返回产品ID列表
r(count($metricTest->getObjectsWithPagerTest($projectMetric, $query))) && p() && e('0');  // 测试scope为project时返回项目ID列表
r(count($metricTest->getObjectsWithPagerTest($executionMetric, $query))) && p() && e('0'); // 测试scope为execution时返回执行ID列表
r(count($metricTest->getObjectsWithPagerTest($userMetric, $query))) && p() && e('0');     // 测试scope为user时返回用户账号列表
r(count($metricTest->getObjectsWithPagerTest($repoMetric, $query))) && p() && e('0');     // 测试scope为repo时返回代码库ID列表
r(count($metricTest->getObjectsWithPagerTest($repoMetric, $query))) && p() && e('0');     // 测试不同参数下scope为repo时返回代码库ID列表