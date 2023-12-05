#!/usr/bin/env php
<?php
/**

title=测试 docModel->getLinkedObjectData();
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
zdTable('user')->gen(5);
su('admin');

$types      = array('all', 'product', 'project', 'execution');
$products   = array(0 ,1, 2);
$projects   = array(0, 11, 60);
$editions   = array('open', 'max', 'ipd');
$executions = array(0, 101, 106);

$docTester = new docTest();
r($docTester->getLinkedObjectDataTest($types[0], $products[0], $editions[0]))   && p()          && e('0');                                                                        // 测试传入错误的type
r($docTester->getLinkedObjectDataTest($types[1], $products[0], $editions[0]))   && p('4')       && e("SELECT id FROM `zt_story` WHERE `product`  = '0' AND  `deleted`  = '0'");   // 获取开源版关联产品ID=0的数据
r($docTester->getLinkedObjectDataTest($types[1], $products[1], $editions[0]))   && p('4')       && e("SELECT id FROM `zt_story` WHERE `product`  = '1' AND  `deleted`  = '0'");   // 获取开源版关联产品ID=1的数据
r($docTester->getLinkedObjectDataTest($types[1], $products[2], $editions[0]))   && p('4')       && e("SELECT id FROM `zt_story` WHERE `product`  = '2' AND  `deleted`  = '0'");   // 获取开源版关联产品ID=2的数据
r($docTester->getLinkedObjectDataTest($types[1], $products[0], $editions[1]))   && p('4')       && e("SELECT id FROM `zt_story` WHERE `product`  = '0' AND  `deleted`  = '0'");   // 获取旗舰版关联产品ID=0的数据
r($docTester->getLinkedObjectDataTest($types[1], $products[1], $editions[1]))   && p('4')       && e("SELECT id FROM `zt_story` WHERE `product`  = '1' AND  `deleted`  = '0'");   // 获取旗舰版关联产品ID=1的数据
r($docTester->getLinkedObjectDataTest($types[1], $products[2], $editions[1]))   && p('4')       && e("SELECT id FROM `zt_story` WHERE `product`  = '2' AND  `deleted`  = '0'");   // 获取旗舰版关联产品ID=2的数据
r($docTester->getLinkedObjectDataTest($types[1], $products[0], $editions[2]))   && p('4')       && e("SELECT id FROM `zt_story` WHERE `product`  = '0' AND  `deleted`  = '0'");   // 获取IPD版关联产品ID=0的数据
r($docTester->getLinkedObjectDataTest($types[1], $products[1], $editions[2]))   && p('4')       && e("SELECT id FROM `zt_story` WHERE `product`  = '1' AND  `deleted`  = '0'");   // 获取IPD版关联产品ID=1的数据
r($docTester->getLinkedObjectDataTest($types[1], $products[2], $editions[2]))   && p('4')       && e("SELECT id FROM `zt_story` WHERE `product`  = '2' AND  `deleted`  = '0'");   // 获取IPD版关联产品ID=2的数据
r($docTester->getLinkedObjectDataTest($types[2], $projects[0], $editions[0]))   && p('11')      && e("SELECT id FROM `zt_design` WHERE `project`  = '0' AND  `deleted`  = '0'");  // 获取开源版关联项目ID=0的数据
r($docTester->getLinkedObjectDataTest($types[2], $projects[1], $editions[0]))   && p('11')      && e("SELECT id FROM `zt_design` WHERE `project`  = '11' AND  `deleted`  = '0'"); // 获取开源版关联项目ID=11的数据
r($docTester->getLinkedObjectDataTest($types[2], $projects[2], $editions[0]))   && p('11')      && e("SELECT id FROM `zt_design` WHERE `project`  = '60' AND  `deleted`  = '0'"); // 获取开源版关联项目ID=60的数据
r($docTester->getLinkedObjectDataTest($types[2], $projects[0], $editions[1]))   && p('11')      && e("SELECT id FROM `zt_design` WHERE `project`  = '0' AND  `deleted`  = '0'");  // 获取旗舰版关联项目ID=0的数据
r($docTester->getLinkedObjectDataTest($types[2], $projects[1], $editions[1]))   && p('11')      && e("SELECT id FROM `zt_design` WHERE `project`  = '11' AND  `deleted`  = '0'"); // 获取旗舰版关联项目ID=11的数据
r($docTester->getLinkedObjectDataTest($types[2], $projects[2], $editions[1]))   && p('11')      && e("SELECT id FROM `zt_design` WHERE `project`  = '60' AND  `deleted`  = '0'"); // 获取旗舰版关联项目ID=60的数据
r($docTester->getLinkedObjectDataTest($types[2], $projects[0], $editions[2]))   && p('11')      && e("SELECT id FROM `zt_design` WHERE `project`  = '0' AND  `deleted`  = '0'");  // 获取IPD版关联项目ID=0的数据
r($docTester->getLinkedObjectDataTest($types[2], $projects[1], $editions[2]))   && p('11')      && e("SELECT id FROM `zt_design` WHERE `project`  = '11' AND  `deleted`  = '0'"); // 获取IPD版关联项目ID=11的数据
r($docTester->getLinkedObjectDataTest($types[2], $projects[2], $editions[2]))   && p('11')      && e("SELECT id FROM `zt_design` WHERE `project`  = '60' AND  `deleted`  = '0'"); // 获取IPD版关联项目ID=60的数据
r($docTester->getLinkedObjectDataTest($types[3], $executions[0], $editions[0])) && p('0')       && e("0");                                                                       // 获取开源版关联执行ID=0的数据
r($docTester->getLinkedObjectDataTest($types[3], $executions[1], $editions[0])) && p('13', ';') && e('1,11');                                                                     // 获取开源版关联执行ID=101的数据
r($docTester->getLinkedObjectDataTest($types[3], $executions[2], $editions[0])) && p('13', ';') && e('6,16');                                                                     // 获取开源版关联执行ID=106的数据
r($docTester->getLinkedObjectDataTest($types[3], $executions[0], $editions[1])) && p('0')       && e("0");                                                                       // 获取旗舰版关联执行ID=0的数据
r($docTester->getLinkedObjectDataTest($types[3], $executions[1], $editions[1])) && p('13', ';') && e('1,11');                                                                     // 获取旗舰版关联执行ID=101的数据
r($docTester->getLinkedObjectDataTest($types[3], $executions[2], $editions[1])) && p('13', ';') && e('6,16');                                                                     // 获取旗舰版关联执行ID=106的数据
r($docTester->getLinkedObjectDataTest($types[3], $executions[0], $editions[2])) && p('0')       && e("0");                                                                       // 获取IPD版关联执行ID=0的数据
r($docTester->getLinkedObjectDataTest($types[3], $executions[1], $editions[2])) && p('13', ';') && e('1,11');                                                                     // 获取IPD版关联执行ID=101的数据
r($docTester->getLinkedObjectDataTest($types[3], $executions[2], $editions[2])) && p('13', ';') && e('6,16');                                                                     // 获取IPD版关联执行ID=106的数据
