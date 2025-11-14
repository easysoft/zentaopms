#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 projectModel->getBudgetWithUnit();
timeout=0
cid=17818

*/

global $tester;
$tester->loadModel('project');

r($tester->project->getBudgetWithUnit(222))            && p() && e('222');    // 测试数字222经过单位转换后为222
r($tester->project->getBudgetWithUnit(222.111123))     && p() && e('222.11'); // 测试数字222.111123经过单位转换后为222.11
r($tester->project->getBudgetWithUnit(222.116))        && p() && e('222.12'); // 测试数字222.116经过单位转换后为222.12
r($tester->project->getBudgetWithUnit(12222))          && p() && e('1.22万'); // 测试数字12222经过单位转换后为1.22万
r($tester->project->getBudgetWithUnit(12345.126))      && p() && e('1.23万'); // 测试数字12345.126经过单位转换后为1.23万
r($tester->project->getBudgetWithUnit(120000000))      && p() && e('1.2亿');  // 测试数字120000000经过单位转换后为1.2亿
r($tester->project->getBudgetWithUnit(123650000.1233)) && p() && e('1.24亿'); // 测试数字123650000.1233经过单位转换后为1.24亿
