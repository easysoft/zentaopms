#!/usr/bin/env php
<?php

/**

title=测试 programplanTao->insertStage();
cid=0

- 测试值传入plan数据
 - 属性id @3
 - 属性name @Test Stage
 - 属性begin @2023-12-28
 - 属性end @2024-03-28
 - 属性parent @0
 - 属性path @,3,
 - 属性acl @private
- 测试创建普通阶段。
 - 属性id @4
 - 属性project @1
 - 属性name @Test Stage
 - 属性begin @2023-12-28
 - 属性end @2024-03-28
 - 属性parent @1
 - 属性path @,1,4,
 - 属性grade @1
 - 属性acl @private
- 测试创建子阶段。
 - 属性id @5
 - 属性project @1
 - 属性name @Test Stage
 - 属性begin @2023-12-28
 - 属性end @2024-03-28
 - 属性parent @4
 - 属性path @,1,4,5,
 - 属性grade @2
 - 属性acl @private

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$project = zdTable('project');
$project->type->range('project');
$project->gen(2);
zdTable('projectspec')->gen(0);
zdTable('product')->gen(2);
zdTable('projectproduct')->gen(2);

global $tester;
$tester->loadModel('programplan');

$plan = new stdclass();
$plan->type      = 'stage';
$plan->parent    = '0';
$plan->name      = 'Test Stage';
$plan->PM        = 'admin';
$plan->begin     = '2023-12-28';
$plan->end       = '2024-03-28';
$plan->acl       = 'private';
$plan->milestone = '0';

$stageID = $tester->programplan->insertStage($plan, 0, 0, 0);
r((array)$tester->programplan->getByID($stageID)) && p('id;name;begin;end;parent;path;acl', ';') && e('3;Test Stage;2023-12-28;2024-03-28;0;,3,;private');   // 测试值传入plan数据

$plan->project = 1;
$plan->parent  = 1;
$stageID = $tester->programplan->insertStage($plan, 1, 1, 0);
r((array)$tester->programplan->getByID($stageID)) && p('id;project;name;begin;end;parent;path;grade;acl', ';') && e('4;1;Test Stage;2023-12-28;2024-03-28;1;,1,4,;1;private');   // 测试创建普通阶段。

$plan->parent = 4;
$stageID = $tester->programplan->insertStage($plan, 1, 1, 4);
r((array)$tester->programplan->getByID($stageID)) && p('id;project;name;begin;end;parent;path;grade;acl', ';') && e('5;1;Test Stage;2023-12-28;2024-03-28;4;,1,4,5,;2;private');   // 测试创建子阶段。
