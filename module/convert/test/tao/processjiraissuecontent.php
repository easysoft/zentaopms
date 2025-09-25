#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processJiraIssueContent();
timeout=0
cid=0

- 执行convertTest模块的processJiraIssueContentTest方法，参数是$testData1  @1
- 执行convertTest模块的processJiraIssueContentTest方法，参数是$testData2  @1
- 执行convertTest模块的processJiraIssueContentTest方法，参数是$testData3  @1
- 执行convertTest模块的processJiraIssueContentTest方法，参数是$testData4  @1
- 执行convertTest模块的processJiraIssueContentTest方法，参数是$testData5  @1
- 执行convertTest模块的processJiraIssueContentTest方法，参数是$testData6  @1
- 执行convertTest模块的processJiraIssueContentTest方法，参数是$testData7  @1
- 执行convertTest模块的processJiraIssueContentTest方法，参数是$testData8  @1
- 执行convertTest模块的processJiraIssueContentTest方法，参数是$testData9  @1
- 执行convertTest模块的processJiraIssueContentTest方法，参数是$testData10  @1
- 执行convertTest模块的processJiraIssueContentTest方法，参数是$testData11  @1
- 执行convertTest模块的processJiraIssueContentTest方法，参数是$testData12  @1
- 执行convertTest模块的processJiraIssueContentTest方法，参数是$testData13  @1
- 执行convertTest模块的processJiraIssueContentTest方法，参数是$testData14  @1
- 执行convertTest模块的processJiraIssueContentTest方法，参数是$testData15  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 准备测试文件数据 - 涵盖各种对象类型和文件类型
$fileTable = zenData('file');
$fileTable->id->range('1-35');
$fileTable->title->range('screenshot.png,document.pdf,attachment.txt,design.jpg,readme.md,video.mp4,archive.zip,spreadsheet.xlsx,presentation.pptx,database.sql,image_with_spaces.png,special-chars@file.txt,测试文件.docx,empty.txt,large_file.zip');
$fileTable->extension->range('png,pdf,txt,jpg,md,mp4,zip,xlsx,pptx,sql,png,txt,docx,txt,zip');
$fileTable->objectType->range('story{10},bug{10},task{8},feedback{4},ticket{2},customflow{1}');
$fileTable->objectID->range('1-5');
$fileTable->gen(35);

// 准备story测试数据 - 包含各种Jira内容格式
$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->title->range('Story with Jira content,Empty story,Story without files,Complex story,Epic story,Requirement story,Multiple attachments story,Single attachment story,Special chars story,Unicode story');
$storyTable->gen(10);

$storySpecTable = zenData('storyspec');
$storySpecTable->story->range('1-10');
$storySpecTable->version->range('1');
$storySpecTable->title->range('Spec1,Spec2,Spec3,Spec4,Spec5,Spec6,Spec7,Spec8,Spec9,Spec10');
$storySpecTable->spec->range('Story with !screenshot.png|thumbnail! and !document.pdf|attachment! files,,,Story with !design.jpg|image! design,Epic with !video.mp4|media! demo,Requirement with !readme.md|doc! specification,Complex story with !archive.zip|file! !presentation.pptx|file! !database.sql|file! attachments,Single story with !attachment.txt|file! only,Story with !special-chars@file.txt|file! special chars,Story with !测试文件.docx|doc! unicode content');
$storySpecTable->gen(10);

// 准备bug测试数据 - 包含Jira格式的步骤描述
$bugTable = zenData('bug');
$bugTable->id->range('1-10');
$bugTable->title->range('Bug with screenshot,Empty bug,Simple bug,Complex bug,Critical bug,Fixed bug,Multi-attachment bug,Media bug,Invalid attachment bug,No content bug');
$bugTable->steps->range('Reproduce with !screenshot.png|image! evidence,,,Bug with !attachment.txt|file! log file,Critical issue with !database.sql|file! backup,Fixed with !presentation.pptx|file! analysis,Multiple attachments: !screenshot.png|image! !document.pdf|file! !design.jpg|image!,Video bug with !video.mp4|media! reproduction,Bug with !nonexistent.txt|file! invalid reference,');
$bugTable->gen(10);

