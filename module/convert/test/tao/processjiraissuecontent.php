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

// 准备jiratmprelation测试数据
$relationTable = zenData('jiratmprelation');
$relationTable->AType->range('jissue');
$relationTable->AID->range('1-20');
$relationTable->BType->range('astory,abug,atask,aticket,afeedback,arequirement');
$relationTable->BID->range('1-5');
$relationTable->gen(20);

// 准备file测试数据
$fileTable = zenData('file');
$fileTable->title->range('image.png,document.pdf,attachment.txt,screenshot.jpg,readme.md');
$fileTable->extension->range('png,pdf,txt,jpg,md');
$fileTable->objectType->range('story,bug,task,ticket,feedback');
$fileTable->objectID->range('1-5');
$fileTable->gen(15);

// 准备story相关测试数据
$storyTable = zenData('story');
$storyTable->id->range('1-5');
$storyTable->title->range('Story1,Story2,Story3,Story4,Story5');
$storyTable->gen(5);

$storySpecTable = zenData('storyspec');
$storySpecTable->story->range('1-5');
$storySpecTable->version->range('1');
$storySpecTable->title->range('Spec1,Spec2,Spec3,Spec4,Spec5');
$storySpecTable->spec->range('Content with !image.png|thumbnail! in it,Normal content,Another !document.pdf|attachment! reference,[],Content with multiple !screenshot.jpg|thumb! and !readme.md|doc! files');
$storySpecTable->gen(5);

// 准备bug测试数据
$bugTable = zenData('bug');
$bugTable->id->range('1-5');
$bugTable->title->range('Bug1,Bug2,Bug3,Bug4,Bug5');
$bugTable->steps->range('Bug steps with !image.png|screenshot! screenshot,Simple bug steps,Steps with !attachment.txt|file! file,[],Complex steps with !document.pdf|attachment! attachment');
$bugTable->gen(5);

// 准备task测试数据
$taskTable = zenData('task');
$taskTable->id->range('1-5');
$taskTable->name->range('Task1,Task2,Task3,Task4,Task5');
$taskTable->desc->range('Task description with !screenshot.jpg|image! image,Plain task description,Description with !readme.md|file! file,[],Multi-file task with !image.png|thumb! and !document.pdf|doc!');
$taskTable->gen(5);

// 准备ticket测试数据
$ticketTable = zenData('ticket');
$ticketTable->id->range('1-5');
$ticketTable->title->range('Ticket1,Ticket2,Ticket3,Ticket4,Ticket5');
$ticketTable->desc->range('Ticket description with !attachment.txt|file! file,Normal ticket description,Description with !image.png|screenshot! screenshot,[],Ticket with !document.pdf|doc! and !screenshot.jpg|image! attachments');
$ticketTable->gen(5);

// 准备feedback测试数据
$feedbackTable = zenData('feedback');
$feedbackTable->id->range('1-5');
$feedbackTable->title->range('Feedback1,Feedback2,Feedback3,Feedback4,Feedback5');
$feedbackTable->desc->range('Feedback with !readme.md|file! file,Plain feedback,Feedback with !image.png|image! image,[],Multi-attachment feedback with !document.pdf|doc! and !attachment.txt|file!');
$feedbackTable->gen(5);

// 准备action测试数据
$actionTable = zenData('action');
$actionTable->id->range('1-15');
$actionTable->objectType->range('story{3},bug{3},task{3},ticket{3},feedback{3}');
$actionTable->objectID->range('1-5');
$actionTable->action->range('created,edited,commented');
$actionTable->comment->range('Action comment with !image.png|attachment! attachment,Normal comment,Comment with !document.pdf|file! file,[]{5}');
$actionTable->gen(15);

su('admin');

$convertTest = new convertTest();

// 测试步骤1：处理story类型的Jira内容
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1),
    (object)array('BType' => 'astory', 'BID' => 2)
))) && p() && e('1');

// 测试步骤2：处理bug类型的Jira内容
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'abug', 'BID' => 1),
    (object)array('BType' => 'abug', 'BID' => 2)
))) && p() && e('1');

// 测试步骤3：处理task类型的Jira内容
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atask', 'BID' => 1),
    (object)array('BType' => 'atask', 'BID' => 2)
))) && p() && e('1');

// 测试步骤4：处理ticket和feedback类型的Jira内容
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'aticket', 'BID' => 1),
    (object)array('BType' => 'afeedback', 'BID' => 1)
))) && p() && e('1');

// 测试步骤5：处理混合类型的Jira内容
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1),
    (object)array('BType' => 'abug', 'BID' => 1),
    (object)array('BType' => 'atask', 'BID' => 1),
    (object)array('BType' => 'aticket', 'BID' => 1),
    (object)array('BType' => 'afeedback', 'BID' => 1)
))) && p() && e('1');

// 测试步骤6：处理空列表输入
r($convertTest->processJiraIssueContentTest(array())) && p() && e('1');

// 测试步骤7：处理不存在的对象类型
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'anonexistent', 'BID' => 1)
))) && p() && e('1');

// 测试步骤8：处理包含action记录的对象
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 3),
    (object)array('BType' => 'abug', 'BID' => 3)
))) && p() && e('1');