#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkProjectPriv();
timeout=0
cid=18321

- 执行searchTest模块的checkProjectPrivTest方法，参数是$results, $objectIdList  @3
- 执行searchTest模块的checkProjectPrivTest方法，参数是$results, $objectIdList  @2
- 执行searchTest模块的checkProjectPrivTest方法，参数是$results, $objectIdList  @0
- 执行searchTest模块的checkProjectPrivTest方法，参数是$results, $objectIdList  @0
- 执行searchTest模块的checkProjectPrivTest方法，参数是$results, $objectIdList  @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

global $tester, $app;
su('admin');

$searchTest = new searchTaoTest();

// 准备测试数据
$result1 = new stdclass();
$result1->id = 1;
$result1->objectType = 'project';
$result1->objectID = 101;

$result2 = new stdclass();
$result2->id = 2;
$result2->objectType = 'project';
$result2->objectID = 102;

$result3 = new stdclass();
$result3->id = 3;
$result3->objectType = 'project';
$result3->objectID = 103;

// 测试步骤1: 用户有权限访问所有项目
$results = array(1 => $result1, 2 => $result2, 3 => $result3);
$objectIdList = array(101 => 1, 102 => 2, 103 => 3);
$app->user->view = new stdclass();
$app->user->view->projects = '101,102,103';
r(count($searchTest->checkProjectPrivTest($results, $objectIdList))) && p() && e('3');

// 测试步骤2: 用户有权限访问部分项目(项目1和2)
$results = array(1 => $result1, 2 => $result2, 3 => $result3);
$objectIdList = array(101 => 1, 102 => 2, 103 => 3);
$app->user->view->projects = '101,102';
r(count($searchTest->checkProjectPrivTest($results, $objectIdList))) && p() && e('2');

// 测试步骤3: 用户没有任何项目权限
$results = array(1 => $result1, 2 => $result2, 3 => $result3);
$objectIdList = array(101 => 1, 102 => 2, 103 => 3);
$app->user->view->projects = '';
r(count($searchTest->checkProjectPrivTest($results, $objectIdList))) && p() && e('0');

// 测试步骤4: 空的结果数组
$results = array();
$objectIdList = array(101 => 1, 102 => 2);
$app->user->view->projects = '101,102';
r(count($searchTest->checkProjectPrivTest($results, $objectIdList))) && p() && e('0');

// 测试步骤5: 空的对象ID列表
$results = array(1 => $result1, 2 => $result2);
$objectIdList = array();
$app->user->view->projects = '101,102';
r(count($searchTest->checkProjectPrivTest($results, $objectIdList))) && p() && e('2');