// 准备task测试数据 - 包含Jira格式的任务描述
$taskTable = zenData('task');
$taskTable->id->range('1-8');
$taskTable->name->range('Development task,Empty task,Documentation task,Review task,Complex task,Media task,Edge case task,Unicode task');
$taskTable->desc->range('Task with !archive.zip|file! resources,,,Documentation with !readme.md|doc! guide,Review with !spreadsheet.xlsx|file! data,Complex task with !screenshot.png|image! !document.pdf|file! attachments,Media task with !video.mp4|media! demo,Task with !image_with_spaces.png|image! spaced filename,Task with !测试文件.docx|doc! unicode filename');
$taskTable->gen(8);

// 准备feedback测试数据 - 包含Jira格式的反馈描述
$feedbackTable = zenData('feedback');
$feedbackTable->id->range('1-4');
$feedbackTable->title->range('User feedback,Bug report,Feature request,Complex feedback');
$feedbackTable->desc->range('Feedback with !screenshot.png|image! screenshot,Report with !document.pdf|attachment! details,Feature request with !design.jpg|image! mockup,Complex feedback with !large_file.zip|file! attachment');
$feedbackTable->gen(4);

// 准备testcase测试数据 - 验证跳过逻辑
$testcaseTable = zenData('case');
$testcaseTable->id->range('1-2');
$testcaseTable->title->range('Test case 1,Test case 2');
$testcaseTable->precondition->range('Prerequisites,Setup with attachments');
$testcaseTable->gen(2);

// 跳过ticket测试数据生成，因为日期字段格式问题

// 准备action测试数据 - 包含Jira格式的评论
$actionTable = zenData('action');
$actionTable->id->range('1-15');
$actionTable->objectType->range('story{5},bug{5},task{3},feedback{2}');
$actionTable->objectID->range('1-5');
$actionTable->action->range('commented,updated,created');
$actionTable->comment->range('Comment with !screenshot.png|image! reference,Updated with !document.pdf|file! attachment,Review comment with !design.jpg|image! feedback,,Action with !video.mp4|media! demonstration,Bug comment with !attachment.txt|file! log,,Feedback with !spreadsheet.xlsx|file! analysis,,Task comment with !archive.zip|file! resource,Comment with !special-chars@file.txt|file! special chars,Updated with !测试文件.docx|doc! unicode,Comment with !nonexistent.jpg|image! missing file,,Action with !empty.txt|file! empty file');
$actionTable->gen(15);

// 准备自定义流程对象测试数据 - 手动创建表和数据
global $tester;
$tester->dao->exec("DROP TABLE IF EXISTS `zt_flow_customflow`");
$tester->dao->exec("CREATE TABLE IF NOT EXISTS `zt_flow_customflow` (`id` int(11) NOT NULL AUTO_INCREMENT, `customflowdesc` text, PRIMARY KEY (`id`))");
$tester->dao->exec("INSERT INTO `zt_flow_customflow` (`id`, `customflowdesc`) VALUES (1, 'Custom flow with !screenshot.png|image! attachment'), (2, 'Custom flow with !document.pdf|file! reference')");

su('admin');

$convertTest = new convertTest();

// 测试步骤1：处理包含Jira附件引用的story对象内容转换
$testData1 = array(
    (object)array('BType' => 'astory', 'BID' => 1)
);
r($convertTest->processJiraIssueContentTest($testData1)) && p() && e('1');

// 测试步骤2：处理包含Jira附件引用的bug对象步骤描述转换
$testData2 = array(
    (object)array('BType' => 'abug', 'BID' => 1)
);
r($convertTest->processJiraIssueContentTest($testData2)) && p() && e('1');

