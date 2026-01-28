#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printProjectDocBlock();
timeout=0
cid=15274

- 步骤1：正常情况属性success @1
- 步骤2：参与项目类型属性type @involved
- 步骤3：所有项目类型属性type @all
- 步骤4：限制数量属性success @1
- 步骤5：空参数默认情况属性type @involved

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 简化数据准备
$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5');
$projectTable->type->range('project');
$projectTable->status->range('doing');
$projectTable->deleted->range('0');
$projectTable->vision->range('rnd');
$projectTable->gen(5);

$docTable = zenData('doc');
$docTable->id->range('1-10');
$docTable->project->range('1{2},2{2},3{2},4{2},5{2}');
$docTable->title->range('文档1,文档2,文档3,文档4,文档5,文档6,文档7,文档8,文档9,文档10');
$docTable->deleted->range('0');
$docTable->vision->range('rnd');
$docTable->gen(10);

$teamTable = zenData('team');
$teamTable->root->range('1{2},2{2},3{2}');
$teamTable->account->range('admin,user1');
$teamTable->type->range('project');
$teamTable->gen(6);

$userTable = zenData('user');
$userTable->account->range('admin,user1,user2');
$userTable->realname->range('管理员,用户1,用户2');
$userTable->deleted->range('0');
$userTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($blockTest->printProjectDocBlockTest())                                  && p('success') && e('1');        // 步骤1：正常情况
r($blockTest->printProjectDocBlockTest(null, array('type' => 'involved'))) && p('type')    && e('involved'); // 步骤2：参与项目类型
r($blockTest->printProjectDocBlockTest(null, array('type' => 'all')))      && p('type')    && e('all');      // 步骤3：所有项目类型

$block = new stdclass();
$block->params = new stdclass();
$block->params->count = 5;
r($blockTest->printProjectDocBlockTest($block)) && p('success') && e('1'); // 步骤4：限制数量

r($blockTest->printProjectDocBlockTest(null, array())) && p('type') && e('involved'); // 步骤5：空参数默认情况