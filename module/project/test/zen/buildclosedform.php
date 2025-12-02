#!/usr/bin/env php
<?php

/**

title=测试 projectZen::buildClosedForm();
timeout=0
cid=17926

- 步骤1：有效项目ID且项目是非多迭代项目
 - 属性title @关闭项目
 - 属性users @5
- 步骤2：有效项目ID且项目是多迭代项目
 - 属性title @关闭项目
 - 属性project @4
- 步骤3：无效项目ID(0)属性error @Invalid project ID
- 步骤4：不存在的项目ID(999)属性error @Project not found
- 步骤5：负数项目ID(-1)属性error @Invalid project ID

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$table->type->range('project');
$table->status->range('doing');
$table->multiple->range('0,1');
$table->gen(10);

$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4');
$userTable->gen(5);

$actionTable = zenData('action');
$actionTable->id->range('1-3');
$actionTable->objectType->range('project');
$actionTable->objectID->range('1-3');
$actionTable->action->range('opened,started,suspended');
$actionTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectzenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($projectTest->buildClosedFormTest(1)) && p('title,users') && e('关闭项目,5'); // 步骤1：有效项目ID且项目是非多迭代项目
r($projectTest->buildClosedFormTest(4)) && p('title,project') && e('关闭项目,4'); // 步骤2：有效项目ID且项目是多迭代项目
r($projectTest->buildClosedFormTest(0)) && p('error') && e('Invalid project ID'); // 步骤3：无效项目ID(0)
r($projectTest->buildClosedFormTest(999)) && p('error') && e('Project not found'); // 步骤4：不存在的项目ID(999)
r($projectTest->buildClosedFormTest(-1)) && p('error') && e('Invalid project ID'); // 步骤5：负数项目ID(-1)