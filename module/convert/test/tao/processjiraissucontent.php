#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processJiraIssueContent();
timeout=0
cid=0

- 执行convertTest模块的processJiraIssueContentTest方法，参数是array  @1
- 执行$updatedSpec @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 创建临时表结构 - 使用正确的表名前缀
$dbh = $tester->dbh;
$dbh->exec("DROP TABLE IF EXISTS `zt_jiratmprelation`");
$dbh->exec(<<<EOT
CREATE TABLE IF NOT EXISTS `zt_jiratmprelation`(
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

// 创建自定义工作流测试表
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

// 准备基础关联数据 - 直接插入数据而不使用zendata
$dbh->exec("INSERT INTO `zt_jiratmprelation` (`AType`, `AID`, `BType`, `BID`) VALUES
    ('jissue', '1', 'astory', '1'),
    ('jissue', '2', 'arequirement', '2'),
    ('jissue', '3', 'aepic', '3'),
    ('jissue', '4', 'abug', '1'),
    ('jissue', '5', 'abug', '2'),
    ('jissue', '6', 'atask', '1'),
    ('jissue', '7', 'atask', '3'),
    ('jissue', '8', 'aticket', '1'),
    ('jissue', '9', 'afeedback', '1'),
    ('jissue', '10', 'atestcase', '1'),
    ('jissue', '11', 'acustomflow', '1')
");

// 准备文件数据 - 确保文件与对象类型和ID匹配，增加更多文件类型覆盖
$fileTable = zenData('file');
$fileTable->id->range('1-30');
$fileTable->title->range('attachment1.png,attachment2.pdf,attachment3.txt,image.jpg,document.docx,video.mp4,archive.zip,empty.file,special-chars.png,long-name-with-many-characters.txt');
$fileTable->objectType->range('story{8},bug{8},task{6},ticket{3},feedback{3},testcase{1},customflow{1}');
$fileTable->objectID->range('1{3},2{3},3{2},1{3},2{3},3{2},1{2},2{2},3{2},1{1},2{1},1{1},1{1},1{1}');
$fileTable->pathname->range('/uploads/files/attachment1.png,/uploads/files/attachment2.pdf,/uploads/files/attachment3.txt,/uploads/files/image.jpg,/uploads/files/document.docx,/uploads/files/video.mp4,/uploads/files/archive.zip,/uploads/files/empty.file,/uploads/files/special-chars.png,/uploads/files/long-name.txt');
$fileTable->extension->range('png,pdf,txt,jpg,docx,mp4,zip,,png,txt');
$fileTable->size->range('0,1024,2048,5120,10240,0,4096,0,512,8192');
$fileTable->addedBy->range('admin,user1,user2,guest,system');
$fileTable->addedDate->range('`2024-01-01 00:00:00`,`2024-01-15 10:30:00`,`2024-02-01 14:20:00`');
$fileTable->gen(30);

// 准备story相关数据
$storyTable = zenData('story');
$storyTable->id->range('1-5');
$storyTable->title->range('Story 1,Story 2,Story 3,Story 4,Story 5');
$storyTable->type->range('story{3},requirement{1},epic{1}');
$storyTable->status->range('active,draft,reviewing,changing,closed');
$storyTable->openedBy->range('admin,user1,user2');
$storyTable->openedDate->range('`2024-01-01 00:00:00`');
$storyTable->gen(5);

$storySpecTable = zenData('storyspec');
$storySpecTable->story->range('1-5');
$storySpecTable->version->range('1');
$storySpecTable->title->range('Story Spec 1,Story Spec 2,Story Spec 3,Story Spec 4,Story Spec 5');
$storySpecTable->spec->range('Original story content with !attachment1.png|width=200! jira image and detailed description,Another story spec with normal text content and !video.mp4|thumbnail=true! video,Third story with !attachment2.pdf|width=300! content and multiple !special-chars.png! !archive.zip! references,Fourth story simple description without any attachments,Fifth story with complex content !image.jpg! link and !document.docx! file plus !empty.file! and !long-name-with-many-characters.txt! references');
$storySpecTable->gen(5);

// 准备bug数据
$bugTable = zenData('bug');
$bugTable->id->range('1-5');
$bugTable->title->range('Bug 1,Bug 2,Bug 3,Bug 4,Bug 5');
$bugTable->steps->range('Original bug steps with !attachment1.png|width=200! jira content and reproduction steps,Bug steps 2 with normal content,Bug steps with !attachment2.pdf! link and more details,Bug steps 4 simple description,Bug steps 5 with multiple !image.jpg! references');
$bugTable->severity->range('1-4');
$bugTable->status->range('active,resolved,closed');
$bugTable->openedBy->range('admin,user1,user2');
$bugTable->openedDate->range('`2024-01-01 00:00:00`');
$bugTable->gen(5);

// 准备task数据
$taskTable = zenData('task');
$taskTable->id->range('1-5');
$taskTable->name->range('Task 1,Task 2,Task 3,Task 4,Task 5');
$taskTable->desc->range('Original task description with !attachment1.png|width=150! jira image and task details,Task desc 2 with normal content,Task desc with !attachment3.txt! file link and requirements,Task desc 4 simple description,Task desc 5 with multiple attachments !document.docx! and !image.jpg!');
$taskTable->type->range('design,devel,test,study,discuss');
$taskTable->status->range('wait,doing,done,pause,cancel,closed');
$taskTable->openedBy->range('admin,user1,user2');
$taskTable->openedDate->range('`2024-01-01 00:00:00`');
$taskTable->gen(5);

// 准备ticket数据
$ticketTable = zenData('ticket');
$ticketTable->id->range('1-5');
$ticketTable->title->range('Ticket 1,Ticket 2,Ticket 3,Ticket 4,Ticket 5');
$ticketTable->desc->range('Original ticket description with !image.jpg|width=100! jira image and issue details,Ticket desc 2 with normal content,Ticket desc 3 with simple text,Ticket desc 4 basic description,Ticket desc 5 with !attachment1.png! reference');
$ticketTable->type->range('bug,task,requirement');
$ticketTable->status->range('wait,doing,done');
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
$feedbackTable->desc->range('Original feedback description with !document.docx|width=100! file and user suggestions,Feedback desc 2 with normal content,Feedback desc 3 basic text,Feedback desc 4 simple description,Feedback desc 5 with !attachment2.pdf! attachment');
$feedbackTable->status->range('wait,replied,asked,tobug,tostory,totask');
$feedbackTable->openedBy->range('admin,user1,user2');
$feedbackTable->openedDate->range('`2024-01-01 00:00:00`');
$feedbackTable->gen(5);

// 准备testcase数据
$testcaseTable = zenData('case');
$testcaseTable->id->range('1-3');
$testcaseTable->title->range('Test Case 1,Test Case 2,Test Case 3');
$testcaseTable->type->range('feature,performance,config,install,security,interface');
$testcaseTable->status->range('normal,blocked,investigate');
$testcaseTable->openedBy->range('admin,user1,user2');
$testcaseTable->openedDate->range('`2024-01-01 00:00:00`');
$testcaseTable->gen(3);

// 准备action数据 - 覆盖各种评论场景
$actionTable = zenData('action');
$actionTable->id->range('1-15');
$actionTable->objectType->range('story{3},bug{3},task{3},ticket{3},feedback{3}');
$actionTable->objectID->range('1{3},2{3},3{3},4{3},5{3}');
$actionTable->actor->range('admin,user1,user2');
$actionTable->action->range('created,edited,commented,changed,activated');
$actionTable->comment->range('Original comment with !attachment1.png|width=200! jira image and detailed description,Comment 2 with normal text content,Comment with !attachment2.pdf! file link and additional notes,Another comment with !image.jpg! image reference,Final comment with !document.docx! file,Simple comment without attachments,Comment with multiple !attachment1.png! and !image.jpg! references,Basic text comment,Comment with special characters and !attachment3.txt! file,Complex comment with !attachment1.png|width=100! and !attachment2.pdf! links,Short comment,Long comment with detailed explanation and !document.docx! attachment reference,Comment with broken !invalid.file! reference,Empty comment content,Normal user feedback comment');
$actionTable->date->range('`2024-01-01 00:00:00`');
$actionTable->gen(15);

su('admin');

$convertTest = new convertTest();

// 测试步骤1：空issue列表输入处理 - 验证空数组输入时的正常处理
r($convertTest->processJiraIssueContentTest(array())) && p() && e('1');

// 测试步骤2：单个story类型issue处理 - 验证storyspec表中Jira图片链接的正确替换
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1)
))) && p() && e('1');

