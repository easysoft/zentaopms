#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

zenData('user')->gen('1');
zenData('case')->gen('5');
zenData('casestep')->loadYaml('casestep')->gen('20');
zenData('testresult')->gen('5');

su('admin');

/**

title=测试 testtaskModel->processResultSteps();
cid=19216
pid=1



*/

$resultIdList = array(1, 2, 3, 4, 5);

$testtask = new testtaskTest();

$testtask->initResult();

r($testtask->processResultStepsTest($resultIdList[0])) && p() && e('1, 1, 1, pass,; 2, 1.1, 2; 3, 1.1.1, 3; 4, 2, 1; 5, 2.1, 2; 6, 2.1.1, 3; 7, 2.1.2, 3; 8, 2.2, 2; 9, 2.2.1, 3; 10, 3, 1; 11, 3.1, 3; 12, 4, 1;'); // 测试计算用例执行结果 1 的步骤 
r($testtask->processResultStepsTest($resultIdList[1])) && p() && e('13, 1, 1;'); // 测试计算用例执行结果 2 的步骤 
r($testtask->processResultStepsTest($resultIdList[2])) && p() && e('14, 1, 1;'); // 测试计算用例执行结果 3 的步骤 
r($testtask->processResultStepsTest($resultIdList[3])) && p() && e('15, 1, 1;'); // 测试计算用例执行结果 4 的步骤 
r($testtask->processResultStepsTest($resultIdList[4])) && p() && e('16, 1, 1;'); // 测试计算用例执行结果 5 的步骤 
