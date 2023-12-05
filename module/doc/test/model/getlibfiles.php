#!/usr/bin/env php
<?php
/**

title=测试 docModel->getLibFiles();
timeout=0
cid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

$storyTable = zdTable('story');
$storyTable->product->range('1-5');
$storyTable->gen(20);

$planTable = zdTable('productplan');
$planTable->product->range('1-5');
$planTable->gen(20);

$releaseTable = zdTable('release');
$releaseTable->product->range('1-5');
$releaseTable->gen(20);

$caseTable = zdTable('case');
$caseTable->product->range('1-5');
$caseTable->gen(20);

$projectstoryTable = zdTable('projectstory');
$projectstoryTable->project->range('11,60,61,100,101-110');
$projectstoryTable->gen(20);

$taskTable = zdTable('task');
$taskTable->execution->range('101-110');
$taskTable->gen(20);

$buildTable = zdTable('build');
$buildTable->execution->range('101-110');
$buildTable->gen(20);

$issuetable = zdtable('issue');
$issuetable->project->range('11, 60, 61, 100');
$issuetable->gen(20);

$meetingTable = zdTable('meeting');
$meetingTable->project->range('11, 60, 61, 100');
$meetingTable->gen(20);

$reviewTable = zdTable('review');
$reviewTable->project->range('11, 60, 61, 100');
$reviewTable->gen(20);

$designTable = zdTable('design');
$designTable->project->range('11, 60, 61, 100');
$designTable->gen(20);

zdTable('product')->config('product')->gen(5);
zdTable('project')->config('execution')->gen(10);
zdTable('file')->gen(50);
zdTable('user')->gen(5);
su('admin');

$types       = array('all', 'product', 'project', 'execution');
$products    = array(0 ,1);
$projects    = array(0, 11);
$executions  = array(0, 101);
$searchTitle = array(false, '文件');

$docTester = new docTest();
r($docTester->getLibFilesTest($types[0], $products[0], $searchTitle[0]))   && p()                               && e('0');                      // 测试空数据
r($docTester->getLibFilesTest($types[1], $products[0], $searchTitle[0]))   && p()                               && e('0');                      // 获取关联产品ID=0数据的附件
r($docTester->getLibFilesTest($types[1], $products[1], $searchTitle[0]))   && p('16:title,objectType,objectID') && e('文件标题16,testcase,16'); // 获取关联产品ID=1数据的附件
r($docTester->getLibFilesTest($types[1], $products[1], $searchTitle[1]))   && p('16:title,objectType,objectID') && e('文件标题16,testcase,16'); // 获取关联产品ID=1且名称中包含“文件”的附件
r($docTester->getLibFilesTest($types[2], $projects[0], $searchTitle[0]))   && p('13:title,objectType,objectID') && e('文件标题13,task,13');     // 获取关联项目ID=0数据的附件
r($docTester->getLibFilesTest($types[2], $projects[1], $searchTitle[0]))   && p('1:title,objectType,objectID')  && e('文件标题1,task,1');       // 获取关联项目ID=11数据的附件
r($docTester->getLibFilesTest($types[2], $projects[1], $searchTitle[1]))   && p('1:title,objectType,objectID')  && e('文件标题1,task,1');       // 获取关联项目ID=11且名称中包含“文件”的附件
r($docTester->getLibFilesTest($types[3], $executions[0], $searchTitle[0])) && p()                               && e('0');                      // 获取关联执行ID=0数据的附件
r($docTester->getLibFilesTest($types[3], $executions[1], $searchTitle[0])) && p('1:title,objectType,objectID')  && e('文件标题1,task,1');       // 获取关联执行ID=101数据的附件
r($docTester->getLibFilesTest($types[3], $executions[1], $searchTitle[1])) && p('1:title,objectType,objectID')  && e('文件标题1,task,1');       // 获取关联执行ID=101且名称中包含“文件”的附件