// 测试步骤3：处理包含Jira附件引用的task对象描述转换
$testData3 = array(
    (object)array('BType' => 'atask', 'BID' => 1)
);
r($convertTest->processJiraIssueContentTest($testData3)) && p() && e('1');

// 测试步骤4：处理包含Jira附件引用的feedback对象描述转换
$testData4 = array(
    (object)array('BType' => 'afeedback', 'BID' => 1)
);
r($convertTest->processJiraIssueContentTest($testData4)) && p() && e('1');

// 测试步骤5：处理多个不同类型对象的批量转换
$testData5 = array(
    (object)array('BType' => 'astory', 'BID' => 7),
    (object)array('BType' => 'abug', 'BID' => 7),
    (object)array('BType' => 'atask', 'BID' => 5)
);
r($convertTest->processJiraIssueContentTest($testData5)) && p() && e('1');

// 测试步骤6：处理不存在对象ID的异常情况
$testData6 = array(
    (object)array('BType' => 'astory', 'BID' => 999),
    (object)array('BType' => 'abug', 'BID' => 888)
);
r($convertTest->processJiraIssueContentTest($testData6)) && p() && e('1');

// 测试步骤7：处理testcase类型对象的跳过逻辑验证
$testData7 = array(
    (object)array('BType' => 'atestcase', 'BID' => 1),
    (object)array('BType' => 'atestcase', 'BID' => 2)
);
r($convertTest->processJiraIssueContentTest($testData7)) && p() && e('1');

// 测试步骤8：处理自定义流程对象的Jira内容转换
$testData8 = array(
    (object)array('BType' => 'acustomflow', 'BID' => 1),
    (object)array('BType' => 'acustomflow', 'BID' => 2)
);
r($convertTest->processJiraIssueContentTest($testData8)) && p() && e('1');

// 测试步骤9：处理空输入数组的边界情况测试
$testData9 = array();
r($convertTest->processJiraIssueContentTest($testData9)) && p() && e('1');

// 测试步骤10：处理包含action评论的Jira内容转换
$testData10 = array(
    (object)array('BType' => 'astory', 'BID' => 1),
    (object)array('BType' => 'abug', 'BID' => 2),
    (object)array('BType' => 'atask', 'BID' => 3)
);
r($convertTest->processJiraIssueContentTest($testData10)) && p() && e('1');

// 测试步骤11：处理包含特殊字符文件名的转换
$testData11 = array(
    (object)array('BType' => 'astory', 'BID' => 9),
    (object)array('BType' => 'atask', 'BID' => 7)
);
r($convertTest->processJiraIssueContentTest($testData11)) && p() && e('1');

// 测试步骤12：处理包含Unicode文件名的转换
$testData12 = array(
    (object)array('BType' => 'astory', 'BID' => 10),
    (object)array('BType' => 'atask', 'BID' => 8)
);
r($convertTest->processJiraIssueContentTest($testData12)) && p() && e('1');

// 测试步骤13：处理包含无效附件引用的内容转换
$testData13 = array(
    (object)array('BType' => 'abug', 'BID' => 9)
);
r($convertTest->processJiraIssueContentTest($testData13)) && p() && e('1');

// 测试步骤14：处理无关联文件的对象
$testData14 = array(
    (object)array('BType' => 'astory', 'BID' => 2),
    (object)array('BType' => 'abug', 'BID' => 3),
    (object)array('BType' => 'abug', 'BID' => 10)
);
r($convertTest->processJiraIssueContentTest($testData14)) && p() && e('1');

// 测试步骤15：处理混合有效和无效对象ID的情况
$testData15 = array(
    (object)array('BType' => 'astory', 'BID' => 1),
    (object)array('BType' => 'astory', 'BID' => 999),
    (object)array('BType' => 'abug', 'BID' => 1),
    (object)array('BType' => 'atask', 'BID' => 888)
);
r($convertTest->processJiraIssueContentTest($testData15)) && p() && e('1');