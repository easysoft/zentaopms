#!/usr/bin/env php
<?php

/**

title=测试 docModel->getLinkedObjectData();
timeout=0
cid=16107

- 测试传入错误的type @0
- 获取开源版关联产品ID=1的数据属性4 @SELECT id FROM `zt_story` WHERE `product`  = '1' AND  `deleted`  = '0'
- 获取开源版关联产品ID=2的数据属性4 @SELECT id FROM `zt_story` WHERE `product`  = '2' AND  `deleted`  = '0'
- 获取开源版关联项目ID=0的数据属性11 @0
- 获取开源版关联项目ID=11的数据属性11 @0
- 获取开源版关联项目ID=60的数据属性11 @0
- 获取开源版关联执行ID=0的数据 @0
- 获取开源版关联执行ID=101的数据属性13 @0
- 获取开源版关联执行ID=106的数据属性13 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$storyTable = zenData('story');
$storyTable->product->range('1-5');
$storyTable->gen(20);

$planTable = zenData('productplan');
$planTable->product->range('1-5');
$planTable->gen(20);

$releaseTable = zenData('release');
$releaseTable->product->range('1-5');
$releaseTable->gen(20);

$caseTable = zenData('case');
$caseTable->product->range('1-5');
$caseTable->gen(20);

$projectstoryTable = zenData('projectstory');
$projectstoryTable->project->range('11,60,61,100,101-110');
$projectstoryTable->gen(20);

$taskTable = zenData('task');
$taskTable->execution->range('101-110');
$taskTable->gen(20);

$buildTable = zenData('build');
$buildTable->execution->range('101-110');
$buildTable->gen(20);

$issuetable = zendata('issue');
$issuetable->project->range('11, 60, 61, 100');
$issuetable->gen(20);

$meetingTable = zenData('meeting');
$meetingTable->project->range('11, 60, 61, 100');
$meetingTable->gen(20);

$reviewTable = zenData('review');
$reviewTable->project->range('11, 60, 61, 100');
$reviewTable->gen(20);

$designTable = zenData('design');
$designTable->project->range('11, 60, 61, 100');
$designTable->gen(20);

zenData('product')->loadYaml('product')->gen(5);
zenData('project')->loadYaml('execution')->gen(10);
zenData('user')->gen(5);
su('admin');

$types      = array('all', 'product', 'project', 'execution');
$products   = array(0 ,1, 2);
$projects   = array(0, 11, 60);
$executions = array(0, 101, 106);

$docTester = new docModelTest();
r($docTester->getLinkedObjectDataTest($types[0], $products[0]))   && p()          && e('0');                                                                      // 测试传入错误的type
r($docTester->getLinkedObjectDataTest($types[1], $products[1]))   && p('4')       && e("SELECT id FROM `zt_story` WHERE `product`  = '1' AND  `deleted`  = '0'"); // 获取开源版关联产品ID=1的数据
r($docTester->getLinkedObjectDataTest($types[1], $products[2]))   && p('4')       && e("SELECT id FROM `zt_story` WHERE `product`  = '2' AND  `deleted`  = '0'"); // 获取开源版关联产品ID=2的数据
r($docTester->getLinkedObjectDataTest($types[2], $projects[0]))   && p('11')      && e('0');                                                                      // 获取开源版关联项目ID=0的数据
r($docTester->getLinkedObjectDataTest($types[2], $projects[1]))   && p('11')      && e('0');                                                                      // 获取开源版关联项目ID=11的数据
r($docTester->getLinkedObjectDataTest($types[2], $projects[2]))   && p('11')      && e('0');                                                                      // 获取开源版关联项目ID=60的数据
r($docTester->getLinkedObjectDataTest($types[3], $executions[0])) && p('0')       && e("0");                                                                      // 获取开源版关联执行ID=0的数据
r($docTester->getLinkedObjectDataTest($types[3], $executions[1])) && p('13', ';') && e('0');                                                                      // 获取开源版关联执行ID=101的数据
r($docTester->getLinkedObjectDataTest($types[3], $executions[2])) && p('13', ';') && e('0');                                                                      // 获取开源版关联执行ID=106的数据
