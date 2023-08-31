#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zdTable('user')->gen(5);
su('admin');

zdTable('burn')->config('burn')->gen(15);
zdTable('project')->config('execution')->gen(35);

/**

title=测试executionModel->getBurnByExecution();
timeout=0
cid=1

*/

$today = helper::today();

$executionIdList = array(101, 106, 124);

global $tester;
$tester->loadModel('execution');
r($tester->execution->getBurnByExecution($executionIdList[0], $today))       && p()                 && e('0');              // 获取迭代的燃尽图数据
r($tester->execution->getBurnByExecution($executionIdList[1], $today))       && p()                 && e('0');              // 获取阶段的燃尽图数据
r($tester->execution->getBurnByExecution($executionIdList[2], $today))       && p()                 && e('0');              // 获取看板的燃尽图数据
r($tester->execution->getBurnByExecution($executionIdList[0], '2023-07-01')) && p('execution,date') && e('101,2023-07-01'); // 获取迭代的燃尽图数据
r($tester->execution->getBurnByExecution($executionIdList[1], '2023-07-06')) && p('execution,date') && e('106,2023-07-06'); // 获取阶段的燃尽图数据
r($tester->execution->getBurnByExecution($executionIdList[2], '2023-07-08')) && p()                 && e('0');              // 获取看板的燃尽图数据
