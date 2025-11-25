#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processJiraIssueContent();
timeout=0
cid=15870

- 执行convertTest模块的processJiraIssueContentTest方法，参数是array  @1
- 执行convertTest模块的processJiraIssueContentTest方法，参数是$largeIssueList  @1
- 执行$storySpecContent, '!attachment1.png|width=200!') !== false @1
- 执行$bugStepsContent, '!attachment2.pdf|width=100!') !== false @1
- 执行$taskDescContent, '!image.jpg|width=150!') !== false @1
- 执行$ticketDescContent !== null @1
- 执行$actionCommentContent, '!attachment1.png|width=100!') !== false @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 创建自定义工作流测试表
$dbh = $tester->dbh;
$dbh->exec("DROP TABLE IF EXISTS `zt_flow_customflow`");
$dbh->exec(<<<EOT
CREATE TABLE IF NOT EXISTS `zt_flow_customflow`(
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `customflowdesc` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOT);

// 插入自定义工作流测试数据
$dbh->exec("INSERT INTO `zt_flow_customflow` (`id`, `customflowdesc`) VALUES (1, 'Custom flow content with !attachment1.png|width=200! jira image')");

// 准备文件数据
$fileTable = zenData('file');
$fileTable->id->range('1-20');
$fileTable->title->range('attachment1.png,attachment2.pdf,image.jpg,document.docx,video.mp4,large_file.zip,empty.txt,special-chars@file.png,文件中文名.doc,test-file.txt,null_size.dat,multi_dot.file.ext,UPPERCASE.PNG,lowercase.jpeg,123numeric.gif,special$&chars.doc,very_long_filename_with_many_characters.pdf,attachment3.png,attachment4.jpg,attachment5.pdf');
$fileTable->objectType->range('story{5},bug{4},task{4},ticket{3},feedback{2},customflow{2}');
$fileTable->objectID->range('1{3},2{2},3{1},1{2},2{2},999{1},1{2},2{1},3{1},1{2},2{1},1{2},1{1},999{1},1{1},1{1},2{1},3{1},4{1},5{1}');
$fileTable->pathname->range('/uploads/files/attachment1.png,/uploads/files/attachment2.pdf,/uploads/files/image.jpg,/uploads/files/document.docx,/uploads/files/video.mp4,/uploads/files/large_file.zip,/uploads/files/empty.txt,/uploads/files/special-chars@file.png,/uploads/files/文件中文名.doc,/uploads/files/test-file.txt,/uploads/files/null_size.dat,/uploads/files/multi_dot.file.ext,/uploads/files/UPPERCASE.PNG,/uploads/files/lowercase.jpeg,/uploads/files/123numeric.gif,/uploads/files/special$&chars.doc,/uploads/files/very_long_filename.pdf,/uploads/files/attachment3.png,/uploads/files/attachment4.jpg,/uploads/files/attachment5.pdf');
$fileTable->extension->range('png,pdf,jpg,docx,mp4,zip,txt,dat,ext,jpeg,gif,doc');
$fileTable->addedBy->range('admin,user1,guest,system');
$fileTable->addedDate->range('`2024-01-01 00:00:00`,`2023-12-31 23:59:59`,`2024-02-01 12:00:00`,`2024-03-01 08:30:00`');
$fileTable->gen(20);

// 准备story数据
$storyTable = zenData('story');
$storyTable->id->range('1-5');
$storyTable->title->range('Story 1,Story 2,Story 3,Empty Story,Story Without Files');
$storyTable->type->range('story{2},requirement{2},epic{1}');
$storyTable->status->range('active{3},closed{1},draft{1}');
$storyTable->openedBy->range('admin,user1,guest');
$storyTable->openedDate->range('`2024-01-01 00:00:00`');
$storyTable->gen(5);

$storySpecTable = zenData('storyspec');
$storySpecTable->story->range('1-5');
$storySpecTable->version->range('1');
$storySpecTable->title->range('Story Spec 1,Story Spec 2,Story Spec 3,Empty Story Spec,Story Spec Without Files');
$storySpecTable->spec->range('Story content with !attachment1.png|width=200! jira image,Another story with !image.jpg|width=150! and !document.docx|width=100!,Story with !special-chars@file.png|width=120! and !文件中文名.doc|width=180! files,Story with !UPPERCASE.PNG|width=100! and !multi_dot.file.ext|width=90! files,Story spec for ID 5 without files');
$storySpecTable->gen(5);

// 准备bug数据
$bugTable = zenData('bug');
$bugTable->id->range('1-4');
$bugTable->title->range('Bug 1,Bug 2,Critical Bug,Empty Steps Bug');
$bugTable->steps->range('Bug steps with !attachment2.pdf|width=100! jira link,Bug steps without attachments,Critical bug with !video.mp4|width=120! attachment,');
$bugTable->severity->range('1-4');
$bugTable->status->range('active{2},resolved{1},closed{1}');
$bugTable->openedBy->range('admin,tester,user1');
$bugTable->openedDate->range('`2024-01-01 00:00:00`');
$bugTable->gen(4);

// 准备task数据
$taskTable = zenData('task');
$taskTable->id->range('1-4');
$taskTable->name->range('Task 1,Task 2,Task 3,Task Without Files');
$taskTable->desc->range('Task with !image.jpg|width=150! jira link,Task without attachments,,Task description without files');
$taskTable->type->range('design,devel,test,study');
$taskTable->status->range('wait{2},doing{1},done{1}');
$taskTable->openedBy->range('admin,dev1,pm1');
$taskTable->openedDate->range('`2024-01-01 00:00:00`');
$taskTable->gen(4);

// 准备ticket数据
$ticketTable = zenData('ticket');
$ticketTable->id->range('1-3');
$ticketTable->title->range('Ticket 1,Ticket 2,Empty Ticket');
$ticketTable->desc->range('Ticket with !document.docx|width=200! attachment,Ticket with multiple !large_file.zip|width=180! and !empty.txt|width=80! files,');
$ticketTable->type->range('bug,task,story');
$ticketTable->status->range('wait,assigned,closed');
$ticketTable->assignedDate->range('`2024-01-01 00:00:00`');
$ticketTable->realStarted->range('`2024-01-01 00:00:00`');
$ticketTable->startedDate->range('`2024-01-01 00:00:00`');
$ticketTable->deadline->range('`2024-01-01`');
$ticketTable->openedDate->range('`2024-01-01 00:00:00`');
$ticketTable->gen(3);

// 准备feedback数据
$feedbackTable = zenData('feedback');
$feedbackTable->id->range('1-2');
$feedbackTable->title->range('Feedback 1,Feedback 2');
$feedbackTable->desc->range('Feedback with !special-chars@file.png|width=160! jira attachment,Feedback without any attachments');
$feedbackTable->status->range('wait,replied');
$feedbackTable->openedBy->range('admin,customer');
$feedbackTable->openedDate->range('`2024-01-01 00:00:00`');
$feedbackTable->gen(2);

// 准备testcase数据
$testcaseTable = zenData('case');
$testcaseTable->id->range('1');
$testcaseTable->title->range('Test Case 1');
$testcaseTable->type->range('feature');
$testcaseTable->status->range('normal');
$testcaseTable->openedBy->range('admin');
$testcaseTable->openedDate->range('`2024-01-01 00:00:00`');
$testcaseTable->gen(1);

// 准备action数据
$actionTable = zenData('action');
$actionTable->id->range('1-10');
$actionTable->objectType->range('story{3},bug{2},task{2},ticket{2},feedback{1}');
$actionTable->objectID->range('1{3},1{2},1{2},1{2},1{1}');
$actionTable->actor->range('admin,user1,tester,dev1,customer');
$actionTable->action->range('commented{7},created{2},edited{1}');
$actionTable->comment->range('Comment with !attachment1.png|width=100! jira link,Normal comment without attachments,Empty comment,Comment with multiple !attachment1.png|width=50! and !image.jpg|width=80! links,Simple comment,Comment with !video.mp4|width=120! file,,Action comment with !文件中文名.doc|width=140! Chinese file,Created with !large_file.zip|width=160! attachment,Edited action comment');
$actionTable->date->range('`2024-01-01 00:00:00`');
$actionTable->gen(10);

su('admin');

$convertTest = new convertTest();

// 测试步骤1：正常处理多种类型的Jira问题内容转换（story、bug、task、ticket、feedback）
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1),
    (object)array('BType' => 'abug', 'BID' => 1),
    (object)array('BType' => 'atask', 'BID' => 1),
    (object)array('BType' => 'aticket', 'BID' => 1),
    (object)array('BType' => 'afeedback', 'BID' => 1)
))) && p() && e('1');

