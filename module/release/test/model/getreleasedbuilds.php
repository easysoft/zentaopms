#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 releaseModel->getReleasedBuilds();
timeout=0
cid=17999

- branch为'all'时数据查询查询属性1 @0
- branch为'0'时数据查询查询属性1 @0
- branch为'1'时数据查询查询 @1
- branch为'2'时数据查询查询 @2
- branch为''时数据查询查询属性1 @0
- 产品ID为空查询 @0
- 产品ID不存在查询 @0

*/

$release = zenData('release')->loadYaml('release');
$release->build->range('1-5');
$release->branch->range('1-5');
$release->gen(5);

zenData('product')->loadYaml('product')->gen(6);
zenData('branch')->loadYaml('branch')->gen(5);
zenData('user')->gen(5);
su('admin');

$products = array(0, 1, 10);
$branches = array('all', '0', '1', '2', '');

global $tester;
$tester->loadModel('release');
r($tester->release->getReleasedBuilds($products[1], $branches[0])) && p('1') && e('0'); // branch为'all'时数据查询查询
r($tester->release->getReleasedBuilds($products[1], $branches[1])) && p('1') && e('0'); // branch为'0'时数据查询查询
r($tester->release->getReleasedBuilds($products[1], $branches[2])) && p('0') && e('1'); // branch为'1'时数据查询查询
r($tester->release->getReleasedBuilds($products[1], $branches[3])) && p('0') && e('2'); // branch为'2'时数据查询查询
r($tester->release->getReleasedBuilds($products[1], $branches[4])) && p('1') && e('0'); // branch为''时数据查询查询
r($tester->release->getReleasedBuilds($products[0], $branches[0])) && p()    && e('0'); // 产品ID为空查询
r($tester->release->getReleasedBuilds($products[2], $branches[0])) && p()    && e('0'); // 产品ID不存在查询