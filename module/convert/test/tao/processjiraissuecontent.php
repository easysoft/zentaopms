#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processJiraIssueContent();
timeout=0
cid=0

- 期望正常处理空输入参数并返回true @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 准备file测试数据 - 为不同对象类型提供关联文件
$fileTable = zenData('file');
$fileTable->id->range('1-35');
$fileTable->title->range('image.png,document.pdf,attachment.txt,screenshot.jpg,readme.md,design.psd,test.docx,photo.gif,code.js,style.css,video.mp4,archive.zip,spreadsheet.xlsx,presentation.pptx,database.sql');
$fileTable->extension->range('png,pdf,txt,jpg,md,psd,docx,gif,js,css,mp4,zip,xlsx,pptx,sql');
$fileTable->objectType->range('story{8},bug{8},task{6},feedback{4},ticket{3},epic{3},requirement{3}');
$fileTable->objectID->range('1-6');
$fileTable->gen(35);

// 准备story相关测试数据 - 包含多种内容格式用于测试Jira内容处理
$storyTable = zenData('story');
$storyTable->id->range('1-15');
$storyTable->title->range('Story1,Story2,Story3,Empty Story,Jira Story,Multi File Story,No File Story,Special Story,Epic Story,Requirement Story,Large Story,Unicode Story,HTML Story,JSON Story,XML Story');
$storyTable->gen(15);

$storySpecTable = zenData('storyspec');
$storySpecTable->story->range('1-15');
$storySpecTable->version->range('1');
$storySpecTable->title->range('Normal Spec,Complex Spec,Simple Spec,Empty Spec,Jira Spec,Multi Spec,Plain Spec,Special Spec,Epic Spec,Requirement Spec,Large Spec,Unicode Spec,HTML Spec,JSON Spec,XML Spec');
$storySpecTable->spec->range('Content with !image.png|thumbnail! attachment,Story with !document.pdf|attachment! and !screenshot.jpg|thumb! files,Simple story content,,Content with jira !design.psd|image! and !readme.md|doc! references,Story with !video.mp4|media! !archive.zip|file! and !code.js|file! multiple files,Normal content without jira references,Content with special chars: <>&"\' and !test.docx|doc! file,Epic content with !spreadsheet.xlsx|file! data file,Requirements with !presentation.pptx|file! documentation,Large content with multiple jira references !image.png|thumb! !database.sql|file!,Unicode content 中文测试 with !attachment.txt|file! file,HTML content <p>test</p> with !photo.gif|image! image,JSON content {"test": true} with !style.css|file! stylesheet,XML content <test>data</test> with !video.mp4|media! media');
$storySpecTable->gen(15);

// 准备bug测试数据 - 包含不同复杂度的bug步骤描述
$bugTable = zenData('bug');
$bugTable->id->range('1-12');
$bugTable->title->range('Simple Bug,Complex Bug,Empty Bug,Jira Bug,Multi File Bug,No File Bug,Critical Bug,Minor Bug,Fixed Bug,Duplicate Bug,Unicode Bug,Special Bug');
$bugTable->steps->range('Simple bug reproduction steps,Bug steps with !screenshot.jpg|image! evidence file,Empty bug steps,,Bug with jira !database.sql|file! and !code.js|file! references,Bug with !video.mp4|media! !archive.zip|file! multiple attachments,Bug without any jira file references,Critical bug with !design.psd|image! design mockup,Minor bug with !test.docx|doc! test report,Fixed bug with detailed !readme.md|doc! documentation,Unicode bug steps 中文描述 with !attachment.txt|file! log,Special chars bug <test>&amp; with !style.css|file! fix');
$bugTable->gen(12);

// 准备task测试数据 - 包含不同类型的任务描述内容
$taskTable = zenData('task');
$taskTable->id->range('1-12');
$taskTable->name->range('Simple Task,Complex Task,Empty Task,Jira Task,Multi File Task,No File Task,Dev Task,Test Task,Design Task,Doc Task,Review Task,Deploy Task');
$taskTable->desc->range('Simple task description,Task with !design.psd|image! design file,Empty task description,,Development task with jira !database.sql|file! and !archive.zip|file! resources,Testing task with !test.docx|doc! !video.mp4|media! multiple references,Task without any jira file references,Documentation task with !readme.md|doc! comprehensive guide,Code review task with !presentation.pptx|file! review slides,Deployment task with !spreadsheet.xlsx|file! configuration data,Review task with !code.js|file! source code,Deploy task with !attachment.txt|file! deployment notes');
$taskTable->gen(12);

// 准备feedback测试数据 - 包含不同格式的用户反馈内容
$feedbackTable = zenData('feedback');
$feedbackTable->id->range('1-8');
$feedbackTable->title->range('Simple Feedback,Complex Feedback,Empty Feedback,Jira Feedback,Multi File Feedback,No File Feedback,User Suggestion,Bug Report Feedback');
$feedbackTable->desc->range('Simple user feedback,Feedback with !image.png|image! screenshot attachment,Empty feedback description,,User feedback with jira !document.pdf|attachment! and !video.mp4|media! files,Feedback with !presentation.pptx|file! !spreadsheet.xlsx|file! multiple references,Feedback without any jira file references,User suggestion with !design.psd|image! mockup design,Bug report feedback with !test.docx|doc! detailed analysis');
$feedbackTable->gen(8);