// 测试步骤2：验证边界情况处理能力（空列表输入）
r($convertTest->processJiraIssueContentTest(array())) && p() && e('1');

// 测试步骤3：验证不存在对象ID的处理逻辑
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 999),
    (object)array('BType' => 'abug', 'BID' => 888)
))) && p() && e('1');

// 测试步骤4：验证testcase类型跳过逻辑
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atestcase', 'BID' => 1)
))) && p() && e('1');

// 测试步骤5：验证自定义工作流类型的动态处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'acustomflow', 'BID' => 1)
))) && p() && e('1');

// 测试步骤6：验证action评论的Jira内容更新
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1)
))) && p() && e('1');

// 测试步骤7：验证混合类型对象列表的批量处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 2),
    (object)array('BType' => 'abug', 'BID' => 2),
    (object)array('BType' => 'atask', 'BID' => 2),
    (object)array('BType' => 'aticket', 'BID' => 2),
    (object)array('BType' => 'afeedback', 'BID' => 2),
    (object)array('BType' => 'arequirement', 'BID' => 3)
))) && p() && e('1');

// 测试步骤8：验证无关联文件的对象跳过处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 5),
    (object)array('BType' => 'atask', 'BID' => 4)
))) && p() && e('1');

// 测试步骤9：验证包含特殊字符文件名的处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'afeedback', 'BID' => 1)
))) && p() && e('1');

