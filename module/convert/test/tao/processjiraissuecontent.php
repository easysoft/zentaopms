#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processJiraIssueContent();
timeout=0
cid=0

- 执行convertTest模块的processJiraIssueContentTest方法，参数是array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 准备file测试数据
$fileTable = zenData('file');
$fileTable->id->range('1-20');
$fileTable->title->range('image.png,document.pdf,attachment.txt,screenshot.jpg,readme.md,design.psd,test.docx,photo.gif,code.js,style.css');
$fileTable->extension->range('png,pdf,txt,jpg,md,psd,docx,gif,js,css');
$fileTable->objectType->range('story,bug,task,ticket,feedback,testcase,customflow');
$fileTable->objectID->range('1-3');
$fileTable->gen(20);

// 准备story相关测试数据
$storyTable = zenData('story');
$storyTable->id->range('1-6');
$storyTable->title->range('Story1,Story2,Story3,Story4,Story5,Story6');
$storyTable->gen(6);

$storySpecTable = zenData('storyspec');
$storySpecTable->story->range('1-3');
$storySpecTable->version->range('1');
$storySpecTable->title->range('Story Spec 1,Story Spec 2,Story Spec 3');
$storySpecTable->spec->range('Content with !image.png|thumbnail! attachment,Another story with !document.pdf|attachment! file,Third story with !screenshot.jpg|thumb! image');
$storySpecTable->gen(3);

// 准备bug测试数据
$bugTable = zenData('bug');
$bugTable->id->range('1-3');
$bugTable->title->range('Bug Report 1,Bug Report 2,Bug Report 3');
$bugTable->steps->range('Bug steps with !screenshot.jpg|image! file,Another bug with !attachment.txt|file! attachment,Complex bug with !readme.md|doc! documentation');
$bugTable->gen(3);

// 准备task测试数据
$taskTable = zenData('task');
$taskTable->id->range('1-3');
$taskTable->name->range('Task 1,Task 2,Task 3');
$taskTable->desc->range('Task with !design.psd|image! design,Task with !code.js|file! code,Task with !style.css|file! stylesheet');
$taskTable->gen(3);

// 跳过ticket测试数据准备，由于assignedDate字段的复杂性

// 准备feedback测试数据
$feedbackTable = zenData('feedback');
$feedbackTable->id->range('1-3');
$feedbackTable->title->range('Feedback 1,Feedback 2,Feedback 3');
$feedbackTable->desc->range('Feedback with !image.png|image! screenshot,Feedback with !document.pdf|attachment! document,Empty feedback content');
$feedbackTable->gen(3);

// 准备testcase测试数据（会被跳过处理）
$testcaseTable = zenData('case');
$testcaseTable->id->range('1-3');
$testcaseTable->title->range('Test Case 1,Test Case 2,Test Case 3');
$testcaseTable->gen(3);

// 准备action测试数据
$actionTable = zenData('action');
$actionTable->id->range('1-15');
$actionTable->objectType->range('story{3},bug{3},task{3},ticket{3},feedback{3}');
$actionTable->objectID->range('1-3');
$actionTable->action->range('created,edited,commented');
$actionTable->comment->range('Comment with !image.png|image! file,Comment with !document.pdf|attachment! document,Simple comment without files');
$actionTable->gen(15);

su('admin');

$convertTest = new convertTest();

// 测试步骤1：处理story类型对象的Jira内容转换
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1)
))) && p() && e('1');

// 测试步骤2：处理bug类型对象的Jira内容转换
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'abug', 'BID' => 1)
))) && p() && e('1');

// 测试步骤3：处理task类型对象的Jira内容转换
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atask', 'BID' => 1)
))) && p() && e('1');

// 测试步骤4：处理feedback类型对象的Jira内容转换
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'afeedback', 'BID' => 1)
))) && p() && e('1');

// 测试步骤5：验证testcase类型对象被跳过的逻辑
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atestcase', 'BID' => 1)
))) && p() && e('1');

// 测试步骤6：处理自定义流程对象的Jira内容转换
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'acustomflow', 'BID' => 1)
))) && p() && e('1');

// 测试步骤7：处理空数据和边界条件
r($convertTest->processJiraIssueContentTest(array())) && p() && e('1');

// 测试步骤8：验证action记录的内容转换
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 2),
    (object)array('BType' => 'abug', 'BID' => 2)
))) && p() && e('1');