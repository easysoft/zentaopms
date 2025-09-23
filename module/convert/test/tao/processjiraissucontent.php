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

// 创建临时表结构
$dbh = $tester->dbh;
$dbh->exec("DROP TABLE IF EXISTS `jiratmprelation`");
$dbh->exec(<<<EOT
CREATE TABLE IF NOT EXISTS `jiratmprelation`(
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `AType` char(30) NOT NULL,
  `AID` char(100) NOT NULL,
  `BType` char(30) NOT NULL,
  `BID` char(100) NOT NULL,
  `extra` char(100) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relation` (`AType`,`BType`,`AID`,`BID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOT);

// 准备基础关联数据
zendata('jiratmprelation')->loadYaml('jiratmprelation_processjiraissucontent', false, 2)->gen(15);

// 准备文件数据
$fileTable = zenData('file');
$fileTable->title->range('attachment1.png,attachment2.pdf,attachment3.txt,image.jpg,document.docx');
$fileTable->objectType->range('story,bug,task,ticket,feedback,testcase,customflow');
$fileTable->objectID->range('1-10');
$fileTable->pathname->range('/uploads/files/attachment1.png,/uploads/files/attachment2.pdf,/uploads/files/attachment3.txt');
$fileTable->extension->range('png,pdf,txt,jpg,docx');
$fileTable->gen(20);

// 准备story相关数据
$storyTable = zenData('story');
$storyTable->id->range('1-5');
$storyTable->title->range('Story 1,Story 2,Story 3,Story 4,Story 5');
$storyTable->gen(5);

$storySpecTable = zenData('storyspec');
$storySpecTable->story->range('1-5');
$storySpecTable->version->range('1');
$storySpecTable->title->range('Story Spec 1,Story Spec 2,Story Spec 3,Story Spec 4,Story Spec 5');
$storySpecTable->spec->range('Original story content with jira links,Another story spec,Third story content,Fourth story,Fifth story');
$storySpecTable->gen(5);

// 准备bug数据
$bugTable = zenData('bug');
$bugTable->id->range('1-5');
$bugTable->title->range('Bug 1,Bug 2,Bug 3,Bug 4,Bug 5');
$bugTable->steps->range('Original bug steps with jira content,Bug steps 2,Bug steps 3,Bug steps 4,Bug steps 5');
$bugTable->gen(5);

// 准备task数据
$taskTable = zenData('task');
$taskTable->id->range('1-5');
$taskTable->name->range('Task 1,Task 2,Task 3,Task 4,Task 5');
$taskTable->desc->range('Original task description with jira,Task desc 2,Task desc 3,Task desc 4,Task desc 5');
$taskTable->gen(5);

// 准备ticket数据
$ticketTable = zenData('ticket');
$ticketTable->id->range('1-5');
$ticketTable->title->range('Ticket 1,Ticket 2,Ticket 3,Ticket 4,Ticket 5');
$ticketTable->desc->range('Original ticket description,Ticket desc 2,Ticket desc 3,Ticket desc 4,Ticket desc 5');
$ticketTable->assignedDate->range('`2024-01-01 00:00:00`');
$ticketTable->realStarted->range('`2024-01-01 00:00:00`');
$ticketTable->startedDate->range('`2024-01-01 00:00:00`');
$ticketTable->deadline->range('`2024-01-01`');
$ticketTable->openedDate->range('`2024-01-01 00:00:00`');
$ticketTable->gen(5);

// 准备feedback数据
$feedbackTable = zenData('feedback');
$feedbackTable->id->range('1-5');
$feedbackTable->title->range('Feedback 1,Feedback 2,Feedback 3,Feedback 4,Feedback 5');
$feedbackTable->desc->range('Original feedback description,Feedback desc 2,Feedback desc 3,Feedback desc 4,Feedback desc 5');
$feedbackTable->gen(5);

// 准备testcase数据
$testcaseTable = zenData('case');
$testcaseTable->id->range('1-3');
$testcaseTable->title->range('Test Case 1,Test Case 2,Test Case 3');
$testcaseTable->gen(3);

// 准备action数据
$actionTable = zenData('action');
$actionTable->id->range('1-10');
$actionTable->objectType->range('story,bug,task,ticket,feedback');
$actionTable->objectID->range('1-5');
$actionTable->actor->range('admin,user1,user2');
$actionTable->action->range('created,edited,commented');
$actionTable->comment->range('Original comment with jira links,Comment 2,Comment 3,Another comment,Final comment');
$actionTable->gen(10);

su('admin');

$convertTest = new convertTest();

// 测试步骤1：处理空的issue列表
r($convertTest->processJiraIssueContentTest(array())) && p() && e('1');

// 测试步骤2：处理包含story类型的issue列表
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1),
    (object)array('BType' => 'astory', 'BID' => 2)
))) && p() && e('1');

// 测试步骤3：处理包含bug类型的issue列表
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'abug', 'BID' => 1),
    (object)array('BType' => 'abug', 'BID' => 2)
))) && p() && e('1');

// 测试步骤4：处理包含task类型的issue列表
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atask', 'BID' => 1),
    (object)array('BType' => 'atask', 'BID' => 2)
))) && p() && e('1');

// 测试步骤5：处理包含ticket和feedback类型的issue列表
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'aticket', 'BID' => 1),
    (object)array('BType' => 'afeedback', 'BID' => 1)
))) && p() && e('1');

// 测试步骤6：处理testcase类型的issue（应被跳过）
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atestcase', 'BID' => 1),
    (object)array('BType' => 'atestcase', 'BID' => 2)
))) && p() && e('1');

// 测试步骤7：处理包含自定义工作流对象的issue列表
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'acustomflow', 'BID' => 1)
))) && p() && e('1');

// 测试步骤8：处理混合多种类型的issue列表
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1),
    (object)array('BType' => 'abug', 'BID' => 1),
    (object)array('BType' => 'atask', 'BID' => 1),
    (object)array('BType' => 'aticket', 'BID' => 1),
    (object)array('BType' => 'afeedback', 'BID' => 1)
))) && p() && e('1');

// 测试步骤9：处理不存在文件关联的issue列表
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 999),
    (object)array('BType' => 'abug', 'BID' => 999)
))) && p() && e('1');

// 测试步骤10：处理无效的BType格式
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'invalidtype', 'BID' => 1),
    (object)array('BType' => '', 'BID' => 1)
))) && p() && e('1');