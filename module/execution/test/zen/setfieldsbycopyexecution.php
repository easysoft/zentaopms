#!/usr/bin/env php
<?php

/**

title=测试 executionZen::setFieldsByCopyExecution();
timeout=0
cid=16440

- 步骤1：正常复制执行字段
 - 属性project @0
 - 属性type @sprint
 - 属性name @执行1
 - 属性code @exec001
 - 属性team @团队A
 - 属性acl @open
- 步骤2：空copyExecutionID返回原字段属性name @新执行
- 步骤3：不存在的ID产生错误 @0
- 步骤4：空对象字段复制
 - 属性project @1
 - 属性type @sprint
 - 属性name @执行2
 - 属性code @exec002
 - 属性team @团队B
 - 属性acl @open
- 步骤5：覆盖已有字段值
 - 属性name @执行3
 - 属性type @sprint
 - 属性team @团队C

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->id->range('1-10');
$table->project->range('0,1{3},2{3},3{3}');
$table->type->range('sprint{5},stage{3},kanban{2}');
$table->name->range('执行1,执行2,执行3,阶段1,阶段2,阶段3,看板1,看板2,项目1,项目2');
$table->code->range('exec001,exec002,exec003,stage001,stage002,stage003,kanban001,kanban002,proj001,proj002');
$table->team->range('团队A,团队B,团队C,团队D,团队E,团队F,团队G,团队H,团队I,团队J');
$table->acl->range('open{3},private{4},custom{3}');
$table->whitelist->range('[]{5},user1,user2,user3{2},admin{2}');
$table->status->range('wait{3},doing{4},done{2},closed{1}');
$table->openedBy->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$table->deleted->range('0');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($executionTest->setFieldsByCopyExecutionTest((object)array('name' => '新执行', 'code' => ''), 1)) && p('project,type,name,code,team,acl') && e('0,sprint,执行1,exec001,团队A,open'); // 步骤1：正常复制执行字段
r($executionTest->setFieldsByCopyExecutionTest((object)array('name' => '新执行'), 0)) && p('name') && e('新执行'); // 步骤2：空copyExecutionID返回原字段
r($executionTest->setFieldsByCopyExecutionTest((object)array('name' => '新执行'), 999)) && p() && e('0'); // 步骤3：不存在的ID产生错误
r($executionTest->setFieldsByCopyExecutionTest(new stdClass(), 2)) && p('project,type,name,code,team,acl') && e('1,sprint,执行2,exec002,团队B,open'); // 步骤4：空对象字段复制
r($executionTest->setFieldsByCopyExecutionTest((object)array('name' => '旧名称', 'type' => '旧类型'), 3)) && p('name,type,team') && e('执行3,sprint,团队C'); // 步骤5：覆盖已有字段值