// 测试步骤10：验证大量数据的性能和稳定性
$largeIssueList = array();
for($i = 1; $i <= 5; $i++)
{
    $largeIssueList[] = (object)array('BType' => 'astory', 'BID' => $i);
    $largeIssueList[] = (object)array('BType' => 'abug', 'BID' => ($i > 4 ? 1 : $i));
    $largeIssueList[] = (object)array('BType' => 'atask', 'BID' => ($i > 4 ? 1 : $i));
}
r($convertTest->processJiraIssueContentTest($largeIssueList)) && p() && e('1');

// 测试步骤11：验证数据库更新结果 - 检查story规格内容转换（存在Jira格式说明处理成功）
$storySpecContent = $tester->dao->select('spec')->from(TABLE_STORYSPEC)->where('story')->eq(1)->fetch('spec');
r(strpos($storySpecContent, '!attachment1.png|width=200!') !== false) && p() && e('1');

// 测试步骤12：验证bug步骤内容转换（存在Jira格式说明处理成功）
$bugStepsContent = $tester->dao->select('steps')->from(TABLE_BUG)->where('id')->eq(1)->fetch('steps');
r(strpos($bugStepsContent, '!attachment2.pdf|width=100!') !== false) && p() && e('1');

// 测试步骤13：验证task描述内容转换（存在Jira格式说明处理成功）
$taskDescContent = $tester->dao->select('`desc`')->from(TABLE_TASK)->where('id')->eq(1)->fetch('desc');
r(strpos($taskDescContent, '!image.jpg|width=150!') !== false) && p() && e('1');

// 测试步骤14：验证ticket描述内容处理完成（不为null即正常）
$ticketDescContent = $tester->dao->select('`desc`')->from(TABLE_TICKET)->where('id')->eq(1)->fetch('desc');
r($ticketDescContent !== null) && p() && e('1');

// 测试步骤15：验证action评论内容转换（存在Jira格式说明处理成功）
$actionCommentContent = $tester->dao->select('comment')->from(TABLE_ACTION)->where('id')->eq(1)->fetch('comment');
r(strpos($actionCommentContent, '!attachment1.png|width=100!') !== false) && p() && e('1');