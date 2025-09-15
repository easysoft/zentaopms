#!/usr/bin/env php
<?php

/**

title=测试 docModel::getDocIdByTitle();
timeout=0
cid=0

- 步骤1：正常情况 - 找到匹配的文档 @1
- 步骤2：正常情况 - 找到第二个匹配的文档 @2
- 步骤3：异常输入 - 标题不存在 @0
- 步骤4：异常输入 - originPageID不存在 @0
- 步骤5：边界值 - 空标题 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$docTable = zenData('doc');
$docTable->id->range('1-10');
$docTable->lib->range('1-3');
$docTable->title->range('测试文档1,测试文档2,用户手册,开发指南,API文档{3}');
$docTable->status->range('normal{8},draft{2}');
$docTable->deleted->range('0{9},1');
$docTable->gen(10);

$docLibTable = zenData('doclib');
$docLibTable->id->range('1-3');
$docLibTable->name->range('测试库1,测试库2,开发文档库');
$docLibTable->type->range('custom,product,project');
$docLibTable->deleted->range('0{3}');
$docLibTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->getDocIdByTitleTest(1001, '测试文档1')) && p() && e('1'); // 步骤1：正常情况 - 找到匹配的文档
r($docTest->getDocIdByTitleTest(1002, '测试文档2')) && p() && e('2'); // 步骤2：正常情况 - 找到第二个匹配的文档
r($docTest->getDocIdByTitleTest(1003, '不存在的标题')) && p() && e('0'); // 步骤3：异常输入 - 标题不存在
r($docTest->getDocIdByTitleTest(9999, '测试文档1')) && p() && e('0'); // 步骤4：异常输入 - originPageID不存在
r($docTest->getDocIdByTitleTest(1001, '')) && p() && e('0'); // 步骤5：边界值 - 空标题