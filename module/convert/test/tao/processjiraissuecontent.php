#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processJiraIssueContent();
timeout=0
cid=0

- 期望正常处理空数组并返回true @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 准备测试文件数据 - 涵盖各种对象类型和文件类型
$fileTable = zenData('file');
$fileTable->id->range('1-20');
$fileTable->title->range('screenshot.png,document.pdf,attachment.txt,design.jpg,readme.md,video.mp4,archive.zip,spreadsheet.xlsx,presentation.pptx,database.sql');
$fileTable->extension->range('png,pdf,txt,jpg,md,mp4,zip,xlsx,pptx,sql');
$fileTable->objectType->range('story{6},bug{6},task{4},feedback{2},epic{1},requirement{1}');
$fileTable->objectID->range('1-3');
$fileTable->gen(20);

// 准备story测试数据 - 包含各种Jira内容格式
$storyTable = zenData('story');
$storyTable->id->range('1-6');
$storyTable->title->range('Story with Jira content,Empty story,Story without files,Complex story,Epic story,Requirement story');
$storyTable->gen(6);

$storySpecTable = zenData('storyspec');
$storySpecTable->story->range('1-6');
$storySpecTable->version->range('1');
$storySpecTable->title->range('Spec1,Spec2,Spec3,Spec4,Spec5,Spec6');
$storySpecTable->spec->range('Story with !screenshot.png|thumbnail! and !document.pdf|attachment! files,,,Story with !design.jpg|image! design,Epic with !video.mp4|media! demo,Requirement with !readme.md|doc! specification');
$storySpecTable->gen(6);

// 准备bug测试数据 - 包含Jira格式的步骤描述
$bugTable = zenData('bug');
$bugTable->id->range('1-6');
$bugTable->title->range('Bug with screenshot,Empty bug,Simple bug,Complex bug,Critical bug,Fixed bug');
$bugTable->steps->range('Reproduce with !screenshot.png|image! evidence,,,Bug with !attachment.txt|file! log file,Critical issue with !database.sql|file! backup,Fixed with !presentation.pptx|file! analysis');
$bugTable->gen(6);

// 准备task测试数据 - 包含Jira格式的任务描述
$taskTable = zenData('task');
$taskTable->id->range('1-4');
$taskTable->name->range('Development task,Empty task,Documentation task,Review task');
$taskTable->desc->range('Task with !archive.zip|file! resources,,,Documentation with !readme.md|doc! guide,Review with !spreadsheet.xlsx|file! data');
$taskTable->gen(4);

// 准备feedback测试数据 - 包含Jira格式的反馈描述
$feedbackTable = zenData('feedback');
$feedbackTable->id->range('1-2');
$feedbackTable->title->range('User feedback,Bug report');
$feedbackTable->desc->range('Feedback with !screenshot.png|image! screenshot,Report with !document.pdf|attachment! details');
$feedbackTable->gen(2);

// 准备testcase测试数据 - 验证跳过逻辑
$testcaseTable = zenData('case');
$testcaseTable->id->range('1-2');
$testcaseTable->title->range('Test case 1,Test case 2');
$testcaseTable->precondition->range('Prerequisites,Setup with attachments');
$testcaseTable->gen(2);

su('admin');

$convertTest = new convertTest();

// 测试步骤1：处理包含Jira格式内容的story对象
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1)
))) && p() && e('1');  // 期望成功转换story中的Jira文件引用并返回true

// 测试步骤2：处理包含Jira格式内容的bug对象
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'abug', 'BID' => 1)
))) && p() && e('1');  // 期望成功转换bug步骤中的Jira文件引用并返回true

// 测试步骤3：处理包含Jira格式内容的task对象
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atask', 'BID' => 1)
))) && p() && e('1');  // 期望成功转换task描述中的Jira文件引用并返回true

// 测试步骤4：处理包含Jira格式内容的feedback对象
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'afeedback', 'BID' => 1)
))) && p() && e('1');  // 期望成功转换feedback描述中的Jira文件引用并返回true

// 测试步骤5：处理testcase类型对象的跳过逻辑
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atestcase', 'BID' => 1)
))) && p() && e('1');  // 期望跳过testcase类型处理并返回true

// 测试步骤6：处理空输入数组的边界情况
r($convertTest->processJiraIssueContentTest(array())) && p() && e('1');  // 期望正常处理空数组并返回true

// 测试步骤7：处理无效BType参数的异常情况
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'ainvalid', 'BID' => 1),
    (object)array('BType' => '', 'BID' => 1)
))) && p() && e('1');  // 期望正常处理无效的对象类型并返回true

// 测试步骤8：处理不存在对象ID的异常情况
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 999),
    (object)array('BType' => 'abug', 'BID' => 888)
))) && p() && e('1');  // 期望正常处理不存在的对象ID并返回true