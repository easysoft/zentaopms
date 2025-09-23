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

// 注意：jiratmprelation表不存在，跳过此数据准备

// 准备file测试数据
$fileTable = zenData('file');
$fileTable->title->range('image.png,document.pdf,attachment.txt,screenshot.jpg,readme.md,design.psd');
$fileTable->extension->range('png,pdf,txt,jpg,md,psd');
$fileTable->objectType->range('story,bug,task,ticket,feedback,testcase,customflow');
$fileTable->objectID->range('1-6');
$fileTable->gen(20);

// 准备story相关测试数据
$storyTable = zenData('story');
$storyTable->id->range('1-6');
$storyTable->title->range('Story1,Story2,Story3,Story4,Story5,Story6');
$storyTable->gen(6);

$storySpecTable = zenData('storyspec');
$storySpecTable->story->range('1-6');
$storySpecTable->version->range('1');
$storySpecTable->title->range('Spec1,Spec2,Spec3,Spec4,Spec5,Spec6');
$storySpecTable->spec->range('Content with !image.png|thumbnail! in it,Normal content,Another !document.pdf|attachment! reference,[],Content with multiple !screenshot.jpg|thumb! and !readme.md|doc! files,Empty content without attachments');
$storySpecTable->gen(6);

// 准备bug测试数据
$bugTable = zenData('bug');
$bugTable->id->range('1-6');
$bugTable->title->range('Bug1,Bug2,Bug3,Bug4,Bug5,Bug6');
$bugTable->steps->range('Bug steps with !image.png|screenshot! screenshot,Simple bug steps,Steps with !attachment.txt|file! file,[],Complex steps with !document.pdf|attachment! attachment,Bug without files');
$bugTable->gen(6);

// 准备task测试数据
$taskTable = zenData('task');
$taskTable->id->range('1-6');
$taskTable->name->range('Task1,Task2,Task3,Task4,Task5,Task6');
$taskTable->desc->range('Task description with !screenshot.jpg|image! image,Plain task description,Description with !readme.md|file! file,[],Multi-file task with !image.png|thumb! and !document.pdf|doc!,Task without files');
$taskTable->gen(6);

// 注意：跳过ticket测试数据准备以避免assignedDate字段问题

// 准备feedback测试数据
$feedbackTable = zenData('feedback');
$feedbackTable->id->range('1-6');
$feedbackTable->title->range('Feedback1,Feedback2,Feedback3,Feedback4,Feedback5,Feedback6');
$feedbackTable->desc->range('Feedback with !readme.md|file! file,Plain feedback,Feedback with !image.png|image! image,[],Multi-attachment feedback with !document.pdf|doc! and !attachment.txt|file!,Feedback without files');
$feedbackTable->gen(6);

// 准备testcase测试数据
$testcaseTable = zenData('case');
$testcaseTable->id->range('1-6');
$testcaseTable->title->range('Testcase1,Testcase2,Testcase3,Testcase4,Testcase5,Testcase6');
$testcaseTable->gen(6);

// 注意：zt_flow_customflow表不存在，跳过此数据准备

// 准备action测试数据
$actionTable = zenData('action');
$actionTable->id->range('1-20');
$actionTable->objectType->range('story{4},bug{4},task{4},ticket{4},feedback{4}');
$actionTable->objectID->range('1-6');
$actionTable->action->range('created,edited,commented,reviewed');
$actionTable->comment->range('Action comment with !image.png|attachment! attachment,Normal comment,Comment with !document.pdf|file! file,[],Action with !screenshot.jpg|image! image');
$actionTable->gen(20);

su('admin');

$convertTest = new convertTest();

// 测试步骤1：处理包含附件的story类型Jira内容
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1),
    (object)array('BType' => 'arequirement', 'BID' => 2)
))) && p() && e('1');

// 测试步骤2：处理包含附件的bug类型Jira内容
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'abug', 'BID' => 1),
    (object)array('BType' => 'abug', 'BID' => 2)
))) && p() && e('1');

// 测试步骤3：处理包含附件的task类型Jira内容
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atask', 'BID' => 1),
    (object)array('BType' => 'atask', 'BID' => 2)
))) && p() && e('1');

// 测试步骤4：处理包含附件的feedback类型内容
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'afeedback', 'BID' => 1),
    (object)array('BType' => 'afeedback', 'BID' => 2)
))) && p() && e('1');

// 测试步骤5：测试testcase类型被跳过的逻辑
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atestcase', 'BID' => 1),
    (object)array('BType' => 'atestcase', 'BID' => 2)
))) && p() && e('1');

// 测试步骤6：处理空issue列表输入
r($convertTest->processJiraIssueContentTest(array())) && p() && e('1');

// 测试步骤7：处理不存在附件的对象
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 6),
    (object)array('BType' => 'abug', 'BID' => 6)
))) && p() && e('1');

// 测试步骤8：处理requirement和epic类型的内容
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'arequirement', 'BID' => 1),
    (object)array('BType' => 'aepic', 'BID' => 1)
))) && p() && e('1');

// 测试步骤9：处理包含action记录的对象内容更新
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 3),
    (object)array('BType' => 'abug', 'BID' => 4),
    (object)array('BType' => 'atask', 'BID' => 5)
))) && p() && e('1');