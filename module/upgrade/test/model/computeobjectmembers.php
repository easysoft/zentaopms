#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->updateProjectMembers();
cid=1

- 测试获取 项目 11 的成员 @acls:admin;teams:admin,user12

- 测试获取 项目 20 的成员 @acls:user9;teams:user11,user21

- 测试获取 产品 1 的成员 @acls:;teams:
- 测试获取 产品 10 的成员 @acls:;teams:
- 测试获取 执行 101 的成员 @acls:;teams:user12
- 测试获取 执行 110 的成员 @acls:;teams:user21

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('user')->gen(40);
zdTable('usergroup')->gen(20);
zdTable('product')->gen(10);
zdTable('projectproduct')->gen(10);

$project = zdTable('project');
$project->id->range('11-20,101-110');
$project->project->range('0{10},11-20');
$project->type->range('project{10},``{10}');
$project->gen(20);

$team = zdTable('team');
$team->root->range('11-20,101-110');
$team->type->range('project{10},execution{10}');
$team->gen(20);

$acl = zdTable('acl');
$acl->objectID->range('11-20,101-110');
$acl->objectType->range('project{10},sprint{10}');
$acl->gen(20);

$doclib = zdTable('doclib');
$doclib->main->range('1');
$doclib->gen(110);

su('admin');

$upgrade = new upgradeTest();
$upgrade->computeObjectMembersTest(true);

$objectID   = array(11, 20, 1, 10, 101, 110);
$objectType = array('project', 'product', 'execution');

global $tester;

r($upgrade->computeObjectMembersTest(false, $objectID[0], $objectType[0])) && p() && e('acls:admin;teams:admin,user12');  // 测试获取 项目 11 的成员
r($upgrade->computeObjectMembersTest(false, $objectID[1], $objectType[0])) && p() && e('acls:user9;teams:user11,user21'); // 测试获取 项目 20 的成员
r($upgrade->computeObjectMembersTest(false, $objectID[2], $objectType[1])) && p() && e('acls:;teams:');                   // 测试获取 产品 1 的成员
r($upgrade->computeObjectMembersTest(false, $objectID[3], $objectType[1])) && p() && e('acls:;teams:');                   // 测试获取 产品 10 的成员
r($upgrade->computeObjectMembersTest(false, $objectID[4], $objectType[2])) && p() && e('acls:;teams:user12');             // 测试获取 执行 101 的成员
r($upgrade->computeObjectMembersTest(false, $objectID[5], $objectType[2])) && p() && e('acls:;teams:user21');             // 测试获取 执行 110 的成员
