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
$fileTable->id->range('1-25');
$fileTable->title->range('image.png,document.pdf,attachment.txt,screenshot.jpg,readme.md,design.psd,test.docx,photo.gif,code.js,style.css,video.mp4,archive.zip,spreadsheet.xlsx,presentation.pptx,database.sql');
$fileTable->extension->range('png,pdf,txt,jpg,md,psd,docx,gif,js,css,mp4,zip,xlsx,pptx,sql');
$fileTable->objectType->range('story{10},bug{10},task{5}');
$fileTable->objectID->range('1-3');
$fileTable->gen(25);

// 准备story相关测试数据
$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->title->range('Story1,Story2,Story3,Story4,Story5,Story6,Story7,Story8,Story9,Story10');
$storyTable->gen(10);

$storySpecTable = zenData('storyspec');
$storySpecTable->story->range('1-10');
$storySpecTable->version->range('1');
$storySpecTable->title->range('Story Spec 1,Story Spec 2,Story Spec 3,Empty Story,Story with Jira Content,Story with Multiple Files,Story without Files,Special Story,Epic Story,Requirement Story');
$storySpecTable->spec->range('Content with !image.png|thumbnail! attachment,Another story with !document.pdf|attachment! file,Third story with !screenshot.jpg|thumb! image,,Content with !design.psd|image! and !readme.md|doc! files,Story with !video.mp4|media! and !archive.zip|file! attachments,Normal content without any files,Story with special characters and !code.js|file! attachment,Epic content with !spreadsheet.xlsx|file! data,Requirements with !presentation.pptx|file! document');
$storySpecTable->gen(10);

// 准备bug测试数据
$bugTable = zenData('bug');
$bugTable->id->range('1-10');
$bugTable->title->range('Bug Report 1,Bug Report 2,Bug Report 3,Empty Bug,Complex Bug,Critical Bug,Minor Bug,Duplicate Bug,Fixed Bug,Closed Bug');
$bugTable->steps->range('Bug steps with !screenshot.jpg|image! file,Another bug with !attachment.txt|file! attachment,Complex bug with !readme.md|doc! documentation,,Bug with !database.sql|file! and !code.js|file! files,Critical bug with !video.mp4|media! evidence,Simple bug without attachments,Bug with !design.psd|image! mockup,Fixed bug with !test.docx|doc! report,Closed bug with !style.css|file! fix');
$bugTable->gen(10);

// 准备task测试数据
$taskTable = zenData('task');
$taskTable->id->range('1-10');
$taskTable->name->range('Task 1,Task 2,Task 3,Empty Task,Development Task,Testing Task,Design Task,Documentation Task,Review Task,Deploy Task');
$taskTable->desc->range('Task with !design.psd|image! design,Task with !code.js|file! code,Task with !style.css|file! stylesheet,,Development task with !database.sql|file! and !archive.zip|file! resources,Testing task with !test.docx|doc! plan,Simple task without files,Documentation task with !readme.md|doc! guide,Code review with !presentation.pptx|file! slides,Deployment task with !spreadsheet.xlsx|file! configuration');
$taskTable->gen(10);

// 准备feedback测试数据
$feedbackTable = zenData('feedback');
$feedbackTable->id->range('1-5');
$feedbackTable->title->range('Feedback 1,Feedback 2,Feedback 3,Empty Feedback,User Feedback');
$feedbackTable->desc->range('Feedback with !image.png|image! screenshot,Feedback with !document.pdf|attachment! document,Empty feedback content,,User feedback with !presentation.pptx|file! suggestion');
$feedbackTable->gen(5);

// 准备testcase测试数据（会被跳过处理）
$testcaseTable = zenData('case');
$testcaseTable->id->range('1-5');
$testcaseTable->title->range('Test Case 1,Test Case 2,Test Case 3,Test Case 4,Test Case 5');
$testcaseTable->gen(5);

// 准备自定义流程测试数据
zenData('flow_customflow')->loadYaml('flow_customflow', false, 2)->gen(3);

// 准备action测试数据
$actionTable = zenData('action');
$actionTable->id->range('1-30');
$actionTable->objectType->range('story{10},bug{10},task{5},feedback{5}');
$actionTable->objectID->range('1-5');
$actionTable->action->range('created,edited,commented');
$actionTable->comment->range('Comment with !image.png|image! file,Comment with !document.pdf|attachment! document,Simple comment without files,,Comment with !video.mp4|media! demo');
$actionTable->gen(30);

su('admin');

$convertTest = new convertTest();

// 测试步骤1：测试正常的story类型Jira内容处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1)
))) && p() && e('1');

// 测试步骤2：测试正常的bug类型Jira内容处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'abug', 'BID' => 1)
))) && p() && e('1');

// 测试步骤3：测试正常的task类型Jira内容处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atask', 'BID' => 1)
))) && p() && e('1');

// 测试步骤4：测试正常的feedback类型Jira内容处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'afeedback', 'BID' => 1)
))) && p() && e('1');

// 测试步骤5：测试testcase类型被跳过的逻辑
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atestcase', 'BID' => 1)
))) && p() && e('1');

// 测试步骤6：测试自定义流程对象的Jira内容处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'acustomflow', 'BID' => 1)
))) && p() && e('1');

// 测试步骤7：测试epic类型和requirement类型的Jira内容处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'aepic', 'BID' => 1),
    (object)array('BType' => 'arequirement', 'BID' => 1)
))) && p() && e('1');

// 测试步骤8：测试空参数数组的边界情况
r($convertTest->processJiraIssueContentTest(array())) && p() && e('1');

// 测试步骤9：测试多种类型混合的批量处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 2),
    (object)array('BType' => 'abug', 'BID' => 2),
    (object)array('BType' => 'atask', 'BID' => 2),
    (object)array('BType' => 'afeedback', 'BID' => 2)
))) && p() && e('1');

// 测试步骤10：测试无关联文件的对象处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 7)
))) && p() && e('1');

// 测试步骤11：测试不存在的对象ID处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 999),
    (object)array('BType' => 'abug', 'BID' => 888),
    (object)array('BType' => 'atask', 'BID' => 777)
))) && p() && e('1');

// 测试步骤12：测试无效的BType参数处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'ainvalid', 'BID' => 1),
    (object)array('BType' => '', 'BID' => 1),
    (object)array('BType' => 'anotexist', 'BID' => 1)
))) && p() && e('1');