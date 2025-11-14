#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 programplanModel->calcDaysForStage();
timeout=0
cid=17735

- 测试计算2025-01-01到2025-01-01期间的工作日。 @1
- 测试计算2025-01-01到2025-01-05期间的工作日。 @4
- 测试计算2025-01-01到2025-01-10期间的工作日。 @9
- 测试计算2025-01-01到3025-01-01期间的工作日。 @313066
- 测试计算非正常日期字符串的工作日。 @1
- 测试计算非正常日期字符串的工作日。 @1
- 测试计算2025-01-01到2025-01-01期间的工作日。 @1
- 测试计算2025-01-01到2025-01-05期间的工作日。 @3
- 测试计算2025-01-01到2025-01-10期间的工作日。 @8
- 测试计算2025-01-01到3025-01-01期间的工作日。 @260888
- 测试计算非正常日期字符串的工作日。 @1
- 测试计算非正常日期字符串的工作日。 @1

*/

global $tester, $config;
$tester->loadModel('programplan');

$config->execution->weekend = 1;
r($tester->programplan->calcDaysForStage('2025-01-01', '2025-01-01')) && p() && e('1');      // 测试计算2025-01-01到2025-01-01期间的工作日。
r($tester->programplan->calcDaysForStage('2025-01-01', '2025-01-05')) && p() && e('4');      // 测试计算2025-01-01到2025-01-05期间的工作日。
r($tester->programplan->calcDaysForStage('2025-01-01', '2025-01-10')) && p() && e('9');      // 测试计算2025-01-01到2025-01-10期间的工作日。
r($tester->programplan->calcDaysForStage('2025-01-01', '3025-01-01')) && p() && e('313066'); // 测试计算2025-01-01到3025-01-01期间的工作日。
r($tester->programplan->calcDaysForStage('asd',        'dsa'))        && p() && e('1');      // 测试计算非正常日期字符串的工作日。
r($tester->programplan->calcDaysForStage('0',          '1'))          && p() && e('1');      // 测试计算非正常日期字符串的工作日。

$config->execution->weekend = 2;
r($tester->programplan->calcDaysForStage('2025-01-01', '2025-01-01')) && p() && e('1');      // 测试计算2025-01-01到2025-01-01期间的工作日。
r($tester->programplan->calcDaysForStage('2025-01-01', '2025-01-05')) && p() && e('3');      // 测试计算2025-01-01到2025-01-05期间的工作日。
r($tester->programplan->calcDaysForStage('2025-01-01', '2025-01-10')) && p() && e('8');      // 测试计算2025-01-01到2025-01-10期间的工作日。
r($tester->programplan->calcDaysForStage('2025-01-01', '3025-01-01')) && p() && e('260888'); // 测试计算2025-01-01到3025-01-01期间的工作日。
r($tester->programplan->calcDaysForStage('asd',        'dsa'))        && p() && e('1');      // 测试计算非正常日期字符串的工作日。
r($tester->programplan->calcDaysForStage('0',          '1'))          && p() && e('1');      // 测试计算非正常日期字符串的工作日。
