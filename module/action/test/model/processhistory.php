#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

/**

title=测试 actionModel->processHistory();
timeout=0
cid=1

*/

zenData('user')->gen(10);
su('admin');

zenData('history')->loadYaml('history')->gen(25);

$idList = array(1, 9, 10, 11, 16, 17, 20, 23, 24, 25);

$actionTester = new actionTest();
r($actionTester->processHistoryTest($idList[0])) && p('field,oldValue,newValue') && e('PO,~~,用户1'); // 测试处理ID为的产品历史记录
r($actionTester->processHistoryTest($idList[1])) && p('field,oldValue,newValue') && e('status,未开始,已关闭'); // 测试处理ID为的需求历史记录
r($actionTester->processHistoryTest($idList[2])) && p('field,oldValue,newValue') && e('closedReason,~~,已完成'); // 测试处理ID为的计划历史记录
r($actionTester->processHistoryTest($idList[3])) && p('field,oldValue,newValue') && e('PM,~~,用户4'); // 测试处理ID为的发布历史记录
r($actionTester->processHistoryTest($idList[4])) && p('field,oldValue,newValue') && e('closedReason,~~,已完成'); // 测试处理ID为的项目历史记录
r($actionTester->processHistoryTest($idList[5])) && p('field,oldValue,newValue') && e('builder,~~,用户8'); // 测试处理ID为的任务历史记录
r($actionTester->processHistoryTest($idList[6])) && p('field,oldValue,newValue') && e('resolution,~~,已解决'); // 测试处理ID为的版本历史记录
r($actionTester->processHistoryTest($idList[7])) && p('field,oldValue,newValue') && e('type,单元测试,接口测试'); // 测试处理ID为的Bug历史记录
r($actionTester->processHistoryTest($idList[8])) && p('field,oldValue,newValue') && e('lastRunner,~~,用户2'); // 测试处理ID为的用例历史记录
r($actionTester->processHistoryTest($idList[9])) && p('field,oldValue,newValue') && e('stage,单元测试阶段,功能测试阶段'); // 测试处理ID为的用例历史记录
