#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->getPairs();
cid=0

- 测试获取执行11 产品11的计划键值对 @;/项目11
- 测试获取执行12 产品12的计划键值对 @;/项目12

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programplan.unittest.class.php';
su('admin');

zenData('project')->loadYaml('getpairs')->gen(10);
zenData('projectproduct')->loadYaml('getprojectproduct')->gen(20);

$executionIDList = array(11, 12, 13, 14, 15);
$productIDList   = array(11, 12, 13, 14, 15);

$programplan = new programplanTest();

r($programplan->getPairsTest($executionIDList[0], $productIDList[0]))         && p() && e(';/项目11'); // 测试获取执行11 产品11的计划键值对
r($programplan->getPairsTest($executionIDList[1], $productIDList[1], 'leaf')) && p() && e(';/项目12'); // 测试获取执行12 产品12的计划键值对