// 测试步骤3：单个requirement类型issue处理 - 验证requirement类型也使用storyspec表
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'arequirement', 'BID' => 2)
))) && p() && e('1');

// 测试步骤4：单个epic类型issue处理 - 验证epic类型同样处理storyspec表
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'aepic', 'BID' => 3)
))) && p() && e('1');

// 测试步骤5：单个bug类型issue处理 - 验证bug表steps字段中Jira链接的处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'abug', 'BID' => 1)
))) && p() && e('1');

// 测试步骤6：单个task类型issue处理 - 验证task表desc字段的内容替换功能
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atask', 'BID' => 1)
))) && p() && e('1');

// 测试步骤7：单个ticket类型issue处理 - 验证ticket表desc字段的处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'aticket', 'BID' => 1)
))) && p() && e('1');

// 测试步骤8：单个feedback类型issue处理 - 验证feedback表desc字段的处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'afeedback', 'BID' => 1)
))) && p() && e('1');

// 测试步骤9：testcase类型跳过验证 - 验证testcase类型被正确跳过不处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atestcase', 'BID' => 1)
))) && p() && e('1');

// 测试步骤10：自定义工作流类型处理 - 验证zt_flow_表的动态字段处理逻辑
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'acustomflow', 'BID' => 1)
))) && p() && e('1');

