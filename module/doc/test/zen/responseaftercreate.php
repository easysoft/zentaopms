#!/usr/bin/env php
<?php

/**

title=测试 docZen::responseAfterCreate();
timeout=0
cid=0

- 步骤1：正常文档创建
 - 属性result @success
 - 属性load @/doc-view-1.html
- 步骤2：模板文档创建
 - 属性result @success
 - 属性load @/doc-browseTemplate-2.html
- 步骤3：JSON视图类型
 - 属性result @success
 - 属性id @3
- 步骤4：包含文件
 - 属性result @success
 - 属性id @4
- 步骤5：空文档结果异常处理 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备
$table = zenData('doc');
$table->id->range('1-10');
$table->lib->range('1-3');
$table->title->range('测试文档{1-10}');
$table->type->range('html{5},md{5}');
$table->status->range('normal{8},draft{2}');
$table->addedBy->range('admin');
$table->addedDate->range('`2023-01-01 00:00:00`');
$table->gen(10);

$contentTable = zenData('doccontent');
$contentTable->id->range('1-10');
$contentTable->doc->range('1-10');
$contentTable->title->range('测试文档{1-10}');
$contentTable->content->range('这是测试文档内容{1-10}');
$contentTable->version->range('1');
$contentTable->gen(10);

$actionTable = zenData('action');
$actionTable->id->range('1-100');
$actionTable->objectType->range('doc');
$actionTable->objectID->range('1-10');
$actionTable->actor->range('admin');
$actionTable->action->range('Created');
$actionTable->date->range('`2023-01-01 00:00:00`');
$actionTable->gen(20);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->responseAfterCreateTest(array('id' => 1, 'title' => '测试文档', 'lib' => 1), 'doc')) && p('result,load') && e('success,/doc-view-1.html'); // 步骤1：正常文档创建
r($docTest->responseAfterCreateTest(array('id' => 2, 'title' => '模板文档', 'lib' => 2), 'docTemplate')) && p('result,load') && e('success,/doc-browseTemplate-2.html'); // 步骤2：模板文档创建
r($docTest->responseAfterCreateTest(array('id' => 3, 'title' => 'JSON文档', 'lib' => 3), 'doc')) && p('result,id') && e('success,3'); // 步骤3：JSON视图类型
r($docTest->responseAfterCreateTest(array('id' => 4, 'title' => '带文件文档', 'lib' => 1, 'files' => array('file1.txt', 'file2.pdf')), 'doc')) && p('result,id') && e('success,4'); // 步骤4：包含文件
r($docTest->responseAfterCreateTest(array(), 'doc')) && p('') && e('0'); // 步骤5：空文档结果异常处理