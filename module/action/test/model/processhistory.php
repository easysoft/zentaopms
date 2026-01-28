#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('action')->gen(10);
zenData('actionrecent')->gen(0);
zenData('history')->loadYaml('history')->gen(25);

su('admin');

/**

title=测试 actionModel->processHistory();
timeout=0
cid=14924

- 测试处理ID为的产品历史记录
 - 属性field @PO
 - 属性oldValue @~~
 - 属性newValue @用户1
- 测试处理ID为的需求历史记录
 - 属性field @status
 - 属性oldValue @未开始
 - 属性newValue @已关闭
- 测试处理ID为的计划历史记录
 - 属性field @closedReason
 - 属性oldValue @~~
 - 属性newValue @已完成
- 测试处理ID为的发布历史记录
 - 属性field @PM
 - 属性oldValue @~~
 - 属性newValue @用户4
- 测试处理ID为的项目历史记录
 - 属性field @closedReason
 - 属性oldValue @~~
 - 属性newValue @已完成
- 测试处理ID为的任务历史记录
 - 属性field @builder
 - 属性oldValue @~~
 - 属性newValue @用户8
- 测试处理ID为的版本历史记录
 - 属性field @resolution
 - 属性oldValue @~~
 - 属性newValue @已解决
- 测试处理ID为的Bug历史记录
 - 属性field @type
 - 属性oldValue @单元测试
 - 属性newValue @接口测试
- 测试处理ID为的用例历史记录
 - 属性field @lastRunner
 - 属性oldValue @~~
 - 属性newValue @用户2
- 测试处理ID为的用例历史记录
 - 属性field @stage
 - 属性oldValue @单元测试阶段
 - 属性newValue @功能测试阶段

*/

$idList = array(1, 9, 10, 11, 16, 17, 20, 23, 24, 25);

$actionTester = new actionModelTest();
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
