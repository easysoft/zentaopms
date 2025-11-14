#!/usr/bin/env php
<?php

/**

title=测试 groupModel::getObjectForAdminGroup();
timeout=0
cid=16706

- 步骤1：验证项目集属性programs @项目集1|项目1
- 步骤2：验证项目属性projects @迭代1|阶段1|看板1
- 步骤3：验证产品属性products @正常产品1|项目集1/正常产品2|正常产品3
- 步骤4：验证执行属性executions @项目2|项目3|迭代2|阶段2|看板2
- 步骤5：空数据库属性programs @~~
- 步骤6：验证已删除项目不显示属性projects @~~
- 步骤7：混合类型项目验证项目集属性programs @新项目集1
- 步骤8：混合类型项目验证项目属性projects @新项目1
- 步骤9：混合类型项目验证执行属性executions @新迭代1|新阶段1|新看板1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/group.unittest.class.php';

// 测试数据准备：正常项目数据
$projectTable = zenData('project');
$projectTable->id->range('1-10');
$projectTable->name->range('项目集1,项目1,迭代1,阶段1,看板1,项目2,项目3,迭代2,阶段2,看板2');
$projectTable->type->range('program{2},project{3},sprint{2},stage{2},kanban{1}');
$projectTable->path->range(',1,,2,,3,,4,,5,');
$projectTable->grade->range('1{2},1{3},2{5}');
$projectTable->parent->range('0{5},2{2},3{2},4{1}');
$projectTable->project->range('0{5},2{2},3{2},4{1}');
$projectTable->deleted->range('0');
$projectTable->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-3');
$productTable->name->range('正常产品1,正常产品2,正常产品3');
$productTable->program->range('0,1,0');
$productTable->deleted->range('0');
$productTable->gen(3);

su('admin');

$group = new groupTest();

r($group->getObjectForAdminGroupTest()) && p('programs') && e('项目集1|项目1');                   // 步骤1：验证项目集
r($group->getObjectForAdminGroupTest()) && p('projects') && e('迭代1|阶段1|看板1');              // 步骤2：验证项目
r($group->getObjectForAdminGroupTest()) && p('products') && e('正常产品1|项目集1/正常产品2|正常产品3'); // 步骤3：验证产品
r($group->getObjectForAdminGroupTest()) && p('executions') && e('项目2|项目3|迭代2|阶段2|看板2'); // 步骤4：验证执行

// 测试步骤5：清空数据测试
zenData('project')->gen(0);
zenData('product')->gen(0);
r($group->getObjectForAdminGroupTest()) && p('programs') && e('~~');                        // 步骤5：空数据库

// 测试步骤6：已删除项目过滤测试
$deletedProjectTable = zenData('project');
$deletedProjectTable->id->range('11-12');
$deletedProjectTable->name->range('已删除项目1,已删除项目2');
$deletedProjectTable->type->range('project');
$deletedProjectTable->deleted->range('1');
$deletedProjectTable->gen(2);

r($group->getObjectForAdminGroupTest()) && p('projects') && e('~~');                        // 步骤6：验证已删除项目不显示

// 测试步骤7：混合类型项目测试
$mixedProjectTable = zenData('project');
$mixedProjectTable->id->range('13-17');
$mixedProjectTable->name->range('新项目集1,新项目1,新迭代1,新阶段1,新看板1');
$mixedProjectTable->type->range('program,project,sprint,stage,kanban');
$mixedProjectTable->path->range(',13,,14,,14,,14,,14,');
$mixedProjectTable->grade->range('1,1,2,2,2');
$mixedProjectTable->parent->range('0,0,14,14,14');
$mixedProjectTable->project->range('0,0,14,14,14');
$mixedProjectTable->deleted->range('0');
$mixedProjectTable->gen(5);

r($group->getObjectForAdminGroupTest()) && p('programs') && e('新项目集1');                  // 步骤7：混合类型项目验证项目集
r($group->getObjectForAdminGroupTest()) && p('projects') && e('新项目1');                   // 步骤8：混合类型项目验证项目
r($group->getObjectForAdminGroupTest()) && p('executions') && e('新迭代1|新阶段1|新看板1');   // 步骤9：混合类型项目验证执行