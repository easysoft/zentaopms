#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

/**

title=测试 actionModel->processActions();
timeout=0
cid=1

*/

zenData('user')->gen(10);
su('admin');

zenData('action')->loadYaml('action')->gen(10);
zenData('history')->loadYaml('history')->gen(25);

$objectTypeList = array('product', 'story', 'productplan', 'release', 'project', 'task', 'build', 'bug', 'testcase', 'case');
$objectIdList   = range(1, 10);

$actionTester = new actionTest();
r($actionTester->processActionsTest($objectTypeList[0], $objectIdList[0])) && p('0:field,old,new') && e('PO,``,用户1');                     // 测试处理ID为1的产品动态
r($actionTester->processActionsTest($objectTypeList[1], $objectIdList[1])) && p('1:field,old,new') && e('reviewedBy,``,用户3');             // 测试处理ID为2的需求动态
r($actionTester->processActionsTest($objectTypeList[2], $objectIdList[2])) && p('0:field,old,new') && e('status,未开始,已关闭');            // 测试处理ID为3的计划动态
r($actionTester->processActionsTest($objectTypeList[3], $objectIdList[3])) && p()                  && e('0');                               // 测试处理ID为4的发布动态
r($actionTester->processActionsTest($objectTypeList[4], $objectIdList[4])) && p('0:field,old,new') && e('PM,``,用户4');                     // 测试处理ID为5的项目动态
r($actionTester->processActionsTest($objectTypeList[5], $objectIdList[5])) && p('0:field,old,new') && e('type,设计,开发');                  // 测试处理ID为6的任务动态
r($actionTester->processActionsTest($objectTypeList[6], $objectIdList[6])) && p('0:field,old,new') && e('builder,``,用户8');                // 测试处理ID为7的版本动态
r($actionTester->processActionsTest($objectTypeList[7], $objectIdList[7])) && p('2:field,old,new') && e('resolution,``,已解决');            // 测试处理ID为8的Bug动态
r($actionTester->processActionsTest($objectTypeList[8], $objectIdList[8])) && p('1:field,old,new') && e('type,单元测试,接口测试');          // 测试处理ID为9的用例动态
r($actionTester->processActionsTest($objectTypeList[9], $objectIdList[9])) && p('1:field,old,new') && e('stage,单元测试阶段,功能测试阶段'); // 测试处理ID为10的用例动态
