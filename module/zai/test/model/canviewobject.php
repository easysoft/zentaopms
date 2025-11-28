#!/usr/bin/env php
<?php

/**

title=测试 zaiModel->canViewObject();
timeout=0
cid=0

- 测试用户可以查看story对象（产品在权限范围内） @1
- 测试用户不能查看story对象（产品不在权限范围内） @0
- 测试用户可以查看bug对象（产品在权限范围内） @1
- 测试用户可以查看bug对象（项目在权限范围内） @1
- 测试用户不能查看bug对象（产品和项目都不在权限范围内） @0
- 测试用户可以查看task对象（项目在权限范围内） @1
- 测试用户不能查看task对象（项目不在权限范围内） @0
- 测试用户可以查看feedback对象（产品在权限范围内） @1
- 测试用户不能查看feedback对象（产品不在权限范围内） @0
- 测试使用attrs参数避免数据库查询（story） @1
- 测试使用attrs参数避免数据库查询（bug） @1
- 测试使用attrs参数避免数据库查询（task） @1
- 测试缓存机制（第二次调用相同对象） @1
- 测试demand对象权限检查 @1
- 测试case对象权限检查 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

zenData('story')->gen(5);
zenData('bug')->gen(5);
zenData('task')->gen(5);
zenData('feedback')->gen(5);
zenData('case')->gen(5);
zenData('user')->gen(1);

su('admin');

global $tester, $app;
$zai = new zaiTest();

// 设置用户视图权限
$app->user->view = new stdClass();
$app->user->view->products = '1,2,3';
$app->user->view->projects = '1,2,3';

/* 测试用户可以查看story对象（产品在权限范围内） */
$result1 = $zai->canViewObjectTest('story', 1, array('product' => 1));
r($result1 ? 1 : 0) && p() && e('1'); // 测试用户可以查看story对象（产品在权限范围内）

/* 测试用户不能查看story对象（产品不在权限范围内） */
$result2 = $zai->canViewObjectTest('story', 2, array('product' => 99));
r($result2 ? 1 : 0) && p() && e('0'); // 测试用户不能查看story对象（产品不在权限范围内）

/* 测试用户可以查看bug对象（产品在权限范围内） */
$result3 = $zai->canViewObjectTest('bug', 1, array('product' => 2));
r($result3 ? 1 : 0) && p() && e('1'); // 测试用户可以查看bug对象（产品在权限范围内）

/* 测试用户可以查看bug对象（项目在权限范围内） */
$result4 = $zai->canViewObjectTest('bug', 2, array('product' => 99, 'project' => 1));
r($result4 ? 1 : 0) && p() && e('1'); // 测试用户可以查看bug对象（项目在权限范围内）

/* 测试用户不能查看bug对象（产品和项目都不在权限范围内） */
$result5 = $zai->canViewObjectTest('bug', 3, array('product' => 99, 'project' => 99));
r($result5 ? 1 : 0) && p() && e('0'); // 测试用户不能查看bug对象（产品和项目都不在权限范围内）

/* 测试用户可以查看task对象（项目在权限范围内） */
$result6 = $zai->canViewObjectTest('task', 1, array('project' => 2));
r($result6 ? 1 : 0) && p() && e('1'); // 测试用户可以查看task对象（项目在权限范围内）

/* 测试用户不能查看task对象（项目不在权限范围内） */
$result7 = $zai->canViewObjectTest('task', 2, array('project' => 99));
r($result7 ? 1 : 0) && p() && e('0'); // 测试用户不能查看task对象（项目不在权限范围内）

/* 测试用户可以查看feedback对象（产品在权限范围内） */
$result8 = $zai->canViewObjectTest('feedback', 1, array('product' => 3));
r($result8 ? 1 : 0) && p() && e('1'); // 测试用户可以查看feedback对象（产品在权限范围内）

/* 测试用户不能查看feedback对象（产品不在权限范围内） */
$result9 = $zai->canViewObjectTest('feedback', 2, array('product' => 99));
r($result9 ? 1 : 0) && p() && e('0'); // 测试用户不能查看feedback对象（产品不在权限范围内）

/* 测试使用attrs参数避免数据库查询（story） */
$result10 = $zai->canViewObjectTest('story', 999, array('product' => 1));
r($result10 ? 1 : 0) && p() && e('1'); // 测试使用attrs参数避免数据库查询（story）

/* 测试使用attrs参数避免数据库查询（bug） */
$result11 = $zai->canViewObjectTest('bug', 999, array('product' => 2));
r($result11 ? 1 : 0) && p() && e('1'); // 测试使用attrs参数避免数据库查询（bug）

/* 测试使用attrs参数避免数据库查询（task） */
$result12 = $zai->canViewObjectTest('task', 999, array('project' => 3));
r($result12 ? 1 : 0) && p() && e('1'); // 测试使用attrs参数避免数据库查询（task）

/* 测试缓存机制（第二次调用相同对象） */
// 第一次调用
$zai->canViewObjectTest('story', 100, array('product' => 1));
// 第二次调用应该使用缓存
$result13 = $zai->canViewObjectTest('story', 100, array('product' => 1));
r($result13 ? 1 : 0) && p() && e('1'); // 测试缓存机制（第二次调用相同对象）

/* 测试demand对象权限检查 */
$result14 = $zai->canViewObjectTest('demand', 1, array('product' => 1));
r($result14 ? 1 : 0) && p() && e('1'); // 测试demand对象权限检查

/* 测试case对象权限检查 */
$result15 = $zai->canViewObjectTest('case', 1, array('product' => 2));
r($result15 ? 1 : 0) && p() && e('1'); // 测试case对象权限检查
