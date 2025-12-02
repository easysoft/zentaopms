#!/usr/bin/env php
<?php

/**

title=测试 loadModel->buildGroupDataForGantt()
timeout=0
cid=17762

- 检查构建分组Gantt数据。
 - 属性id @1
 - 属性type @group
 - 属性text @开发
- 检查另一个分组。
 - 属性id @2
 - 属性type @group
 - 属性text @测试
- 检查空组构建情况。
 - 属性id @3
 - 属性type @group
 - 属性text @未指派
- 检查自定义组ID。
 - 属性id @99
 - 属性type @group
 - 属性text @产品
- 检查无用户列表。
 - 属性id @5
 - 属性type @group
 - 属性text @设计

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

zenData('user')->gen(10);

global $tester;
$tester->loadModel('programplan');
$tester->programplan->config->setPercent = false;

$users = $tester->programplan->loadModel('user')->getPairs();
$emptyUsers = array();

$groupID = 1;
$group   = '开发';
r((array)$tester->programplan->buildGroupDataForGantt($groupID, $group, $users)) && p('id,type,text') && e("1,group,开发"); //检查构建分组Gantt数据。

$groupID = 2;
$group   = '测试';
r((array)$tester->programplan->buildGroupDataForGantt($groupID, $group, $users)) && p('id,type,text') && e("2,group,测试"); //检查另一个分组。

$groupID = 3;
$group   = '/';
r((array)$tester->programplan->buildGroupDataForGantt($groupID, $group, $users)) && p('id,type,text') && e("3,group,未指派"); //检查空组构建情况。

$groupID = 99;
$group   = '产品';
r((array)$tester->programplan->buildGroupDataForGantt($groupID, $group, $users)) && p('id,type,text') && e("99,group,产品"); //检查自定义组ID。

$groupID = 5;
$group   = '设计';
r((array)$tester->programplan->buildGroupDataForGantt($groupID, $group, $emptyUsers)) && p('id,type,text') && e("5,group,设计"); //检查无用户列表。