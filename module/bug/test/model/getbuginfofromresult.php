#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

zdTable('bug')->gen(10);
zdTable('case')->gen(10);
zdTable('testsuite')->gen(10);
zdTable('testrun')->gen(10);
zdTable('casestep')->gen(10);
zdTable('story')->gen(10);

$result = zdTable('testresult');
$result->stepResults->range('1-10')->prefix('a:1:{i:')->postfix(';a:2:{s:6:"result";s:4:"pass";s:4:"real";s:0:"";}}');
$result->gen(10);

/**

title=bugModel->getBugInfoFromResult();
cid=1
pid=1

*/

$runIDList  = array(0, 2, 6, 10);
$caseIDList = array(2, 6, 10);
$stepIdList = array('2', '6', '10');

$bug = new bugTest();

r($bug->getBugInfoFromResultTest($runIDList[0], $caseIDList[0]))                 && p() && e('0');                // 测试获取runID为0 caseID为2的bug
r($bug->getBugInfoFromResultTest($runIDList[0], $caseIDList[1]))                 && p() && e('0');                // 测试获取runID为0 caseID为6的bug
r($bug->getBugInfoFromResultTest($runIDList[0], $caseIDList[2]))                 && p() && e('0');                // 测试获取runID为0 caseID为10的bug
r($bug->getBugInfoFromResultTest($runIDList[1], $caseIDList[0]))                 && p() && e('这个是测试用例2');  // 测试获取runID为2 caseID为2的bug
r($bug->getBugInfoFromResultTest($runIDList[1], $caseIDList[1]))                 && p() && e('这个是测试用例6');  // 测试获取runID为2 caseID为6的bug
r($bug->getBugInfoFromResultTest($runIDList[1], $caseIDList[2]))                 && p() && e('这个是测试用例10'); // 测试获取runID为2 caseID为10的bug
r($bug->getBugInfoFromResultTest($runIDList[2], $caseIDList[0]))                 && p() && e('这个是测试用例2');  // 测试获取runID为6 caseID为2的bug
r($bug->getBugInfoFromResultTest($runIDList[2], $caseIDList[1]))                 && p() && e('这个是测试用例6');  // 测试获取runID为6 caseID为6的bug
r($bug->getBugInfoFromResultTest($runIDList[2], $caseIDList[2]))                 && p() && e('这个是测试用例10'); // 测试获取runID为6 caseID为10的bug
r($bug->getBugInfoFromResultTest($runIDList[3], $caseIDList[0]))                 && p() && e('这个是测试用例2');  // 测试获取runID为10 caseID为2的bug
r($bug->getBugInfoFromResultTest($runIDList[3], $caseIDList[1]))                 && p() && e('这个是测试用例6');  // 测试获取runID为10 caseID为6的bug
r($bug->getBugInfoFromResultTest($runIDList[3], $caseIDList[2]))                 && p() && e('这个是测试用例10'); // 测试获取runID为10 caseID为10的bug
r($bug->getBugInfoFromResultTest($runIDList[1], $caseIDList[0], $stepIdList[0])) && p() && e('这个是测试用例2');  // 测试获取runID为2 caseID为2 stepIdList为2 的bug
r($bug->getBugInfoFromResultTest($runIDList[1], $caseIDList[1], $stepIdList[1])) && p() && e('这个是测试用例6');  // 测试获取runID为2 caseID为6 stepIdList为6 的bug
r($bug->getBugInfoFromResultTest($runIDList[1], $caseIDList[2], $stepIdList[2])) && p() && e('这个是测试用例10'); // 测试获取runID为2 caseID为10 stepIdList为10 的bug
