#!/usr/bin/env php
<?php
/**

title=测试 reportModel->createSingleJSON();
cid=18161

- 测试获取执行 101 的json数据 @1
- 测试获取执行 102 的json数据 @1
- 测试获取执行 103 的json数据 @1
- 测试获取执行 104 的json数据 @1
- 测试获取执行 105 的json数据 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

zenData('project')->loadYaml('execution')->gen('100');
zenData('burn')->loadYaml('burn')->gen('100');
zenData('task')->loadYaml('task')->gen('100');
zenData('user')->gen(1);
su('admin');

$report      = new reportTest();
$executionID = array(101, 102, 103, 104, 105);

r($report->createSingleJSONTest($executionID[0])) && p() && e('1'); // 测试获取执行 101 的json数据
r($report->createSingleJSONTest($executionID[1])) && p() && e('1'); // 测试获取执行 102 的json数据
r($report->createSingleJSONTest($executionID[2])) && p() && e('1'); // 测试获取执行 103 的json数据
r($report->createSingleJSONTest($executionID[3])) && p() && e('1'); // 测试获取执行 104 的json数据
r($report->createSingleJSONTest($executionID[4])) && p() && e('1'); // 测试获取执行 105 的json数据
