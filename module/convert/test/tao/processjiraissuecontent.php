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

// 准备测试文件数据 - 涵盖各种对象类型和文件类型
$fileTable = zenData('file');
$fileTable->id->range('1-30');
$fileTable->title->range('screenshot.png,document.pdf,attachment.txt,design.jpg,readme.md,video.mp4,archive.zip,spreadsheet.xlsx,presentation.pptx,database.sql');
$fileTable->extension->range('png,pdf,txt,jpg,md,mp4,zip,xlsx,pptx,sql');
$fileTable->objectType->range('story{8},bug{8},task{6},feedback{3},ticket{3},customflow{2}');
$fileTable->objectID->range('1-4');
$fileTable->gen(30);

// 准备story测试数据 - 包含各种Jira内容格式
$storyTable = zenData('story');
$storyTable->id->range('1-8');
$storyTable->title->range('Story with Jira content,Empty story,Story without files,Complex story,Epic story,Requirement story,Multiple attachments story,Single attachment story');
$storyTable->gen(8);

$storySpecTable = zenData('storyspec');
$storySpecTable->story->range('1-8');
$storySpecTable->version->range('1');
$storySpecTable->title->range('Spec1,Spec2,Spec3,Spec4,Spec5,Spec6,Spec7,Spec8');
$storySpecTable->spec->range('Story with !screenshot.png|thumbnail! and !document.pdf|attachment! files,,,Story with !design.jpg|image! design,Epic with !video.mp4|media! demo,Requirement with !readme.md|doc! specification,Complex story with !archive.zip|file! !presentation.pptx|file! !database.sql|file! attachments,Single story with !attachment.txt|file! only');
$storySpecTable->gen(8);

// 准备bug测试数据 - 包含Jira格式的步骤描述
$bugTable = zenData('bug');
$bugTable->id->range('1-8');
$bugTable->title->range('Bug with screenshot,Empty bug,Simple bug,Complex bug,Critical bug,Fixed bug,Multi-attachment bug,Media bug');
$bugTable->steps->range('Reproduce with !screenshot.png|image! evidence,,,Bug with !attachment.txt|file! log file,Critical issue with !database.sql|file! backup,Fixed with !presentation.pptx|file! analysis,Multiple attachments: !screenshot.png|image! !document.pdf|file! !design.jpg|image!,Video bug with !video.mp4|media! reproduction');
$bugTable->gen(8);

// 准备task测试数据 - 包含Jira格式的任务描述
$taskTable = zenData('task');
$taskTable->id->range('1-6');
$taskTable->name->range('Development task,Empty task,Documentation task,Review task,Complex task,Media task');
$taskTable->desc->range('Task with !archive.zip|file! resources,,,Documentation with !readme.md|doc! guide,Review with !spreadsheet.xlsx|file! data,Complex task with !screenshot.png|image! !document.pdf|file! attachments,Media task with !video.mp4|media! demo');
$taskTable->gen(6);

// 准备feedback测试数据 - 包含Jira格式的反馈描述
$feedbackTable = zenData('feedback');
$feedbackTable->id->range('1-3');
$feedbackTable->title->range('User feedback,Bug report,Feature request');
$feedbackTable->desc->range('Feedback with !screenshot.png|image! screenshot,Report with !document.pdf|attachment! details,Feature request with !design.jpg|image! mockup');
$feedbackTable->gen(3);

// 准备testcase测试数据 - 验证跳过逻辑
$testcaseTable = zenData('case');
$testcaseTable->id->range('1-2');
$testcaseTable->title->range('Test case 1,Test case 2');
$testcaseTable->precondition->range('Prerequisites,Setup with attachments');
$testcaseTable->gen(2);

// 跳过ticket测试数据生成，因为日期字段格式问题

// 准备action测试数据 - 包含Jira格式的评论
$actionTable = zenData('action');
$actionTable->id->range('1-10');
$actionTable->objectType->range('story{3},bug{3},task{2},feedback{2}');
$actionTable->objectID->range('1-4');
$actionTable->action->range('commented,updated');
$actionTable->comment->range('Comment with !screenshot.png|image! reference,Updated with !document.pdf|file! attachment,Review comment with !design.jpg|image! feedback,,Action with !video.mp4|media! demonstration,Bug comment with !attachment.txt|file! log,,Feedback with !spreadsheet.xlsx|file! analysis,,Task comment with !archive.zip|file! resource');
$actionTable->gen(10);

// 准备自定义流程对象测试数据 - 手动创建表和数据
global $tester;
$tester->dao->exec("DROP TABLE IF EXISTS `zt_flow_customflow`");
$tester->dao->exec("CREATE TABLE IF NOT EXISTS `zt_flow_customflow` (`id` int(11) NOT NULL AUTO_INCREMENT, `customflowdesc` text, PRIMARY KEY (`id`))");
$tester->dao->exec("INSERT INTO `zt_flow_customflow` (`id`, `customflowdesc`) VALUES (1, 'Custom flow with !screenshot.png|image! attachment'), (2, 'Custom flow with !document.pdf|file! reference')");

su('admin');

$convertTest = new convertTest();

// 测试步骤1：处理包含Jira附件引用的story对象内容转换
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1)
))) && p() && e('1');

// 测试步骤2：处理包含Jira附件引用的bug对象步骤描述转换
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'abug', 'BID' => 1)
))) && p() && e('1');

// 测试步骤3：处理包含Jira附件引用的task对象描述转换
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atask', 'BID' => 1)
))) && p() && e('1');

// 测试步骤4：处理包含Jira附件引用的feedback对象描述转换
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'afeedback', 'BID' => 1)
))) && p() && e('1');

// 测试步骤5：处理多个不同类型对象的复合测试
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 7),
    (object)array('BType' => 'abug', 'BID' => 7)
))) && p() && e('1');

// 测试步骤6：处理包含Jira附件引用的action评论内容转换
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1),
    (object)array('BType' => 'abug', 'BID' => 2)
))) && p() && e('1');

// 测试步骤7：处理自定义流程对象的Jira内容转换
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'acustomflow', 'BID' => 1)
))) && p() && e('1');

// 测试步骤8：验证testcase类型对象的跳过处理逻辑
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atestcase', 'BID' => 1)
))) && p() && e('1');

// 测试步骤9：处理空输入数组的边界情况
r($convertTest->processJiraIssueContentTest(array())) && p() && e('1');

// 测试步骤10：处理无关联文件的对象的异常情况
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 2),
    (object)array('BType' => 'abug', 'BID' => 3)
))) && p() && e('1');