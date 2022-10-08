#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getBugInfoFromResult();
cid=1
pid=1

测试获取runID为0 caseID为2的bug >> 这个是测试用例2
测试获取runID为0 caseID为6的bug >> 这个是测试用例6
测试获取runID为0 caseID为10的bug >> 这个是测试用例10
测试获取runID为2 caseID为2的bug >> 这个是测试用例2
测试获取runID为2 caseID为6的bug >> 这个是测试用例6
测试获取runID为2 caseID为10的bug >> 这个是测试用例10
测试获取runID为6 caseID为2的bug >> 这个是测试用例2
测试获取runID为6 caseID为6的bug >> 这个是测试用例6
测试获取runID为6 caseID为10的bug >> 这个是测试用例10
测试获取runID为10 caseID为2的bug >> 这个是测试用例2
测试获取runID为10 caseID为6的bug >> 这个是测试用例6
测试获取runID为10 caseID为10的bug >> 这个是测试用例10

*/

$runIDList       = array('0', '2', '6', '10');
$caseIDList      = array('0', '2', '6', '10');

$bug=new bugTest();

r($bug->getBugInfoFromResultTest($runIDList[0], $caseIDList[1])) && p() && e('这个是测试用例2');  // 测试获取runID为0 caseID为2的bug
r($bug->getBugInfoFromResultTest($runIDList[0], $caseIDList[2])) && p() && e('这个是测试用例6');  // 测试获取runID为0 caseID为6的bug
r($bug->getBugInfoFromResultTest($runIDList[0], $caseIDList[3])) && p() && e('这个是测试用例10'); // 测试获取runID为0 caseID为10的bug
r($bug->getBugInfoFromResultTest($runIDList[1], $caseIDList[1])) && p() && e('这个是测试用例2');  // 测试获取runID为2 caseID为2的bug
r($bug->getBugInfoFromResultTest($runIDList[1], $caseIDList[2])) && p() && e('这个是测试用例6');  // 测试获取runID为2 caseID为6的bug
r($bug->getBugInfoFromResultTest($runIDList[1], $caseIDList[3])) && p() && e('这个是测试用例10'); // 测试获取runID为2 caseID为10的bug
r($bug->getBugInfoFromResultTest($runIDList[2], $caseIDList[1])) && p() && e('这个是测试用例2');  // 测试获取runID为6 caseID为2的bug
r($bug->getBugInfoFromResultTest($runIDList[2], $caseIDList[2])) && p() && e('这个是测试用例6');  // 测试获取runID为6 caseID为6的bug
r($bug->getBugInfoFromResultTest($runIDList[2], $caseIDList[3])) && p() && e('这个是测试用例10'); // 测试获取runID为6 caseID为10的bug
r($bug->getBugInfoFromResultTest($runIDList[3], $caseIDList[1])) && p() && e('这个是测试用例2');  // 测试获取runID为10 caseID为2的bug
r($bug->getBugInfoFromResultTest($runIDList[3], $caseIDList[2])) && p() && e('这个是测试用例6');  // 测试获取runID为10 caseID为6的bug
r($bug->getBugInfoFromResultTest($runIDList[3], $caseIDList[3])) && p() && e('这个是测试用例10'); // 测试获取runID为10 caseID为10的bug