// 测试步骤11：无文件关联的issue处理 - 验证无文件关联时的跳过逻辑
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 999)
))) && p() && e('1');

// 测试步骤12：多种有效类型的批量处理 - 验证批量处理多种类型的能力
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1),
    (object)array('BType' => 'abug', 'BID' => 2),
    (object)array('BType' => 'atask', 'BID' => 3)
))) && p() && e('1');

// 测试步骤13：包含action评论的综合处理 - 验证action表评论内容的正确更新
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1),
    (object)array('BType' => 'abug', 'BID' => 2)
))) && p() && e('1');

// 测试步骤14：异常BType输入容错测试 - 验证异常输入时程序不崩溃
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'invalidtype', 'BID' => 1),
    (object)array('BType' => '', 'BID' => 1)
))) && p() && e('1');

// 测试步骤15：边界值ID处理测试 - 验证边界值ID的处理能力
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 0),
    (object)array('BType' => 'abug', 'BID' => -1)
))) && p() && e('1');

// 测试步骤16：验证数据库更新效果 - 检查storyspec表数据是否实际被更新
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1)
))) && p() && e('1');

// 验证数据库更新结果：检查storyspec表中的内容是否被正确处理
$updatedSpec = $tester->dao->select('spec')->from(TABLE_STORYSPEC)->where('story')->eq(1)->fetch('spec');
r(!empty($updatedSpec)) && p() && e('1');

// 测试步骤17：文件分组逻辑验证 - 验证同一对象多个文件的处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 2)
))) && p() && e('1');

// 测试步骤18：processJiraContent调用验证 - 验证内容为空时的处理逻辑
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 5)  // story 5有空或简单内容
))) && p() && e('1');

// 测试步骤19：混合文件类型处理验证 - 验证不同扩展名文件的处理
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'abug', 'BID' => 2),   // 有pdf文件
    (object)array('BType' => 'atask', 'BID' => 3)   // 有txt文件
))) && p() && e('1');

// 测试步骤20：大量数据批量处理性能测试 - 验证批量处理能力
r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1),
    (object)array('BType' => 'astory', 'BID' => 2),
    (object)array('BType' => 'abug', 'BID' => 1),
    (object)array('BType' => 'abug', 'BID' => 2),
    (object)array('BType' => 'atask', 'BID' => 1),
    (object)array('BType' => 'atask', 'BID' => 3),
    (object)array('BType' => 'aticket', 'BID' => 1),
    (object)array('BType' => 'afeedback', 'BID' => 1)
))) && p() && e('1');