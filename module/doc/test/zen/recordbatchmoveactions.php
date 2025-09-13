#!/usr/bin/env php
<?php

/**

title=测试 docZen::recordBatchMoveActions();
timeout=0
cid=0

- 步骤1：正常情况-批量移动2个文档 @2
- 步骤2：边界值-空文档列表 @0
- 步骤3：单个文档移动 @1
- 步骤4：多个不同lib的文档移动 @2
- 步骤5：文档从lib1移动到lib2 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$docTable = zenData('doc');
$docTable->id->range('1-5');
$docTable->lib->range('1{2},2{2},3{1}');
$docTable->title->range('测试文档1,测试文档2,测试文档3,测试文档4,测试文档5');
$docTable->type->range('text');
$docTable->status->range('normal');
$docTable->addedBy->range('admin');
$docTable->addedDate->range('`2024-01-01 10:00:00`');
$docTable->gen(5);

$doclibTable = zenData('doclib');
$doclibTable->id->range('1-3');
$doclibTable->type->range('custom');
$doclibTable->name->range('测试库1,测试库2,测试库3');
$doclibTable->acl->range('open');
$doclibTable->gen(3);

$actionTable = zenData('action');
$actionTable->id->range('1-100');
$actionTable->gen(0); // 先清空action表

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 准备测试数据
$oldDoc1 = new stdclass();
$oldDoc1->id = 1;
$oldDoc1->lib = 1;

$oldDoc2 = new stdclass(); 
$oldDoc2->id = 2;
$oldDoc2->lib = 1;

$oldDoc3 = new stdclass();
$oldDoc3->id = 3;
$oldDoc3->lib = 2;

$data = new stdclass();
$data->lib = 2;

r($docTest->recordBatchMoveActionsTest(array($oldDoc1, $oldDoc2), $data)) && p() && e('2'); // 步骤1：正常情况-批量移动2个文档
r($docTest->recordBatchMoveActionsTest(array(), $data)) && p() && e('0'); // 步骤2：边界值-空文档列表
r($docTest->recordBatchMoveActionsTest(array($oldDoc1), $data)) && p() && e('1'); // 步骤3：单个文档移动
r($docTest->recordBatchMoveActionsTest(array($oldDoc1, $oldDoc3), $data)) && p() && e('2'); // 步骤4：多个不同lib的文档移动
r($docTest->recordBatchMoveActionsTest(array($oldDoc2), $data)) && p() && e('1'); // 步骤5：文档从lib1移动到lib2