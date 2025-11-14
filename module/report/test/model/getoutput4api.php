#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

zenData('action')->loadYaml('action_annual')->gen(200);
zenData('task')->gen(60);
zenData('bug')->gen(60);
zenData('story')->gen(60);
zenData('case')->gen(80);
zenData('testresult')->loadYaml('testresult')->gen(80);
zenData('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getOutput4API();
cid=18164

- 测试获取 admin 的输出 @story:10;task:8;bug:3;case:5;
- 测试获取 dev17 的输出 @task:4;bug:3;case:3;
- 测试获取 test18 的输出 @story:5;task:4;bug:7;case:3;
- 测试获取 admin,dev17 的输出 @story:10;task:12;bug:6;case:8;
- 测试获取 admin,test18 的输出 @story:15;task:12;bug:10;case:8;
- 测试获取 所有用户 的输出 @story:15;task:16;bug:13;case:54;

*/
$account = array(array('admin'), array('dev17'), array('test18'), array('admin', 'dev17'), array('admin', 'test18'), array());

$report = new reportTest();

r($report->getOutput4APITest($account[0])) && p() && e('story:10;task:8;bug:3;case:5;');    // 测试获取 admin 的输出
r($report->getOutput4APITest($account[1])) && p() && e('task:4;bug:3;case:3;');             // 测试获取 dev17 的输出
r($report->getOutput4APITest($account[2])) && p() && e('story:5;task:4;bug:7;case:3;');     // 测试获取 test18 的输出
r($report->getOutput4APITest($account[3])) && p() && e('story:10;task:12;bug:6;case:8;');   // 测试获取 admin,dev17 的输出
r($report->getOutput4APITest($account[4])) && p() && e('story:15;task:12;bug:10;case:8;');  // 测试获取 admin,test18 的输出
r($report->getOutput4APITest($account[5])) && p() && e('story:15;task:16;bug:13;case:54;'); // 测试获取 所有用户 的输出
