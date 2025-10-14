#!/usr/bin/env php
<?php

/**

title=测试 docZen::responseAfterEditTemplate();
timeout=0
cid=0

- 步骤1：正常编辑，状态从draft变为normal属性action @releasedDoc
- 步骤2：验证返回结果成功属性result @success
- 步骤3：状态从normal变为draft属性action @savedDraft
- 步骤4：只添加评论属性action @Commented
- 步骤5：编辑并添加文件属性objectType @docTemplate

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doc');
$table->loadYaml('zt_doc_responseafteredittemplate', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 准备测试文档对象
$doc1 = new stdClass();
$doc1->id = 1;
$doc1->status = 'draft';
$doc1->title = '测试模板1';

$doc2 = new stdClass();
$doc2->id = 2;
$doc2->status = 'draft';
$doc2->title = '测试模板2';

$doc3 = new stdClass();
$doc3->id = 3;
$doc3->status = 'normal';
$doc3->title = '测试模板3';

$doc4 = new stdClass();
$doc4->id = 4;
$doc4->status = 'normal';
$doc4->title = '测试模板4';

$doc5 = new stdClass();
$doc5->id = 5;
$doc5->status = 'draft';
$doc5->title = '测试模板5';

// 5. 强制要求：必须包含至少5个测试步骤
r($docTest->responseAfterEditTemplateTest($doc1, array('title' => '新标题1'), array(), '', 'normal', false)) && p('action') && e('releasedDoc'); // 步骤1：正常编辑，状态从draft变为normal
r($docTest->responseAfterEditTemplateTest($doc2, array('title' => '新标题2'), array(), '', 'normal', false)) && p('result') && e('success'); // 步骤2：验证返回结果成功
r($docTest->responseAfterEditTemplateTest($doc3, array('status' => 'draft'), array(), '', 'draft', false)) && p('action') && e('savedDraft'); // 步骤3：状态从normal变为draft
r($docTest->responseAfterEditTemplateTest($doc4, array(), array(), '这是评论内容', '', false)) && p('action') && e('Commented'); // 步骤4：只添加评论
r($docTest->responseAfterEditTemplateTest($doc5, array('title' => '新标题5'), array('file1.txt', 'file2.pdf'), '编辑并添加文件', 'normal', false)) && p('objectType') && e('docTemplate'); // 步骤5：编辑并添加文件