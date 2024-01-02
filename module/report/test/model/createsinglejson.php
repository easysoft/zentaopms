#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/report.class.php';

zdTable('project')->config('execution')->gen('100');
zdTable('burn')->config('burn')->gen('100');
zdTable('task')->config('task')->gen('100');
zdTable('user')->gen(1);

su('admin');

/**

title=测试 reportModel->createSingleJSON();
cid=1
pid=1

*/

$report = new reportTest();

$executionID = array(101, 102, 103, 104, 105);

r($report->createSingleJSONTest($executionID[0])) && p() && e('0,0,0,0,0,0,0,0,0,2,2,2,2,2,2,2,2,2,2,2'); // 测试获取执行 101 的json数据
r($report->createSingleJSONTest($executionID[1])) && p() && e('0,0,0,0,0,0,0,0,0,2,2,2,2,2,2,2,2,2,2');   // 测试获取执行 102 的json数据
r($report->createSingleJSONTest($executionID[2])) && p() && e('0,0,0,0,0,0,0,0,2,2,2,2,2,2,2,2,2,2,2');   // 测试获取执行 103 的json数据
r($report->createSingleJSONTest($executionID[3])) && p() && e('0,0,0,0,0,0,0,0,2,2,2,2,2,2,2,2,2,2,2');   // 测试获取执行 104 的json数据
r($report->createSingleJSONTest($executionID[4])) && p() && e('0,0,0,0,0,0,0,0,2,2,2,2,2,2,2,2,2,2');     // 测试获取执行 105 的json数据