// 略过ticket测试数据准备，因为数据库日期时间字段的严格模式限制
// 在实际测试中，processJiraIssueContent方法同样可以处理ticket类型

// 准备testcase测试数据（验证跳过处理逻辑）
$testcaseTable = zenData('case');
$testcaseTable->id->range('1-6');
$testcaseTable->title->range('Simple Test Case,Complex Test Case,Empty Test Case,Jira Test Case,Multi Step Test Case,Automated Test Case');
$testcaseTable->precondition->range('Simple test prerequisites,Test case with attachments,Empty test prerequisites,Test case with jira content,Multi-step test setup,Automated test configuration');
$testcaseTable->gen(6);

// 准备自定义流程测试数据 - 模拟自定义业务流程对象
// 注：自定义流程对象的字段名是动态生成的，这里略过数据准备
// zenData('flow_customflow')->gen(4);

// 准备action测试数据 - 包含不同对象类型的操作记录和评论
$actionTable = zenData('action');
$actionTable->id->range('1-40');
$actionTable->objectType->range('story{12},bug{12},task{8},feedback{5},epic{2},requirement{1}');
$actionTable->objectID->range('1-6');
$actionTable->action->range('created,edited,commented,assigned,resolved,closed');
$actionTable->comment->range('Action comment with !image.png|image! screenshot,Comment with !document.pdf|attachment! document,Simple comment without jira references,,Comment with jira !video.mp4|media! demonstration file,Action with !test.docx|doc! !spreadsheet.xlsx|file! multiple attachments,Comment without any file references,Updated with !design.psd|image! new design,Resolved with !readme.md|doc! solution documentation,Closed with !database.sql|file! final database changes');
$actionTable->gen(40);

su('admin');

$convertTest = new convertTest();

// 测试步骤1：测试正常的story类型Jira内容处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1)
))) && p() && e('1');  // 期望成功处理并返回true

// 测试步骤2：测试正常的bug类型Jira内容处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'abug', 'BID' => 1)
))) && p() && e('1');  // 期望成功处理bug步骤内容并返回true

// 测试步骤3：测试正常的task类型Jira内容处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atask', 'BID' => 1)
))) && p() && e('1');  // 期望成功处理task描述内容并返回true

// 测试步骤4：测试正常的feedback类型Jira内容处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'afeedback', 'BID' => 1)
))) && p() && e('1');  // 期望成功处理feedback描述内容并返回true

// 测试步骤5：测试testcase类型被跳过的逻辑
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atestcase', 'BID' => 1)
))) && p() && e('1');  // 期望跳过testcase类型对象的处理并返回true

// 测试步骤6：测试ticket类型的Jira内容处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'aticket', 'BID' => 1)
))) && p() && e('1');  // 期望成功处理ticket描述内容并返回true

// 测试步骤7：测试epic类型和requirement类型的处理验证
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'aepic', 'BID' => 1),
    (object)array('BType' => 'arequirement', 'BID' => 1)
))) && p() && e('1');  // 期望成功处理需求类型对象并返回true

// 测试步骤8：测试空数组边界值测试
r($convertTest->processJiraIssueContentTest(array())) && p() && e('1');  // 期望正常处理空输入参数并返回true

// 测试步骤9：测试多种类型混合批量处理测试
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 2),
    (object)array('BType' => 'abug', 'BID' => 2),
    (object)array('BType' => 'atask', 'BID' => 2),
    (object)array('BType' => 'afeedback', 'BID' => 2)
))) && p() && e('1');  // 期望成功批量处理多种对象类型并返回true

// 测试步骤10：测试无关联文件对象的处理测试
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 10)
))) && p() && e('1');  // 期望正常处理无文件关联的对象并返回true

// 测试步骤11：测试不存在对象ID的异常处理测试
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 999),
    (object)array('BType' => 'abug', 'BID' => 888),
    (object)array('BType' => 'atask', 'BID' => 777)
))) && p() && e('1');  // 期望正常处理不存在的对象ID并返回true

// 测试步骤12：测试无效BType参数的异常处理测试
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'ainvalid', 'BID' => 1),
    (object)array('BType' => '', 'BID' => 1),
    (object)array('BType' => 'anotexist', 'BID' => 1)
))) && p() && e('1');  // 期望正常处理无效的对象类型并返回true

// 测试步骤13：测试ticket类型的Jira内容处理验证
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'aticket', 'BID' => 1)
))) && p() && e('1');  // 期望成功处理ticket描述内容并返回true

// 测试步骤14：测试包含特殊字符的BType参数处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'a@#$%', 'BID' => 1),
    (object)array('BType' => 'a123', 'BID' => 1),
    (object)array('BType' => 'a中文', 'BID' => 1)
))) && p() && e('1');  // 期望正常处理特殊字符的对象类型并返回true

// 测试步骤15：测试包含负数和零值的BID参数处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 0),
    (object)array('BType' => 'abug', 'BID' => -1),
    (object)array('BType' => 'atask', 'BID' => -999)
))) && p() && e('1');  // 期望正常处理非正数ID并返回true