#!/usr/bin/env php
<?php
/**

title=测试 buildModel->getByList();
timeout=0
cid=15492

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('build')->loadYaml('build')->gen(10);
zenData('user')->gen(5);
su('admin');


$buildIdList[0] = array();
$buildIdList[1] = range(1, 10);
$buildIdList[2] = range(11, 20);

$count = array(0, 1);

$buildTester = new buildModelTest();
r($buildTester->getByListTest($buildIdList[0], $count[0])) && p()         && e('0');     // 测试空数组
r($buildTester->getByListTest($buildIdList[1], $count[0])) && p('1:name') && e('版本1'); // 测试传入版本Id列表获取版本信息
r($buildTester->getByListTest($buildIdList[2], $count[0])) && p()         && e('0');     // 测试传入不存在的版本Id列表获取版本信息
r($buildTester->getByListTest($buildIdList[0], $count[1])) && p()         && e('0');     // 测试传入空数组获取版本的数量
r($buildTester->getByListTest($buildIdList[1], $count[1])) && p()         && e('10');    // 测试传入版本Id列表获取版本的数量
r($buildTester->getByListTest($buildIdList[2], $count[1])) && p()         && e('0');     // 测试传入不存在的版本Id列表获取版本的数量
