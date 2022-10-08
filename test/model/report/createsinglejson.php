#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->createSingleJSON();
cid=1
pid=1

测试获取执行 101 的json数据 >> [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,36]
测试获取执行 102 的json数据 >> [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4]
测试获取执行 103 的json数据 >> [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,23]
测试获取执行 104 的json数据 >> [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,12]
测试获取执行 105 的json数据 >> [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6]

*/

$report = new reportTest();

$executionID = array(101, 102, 103, 104, 105);

r($report->createSingleJSONTest($executionID[0])) && p() && e('[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,36]'); // 测试获取执行 101 的json数据
r($report->createSingleJSONTest($executionID[1])) && p() && e('[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4]');  // 测试获取执行 102 的json数据
r($report->createSingleJSONTest($executionID[2])) && p() && e('[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,23]');   // 测试获取执行 103 的json数据
r($report->createSingleJSONTest($executionID[3])) && p() && e('[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,12]');   // 测试获取执行 104 的json数据
r($report->createSingleJSONTest($executionID[4])) && p() && e('[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6]');      // 测试获取执行 105 的json数据