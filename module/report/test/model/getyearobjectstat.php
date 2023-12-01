#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/report.class.php';

zdTable('action')->config('action_annual')->gen(200);
zdTable('task')->gen(60);
zdTable('bug')->gen(60);
zdTable('story')->gen(60);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getYearObjectStat();
timeout=0
cid=1

*/
$accounts   = array(array('admin'), array('dev17'), array('test18'), array('admin', 'dev17'), array('admin', 'test18'), array());
$objectType = array('story', 'task', 'bug');

$report = new reportTest();

r($report->getYearObjectStatTest($accounts[0], $objectType[0])) && p() && e('draft:3;changing:3;');                              // 测试获取 admin story
r($report->getYearObjectStatTest($accounts[0], $objectType[1])) && p() && e('wait:5;pause:3;');                                  // 测试获取 admin task
r($report->getYearObjectStatTest($accounts[0], $objectType[2])) && p() && e('active:3;');                                        // 测试获取 admin bug
r($report->getYearObjectStatTest($accounts[1], $objectType[0])) && p() && e('active:3;draft:3;');                                // 测试获取 dev17 story
r($report->getYearObjectStatTest($accounts[1], $objectType[1])) && p() && e('doing:5;cancel:3;');                                // 测试获取 dev17 task
r($report->getYearObjectStatTest($accounts[1], $objectType[2])) && p() && e('active:2;');                                        // 测试获取 dev17 bug
r($report->getYearObjectStatTest($accounts[2], $objectType[0])) && p() && e('closed:3;active:3;');                               // 测试获取 test18 story
r($report->getYearObjectStatTest($accounts[2], $objectType[1])) && p() && e('done:3;closed:3;');                                 // 测试获取 test18 task
r($report->getYearObjectStatTest($accounts[2], $objectType[2])) && p() && e('active:4;');                                        // 测试获取 test18 bug
r($report->getYearObjectStatTest($accounts[3], $objectType[0])) && p() && e('draft:6;active:3;changing:3;');                     // 测试获取 admin dev17 story
r($report->getYearObjectStatTest($accounts[3], $objectType[1])) && p() && e('wait:5;doing:5;pause:3;cancel:3;');                 // 测试获取 admin dev17 task
r($report->getYearObjectStatTest($accounts[3], $objectType[2])) && p() && e('active:5;');                                        // 测试获取 admin dev17 bug
r($report->getYearObjectStatTest($accounts[4], $objectType[0])) && p() && e('draft:3;closed:3;changing:3;active:3;');            // 测试获取 admin test18 story
r($report->getYearObjectStatTest($accounts[4], $objectType[1])) && p() && e('wait:5;done:3;pause:3;closed:3;');                  // 测试获取 admin test18 task
r($report->getYearObjectStatTest($accounts[4], $objectType[2])) && p() && e('active:7;');                                        // 测试获取 admin test18 bug
r($report->getYearObjectStatTest($accounts[5], $objectType[0])) && p() && e('draft:6;active:6;closed:3;changing:3;');            // 测试获取 所有用户 story
r($report->getYearObjectStatTest($accounts[5], $objectType[1])) && p() && e('wait:5;doing:5;done:3;pause:3;cancel:3;closed:3;'); // 测试获取 所有用户 task
r($report->getYearObjectStatTest($accounts[5], $objectType[2])) && p() && e('active:9;');                                        // 测试获取 所有用户 bug
