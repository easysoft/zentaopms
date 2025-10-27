#!/usr/bin/env php
<?php

/**

title=测试 docZen::responseAfterUploadDocs();
timeout=0
cid=0

- 步骤1：正常combinedDocs处理属性result @success
- 步骤2：正常多文档处理属性result @success
- 步骤3：空结果处理属性result @fail
- 步骤4：错误结果处理属性result @fail
- 步骤5：JSON响应格式属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doc');
$table->id->range('1-5');
$table->title->range('测试文档1,测试文档2,上传文档测试');
$table->type->range('html,attachment');
$table->status->range('normal,draft');
$table->addedBy->range('admin,user1,user2');
$table->addedDate->range('`2025-01-01 10:00:00`');
$table->editedDate->range('`2025-01-01 12:00:00`');
$table->lib->range('1-3');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->responseAfterUploadDocsTest(array('id' => 1, 'files' => array('file1.txt', 'file2.txt')), 'combinedDocs')) && p('result') && e('success'); // 步骤1：正常combinedDocs处理
r($docTest->responseAfterUploadDocsTest(array('docsAction' => array(2 => (object)array('title' => '测试文档'))), 'separateDocs')) && p('result') && e('success'); // 步骤2：正常多文档处理
r($docTest->responseAfterUploadDocsTest('', '')) && p('result') && e('fail'); // 步骤3：空结果处理
r($docTest->responseAfterUploadDocsTest(false, '')) && p('result') && e('fail'); // 步骤4：错误结果处理
r($docTest->responseAfterUploadDocsTest(array('id' => 3, 'files' => array('test.pdf')), 'combinedDocs', 'json')) && p('result') && e('success'); // 步骤5：JSON响应格式