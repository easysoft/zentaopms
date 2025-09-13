#!/usr/bin/env php
<?php

/**

title=测试 docZen::responseAfterEdit();
timeout=0
cid=0

- 步骤1：正常编辑响应属性result @success
- 步骤2：带变更记录属性result @success
- 步骤3：带文件附件属性result @success
- 步骤4：带评论内容属性result @success
- 步骤5：状态变更属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doc');
$table->id->range('1-10');
$table->lib->range('1-3');
$table->title->range('测试文档{1-10}');
$table->type->range('text');
$table->status->range('normal{5},draft{5}');
$table->addedBy->range('admin,user1,user2');
$table->addedDate->range('`2023-01-01 00:00:00`-`2023-12-31 23:59:59`')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$table->gen(10);

$docLibTable = zenData('doclib');
$docLibTable->id->range('1-5');
$docLibTable->name->range('测试库{1-5}');
$docLibTable->type->range('custom,product,project');
$docLibTable->acl->range('open,private,default');
$docLibTable->addedBy->range('admin');
$docLibTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$doc = new stdClass();
$doc->id = 1;
$doc->title = '测试文档';
$doc->status = 'normal';
$doc->lib = 1;

// 准备POST数据
$_POST['comment'] = '';
$_POST['status'] = 'normal';

r($docTest->responseAfterEditTest($doc, array(), array())) && p('result') && e('success'); // 步骤1：正常编辑响应
$changes = array(array('field' => 'title', 'old' => '旧标题', 'new' => '新标题'));
r($docTest->responseAfterEditTest($doc, $changes, array())) && p('result') && e('success'); // 步骤2：带变更记录
$files = array('file1.txt', 'file2.pdf');
r($docTest->responseAfterEditTest($doc, array(), $files)) && p('result') && e('success'); // 步骤3：带文件附件
$_POST['comment'] = '添加测试评论';
r($docTest->responseAfterEditTest($doc, array(), array())) && p('result') && e('success'); // 步骤4：带评论内容
$doc->status = 'draft';
$_POST['status'] = 'normal';
$changes = array(array('field' => 'status', 'old' => 'draft', 'new' => 'normal'));
r($docTest->responseAfterEditTest($doc, $changes, array())) && p('result') && e('success'); // 步骤5：状态变更