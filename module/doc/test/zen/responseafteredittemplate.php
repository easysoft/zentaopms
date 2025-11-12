#!/usr/bin/env php
<?php

/**

title=测试 docZen::responseAfterEditTemplate();
timeout=0
cid=0

- 测试编辑模板文档无修改属性result @success
- 测试编辑模板文档有变更属性result @success
- 测试草稿状态变更为正常状态属性result @success
- 测试正常状态变更为草稿状态属性result @success
- 测试编辑模板文档附带评论属性result @success
- 测试编辑模板文档添加附件属性result @success
- 测试编辑模板文档同时变更和添加附件属性result @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$doc = zenData('doc');
$doc->id->range('1-10');
$doc->lib->range('1-5');
$doc->title->range('Template`10');
$doc->status->range('normal{5},draft{5}');
$doc->type->range('text');
$doc->addedBy->range('admin');
$doc->deleted->range('0');
$doc->gen(10);

$docLib = zenData('doclib');
$docLib->id->range('1-5');
$docLib->name->range('Library`5');
$docLib->type->range('mine,custom,product,project,execution');
$docLib->acl->range('open');
$docLib->deleted->range('0');
$docLib->gen(5);

su('admin');

$docTest = new docZenTest();

$doc1 = new stdClass();
$doc1->id = 1;
$doc1->lib = 1;
$doc1->status = 'normal';

$doc2 = new stdClass();
$doc2->id = 2;
$doc2->lib = 1;
$doc2->status = 'normal';

$doc3 = new stdClass();
$doc3->id = 6;
$doc3->lib = 2;
$doc3->status = 'draft';

$doc4 = new stdClass();
$doc4->id = 7;
$doc4->lib = 2;
$doc4->status = 'normal';

$doc5 = new stdClass();
$doc5->id = 3;
$doc5->lib = 1;
$doc5->status = 'normal';

$doc6 = new stdClass();
$doc6->id = 4;
$doc6->lib = 1;
$doc6->status = 'normal';

$doc7 = new stdClass();
$doc7->id = 5;
$doc7->lib = 1;
$doc7->status = 'normal';

$changes1 = array();
$changes2 = array(array('field' => 'title', 'old' => 'Old Title', 'new' => 'New Title'));
$changes3 = array(array('field' => 'status', 'old' => 'draft', 'new' => 'normal'));
$changes4 = array(array('field' => 'status', 'old' => 'normal', 'new' => 'draft'));
$files1 = array();
$files2 = array('file1.txt', 'file2.pdf');
$postData1 = array('comment' => '', 'status' => 'normal');
$postData2 = array('comment' => '', 'status' => 'normal');
$postData3 = array('comment' => '', 'status' => 'normal');
$postData4 = array('comment' => '', 'status' => 'draft');
$postData5 = array('comment' => 'This is a test comment', 'status' => 'normal');
$postData6 = array('comment' => '', 'status' => 'normal');
$postData7 = array('comment' => '', 'status' => 'normal');

r($docTest->responseAfterEditTemplateTest($doc1, $changes1, $files1, $postData1)) && p('result') && e('success'); // 测试编辑模板文档无修改
r($docTest->responseAfterEditTemplateTest($doc2, $changes2, $files1, $postData2)) && p('result') && e('success'); // 测试编辑模板文档有变更
r($docTest->responseAfterEditTemplateTest($doc3, $changes3, $files1, $postData3)) && p('result') && e('success'); // 测试草稿状态变更为正常状态
r($docTest->responseAfterEditTemplateTest($doc4, $changes4, $files1, $postData4)) && p('result') && e('success'); // 测试正常状态变更为草稿状态
r($docTest->responseAfterEditTemplateTest($doc5, $changes1, $files1, $postData5)) && p('result') && e('success'); // 测试编辑模板文档附带评论
r($docTest->responseAfterEditTemplateTest($doc6, $changes2, $files2, $postData6)) && p('result') && e('success'); // 测试编辑模板文档添加附件
r($docTest->responseAfterEditTemplateTest($doc7, $changes2, $files2, $postData7)) && p('result') && e('success'); // 测试编辑模板文档同时变更和添加附件