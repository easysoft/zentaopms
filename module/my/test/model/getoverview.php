#!/usr/bin/env php
<?php

/**

title=测试 myModel::getOverview();
timeout=0
cid=17285

- 步骤1：管理员用户获取项目总览数据
 - 属性projectTotal @4
 - 属性allConsumed @33
 - 属性thisYearConsumed @1
- 步骤2：验证管理员权限下的项目数量统计属性projectTotal @4
- 步骤3：验证管理员权限下的项目总工时统计属性allConsumed @33
- 步骤4：验证管理员权限下的本年度工时统计属性thisYearConsumed @1
- 步骤5：普通用户获取个人工作数据
 - 属性myTaskTotal @0
 - 属性myStoryTotal @0
 - 属性myBugTotal @0
- 步骤6：验证普通用户的任务统计数据属性myTaskTotal @0
- 步骤7：验证普通用户的需求统计数据属性myStoryTotal @0
- 步骤8：验证普通用户的Bug统计数据属性myBugTotal @0
- 步骤9：验证非管理员组用户权限验证 @1
- 步骤10：验证返回对象的数据完整性 @1

*/

declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

// zendata数据准备 - 创建完整的测试数据环境
zenData('project')->loadYaml('program')->gen(20);
zenData('usergroup')->gen(10);
zenData('group')->gen(10);
zenData('user')->gen(10);
zenData('bug')->gen(20);
zenData('task')->gen(20);
zenData('story')->gen(20);
zenData('effort')->gen(10);

// 创建管理员用户组数据
$adminGroup = zenData('group');
$adminGroup->id->range('1');
$adminGroup->name->range('admin');
$adminGroup->role->range('admin');
$adminGroup->gen(1);

// 创建普通用户组数据
$userGroup = zenData('group');
$userGroup->id->range('2');
$userGroup->name->range('user');
$userGroup->role->range('user');
$userGroup->gen(1);

// 创建用户组关联数据
$userGroupRelation = zenData('usergroup');
$userGroupRelation->account->range('admin,user1{9}');
$userGroupRelation->group->range('1,2{9}');
$userGroupRelation->gen(10);

// 测试准备
global $tester;
$tester->loadModel('program')->refreshStats(true);
$my = new myTest();

// 步骤1-4：管理员用户测试
su('admin');
r($my->getOverviewTest()) && p('projectTotal,allConsumed,thisYearConsumed') && e('4,33,1'); // 步骤1：管理员用户获取项目总览数据
r($my->getOverviewTest()) && p('projectTotal') && e('4'); // 步骤2：验证管理员权限下的项目数量统计
r($my->getOverviewTest()) && p('allConsumed') && e('33'); // 步骤3：验证管理员权限下的项目总工时统计
r($my->getOverviewTest()) && p('thisYearConsumed') && e('1'); // 步骤4：验证管理员权限下的本年度工时统计

// 步骤5-8：普通用户测试
su('user1');
r($my->getOverviewTest()) && p('myTaskTotal,myStoryTotal,myBugTotal') && e('0,0,0'); // 步骤5：普通用户获取个人工作数据
r($my->getOverviewTest()) && p('myTaskTotal') && e('0'); // 步骤6：验证普通用户的任务统计数据
r($my->getOverviewTest()) && p('myStoryTotal') && e('0'); // 步骤7：验证普通用户的需求统计数据
r($my->getOverviewTest()) && p('myBugTotal') && e('0'); // 步骤8：验证普通用户的Bug统计数据

// 步骤9-10：边界条件和数据完整性测试
su('user2'); // 切换到另一个普通用户
$result = $my->getOverviewTest();
r(isset($result->myTaskTotal) && isset($result->myStoryTotal) && isset($result->myBugTotal)) && p() && e('1'); // 步骤9：验证非管理员组用户权限验证
r(!isset($result->projectTotal) && !isset($result->allConsumed) && !isset($result->thisYearConsumed)) && p() && e('1'); // 步骤10：验证返回对象的数据完整性