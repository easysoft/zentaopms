#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';
su('admin');

zdTable('projectadmin')->gen(50);

/**

title=测试 groupModel->getAdmins();
timeout=0
cid=1

- 测试不传入任何数据 @0
- 测试获取 programID = 1 的管理员数 @18
- 测试获取 programID = 2 的管理员数 @17
- 测试获取 programID = 5 的管理员数 @17
- 测试获取 projectID = 6  的管理员数 @10
- 测试获取 projectID = 8  的管理员数 @10
- 测试获取 projectID = 10 的管理员数 @10
- 测试获取 projectID = 15 的管理员数 @9
- 测试获取 productID = 1   的管理员数 @6
- 测试获取 productID = 3   的管理员数 @6
- 测试获取 productID = 10  的管理员数 @5
- 测试获取 productID = 20  的管理员数 @5
- 测试获取 executionID = 16  的管理员数 @8
- 测试获取 executionID = 20  的管理员数 @7
- 测试获取 executionID = 30  的管理员数 @7
- 测试获取 executionID = 24  的管理员数 @7
- 测试获取 executionID = 25  的管理员数 @7

*/
$programIdList   = array(1, 2, 5);
$projectIdList   = array(6, 8, 10, 15);
$productIdList   = array(1, 3, 10, 20);
$executionIdList = array(16, 20, 30, 24, 25);

$group = new groupTest();

r($group->getAdminsTest(array())) && p() && e('0');       //测试不传入任何数据
$programAdmins = $group->getAdminsTest($programIdList, 'programs');
r(count($programAdmins[1]))     && p() && e('18');       //测试获取 programID = 1 的管理员数
r(count($programAdmins[2]))     && p() && e('17');       //测试获取 programID = 2 的管理员数
r(count($programAdmins[5]))     && p() && e('17');       //测试获取 programID = 5 的管理员数

$projectAdmins = $group->getAdminsTest($projectIdList, 'projects');
r(count($projectAdmins[6]))      && p() && e('10');       //测试获取 projectID = 6  的管理员数
r(count($projectAdmins[8]))      && p() && e('10');       //测试获取 projectID = 8  的管理员数
r(count($projectAdmins[10]))     && p() && e('10');       //测试获取 projectID = 10 的管理员数
r(count($projectAdmins[15]))     && p() && e('9');        //测试获取 projectID = 15 的管理员数

$productAdmins = $group->getAdminsTest($productIdList, 'products');
r(count($productAdmins[1]))      && p() && e('6');       //测试获取 productID = 1   的管理员数
r(count($productAdmins[3]))      && p() && e('6');       //测试获取 productID = 3   的管理员数
r(count($productAdmins[10]))     && p() && e('5');       //测试获取 productID = 10  的管理员数
r(count($productAdmins[20]))     && p() && e('5');       //测试获取 productID = 20  的管理员数

$executionAdmins = $group->getAdminsTest($executionIdList, 'executions');
r(count($executionAdmins[16]))     && p() && e('8');       //测试获取 executionID = 16  的管理员数
r(count($executionAdmins[20]))     && p() && e('7');       //测试获取 executionID = 20  的管理员数
r(count($executionAdmins[30]))     && p() && e('7');       //测试获取 executionID = 30  的管理员数
r(count($executionAdmins[24]))     && p() && e('7');       //测试获取 executionID = 24  的管理员数
r(count($executionAdmins[25]))     && p() && e('7');       //测试获取 executionID = 25  的管理员数