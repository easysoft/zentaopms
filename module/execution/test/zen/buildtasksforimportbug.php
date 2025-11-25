#!/usr/bin/env php
<?php
/**

title=测试 executionZen::buildTasksForImportBug();
timeout=0
cid=16419

- 测试空数据 @0
- 测试预估工时为负数的情况属性type[1] @ID: 1"最初预计"必须为正数
- 测试截止日期小于实际开始日期的情况属性deadline[1] @ID: 1"截止日期"必须大于"预计开始"
- 测试正常的情况
 - 第1条的name属性 @BUG1
 - 第1条的execution属性 @3
 - 第1条的type属性 @devel

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

$bugTable = zenData('bug');
$bugTable->project->range('3');
$bugTable->gen(10);

zenData('project')->loadYaml('project')->gen(3);
zenData('task')->loadYaml('task')->gen(10);
zenData('user')->gen(5);
su('admin');

$modeList = array('emptyData', 'errorEstimate', 'errorDeadline', 'normal');

global $tester;
$execution = new executionZenTest();
r($execution->buildTasksForImportBugTest($modeList[0])) && p()                        && e('0');                                 // 测试空数据
r($execution->buildTasksForImportBugTest($modeList[1])) && p('type[1]')               && e('ID: 1"最初预计"必须为正数');         // 测试预估工时为负数的情况
r($execution->buildTasksForImportBugTest($modeList[2])) && p('deadline[1]')           && e('ID: 1"截止日期"必须大于"预计开始"'); // 测试截止日期小于实际开始日期的情况
r($execution->buildTasksForImportBugTest($modeList[3])) && p('1:name,execution,type') && e('BUG1,3,devel');                      // 测试正常的情况
