#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getBugs();
cid=1
pid=1

测试获取 -40 day 到 -29 day 产品 1 执行 101 的bug >> :4;all:4;validRate:0;
测试获取 -28 day 到 -25 day 产品 2 执行 102 的bug >> :3;all:3;validRate:0;
测试获取 -24 day 到 -21 day 产品 3 执行 103 的bug >> :2;all:2;validRate:0;
测试获取 -20 day 到 -19 day 产品 4 执行 104 的bug >> :1;all:1;validRate:0;
测试获取 -20 day 到 -16 day 产品 5 执行 105 的bug >> :3;all:3;validRate:0;

*/

$begin = array(' -40 day', ' -28 day', ' -24 day', ' -20 day', ' -20 day');
$end   = array(' -29 day', ' -25 day', ' -21 day', ' -19 day', ' -16 day');
$productID = array(1, 2, 3, 4, 5);
$executionID = array(101, 102, 103, 104, 105);

$report = new reportTest();

r($report->getBugsTest($begin[0], $end[0], $productID[0], $executionID[0])) && p('admin') && e(':4;all:4;validRate:0;'); // 测试获取 -40 day 到 -29 day 产品 1 执行 101 的bug
r($report->getBugsTest($begin[1], $end[1], $productID[1], $executionID[1])) && p('admin') && e(':3;all:3;validRate:0;'); // 测试获取 -28 day 到 -25 day 产品 2 执行 102 的bug
r($report->getBugsTest($begin[2], $end[2], $productID[2], $executionID[2])) && p('admin') && e(':2;all:2;validRate:0;'); // 测试获取 -24 day 到 -21 day 产品 3 执行 103 的bug
r($report->getBugsTest($begin[3], $end[3], $productID[3], $executionID[3])) && p('admin') && e(':1;all:1;validRate:0;'); // 测试获取 -20 day 到 -19 day 产品 4 执行 104 的bug
r($report->getBugsTest($begin[4], $end[4], $productID[4], $executionID[4])) && p('admin') && e(':3;all:3;validRate:0;'); // 测试获取 -20 day 到 -16 day 产品 5 执行 105 的bug