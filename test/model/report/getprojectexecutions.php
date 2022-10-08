#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getProjectExecutions();
cid=1
pid=1

获取项目执行对 101 >> 项目1/迭代1
获取项目执行对 102 >> 项目2/迭代2
获取项目执行对 201 >> 项目11/迭代101
获取项目执行对 301 >> 项目21/迭代201
获取项目执行对 401 >> 项目31/阶段301
获取项目执行个数 >> 450

*/

$count = true;

$executionID = array(101, 102, 201, 301, 401);

$report = new reportTest();

r($report->getProjectExecutionsTest())       && p('101') && e('项目1/迭代1');    // 获取项目执行对 101
r($report->getProjectExecutionsTest())       && p('102') && e('项目2/迭代2');    // 获取项目执行对 102
r($report->getProjectExecutionsTest())       && p('201') && e('项目11/迭代101'); // 获取项目执行对 201
r($report->getProjectExecutionsTest())       && p('301') && e('项目21/迭代201'); // 获取项目执行对 301
r($report->getProjectExecutionsTest())       && p('401') && e('项目31/阶段301'); // 获取项目执行对 401
r($report->getProjectExecutionsTest($count)) && p()      && e('450');            // 获取项目执行个数