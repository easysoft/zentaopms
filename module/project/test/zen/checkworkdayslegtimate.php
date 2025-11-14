#!/usr/bin/env php
<?php

/**

title=测试 projectZen::checkWorkdaysLegtimate();
timeout=0
cid=17937

- 步骤1:工作日小于计划工作日期间 @1
- 步骤2:工作日等于计划工作日期间 @1
- 步骤3:工作日为0的边界值 @1
- 步骤4:未设置工作日 @1
- 步骤5:工作日超出期间属性days @可用工作日不能超过『10』天
- 步骤6:跨月份的工作日验证 @1
- 步骤7:跨年份的工作日验证 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$projectTest = new projectZenTest();

$project1 = new stdclass();
$project1->begin = '2024-01-01';
$project1->end = '2024-01-10';
$project1->days = 5;
r($projectTest->checkWorkdaysLegtimateTest($project1)) && p() && e('1'); // 步骤1:工作日小于计划工作日期间

$project2 = new stdclass();
$project2->begin = '2024-01-01';
$project2->end = '2024-01-10';
$project2->days = 10;
r($projectTest->checkWorkdaysLegtimateTest($project2)) && p() && e('1'); // 步骤2:工作日等于计划工作日期间

$project3 = new stdclass();
$project3->begin = '2024-01-01';
$project3->end = '2024-01-10';
$project3->days = 0;
r($projectTest->checkWorkdaysLegtimateTest($project3)) && p() && e('1'); // 步骤3:工作日为0的边界值

$project4 = new stdclass();
$project4->begin = '2024-01-01';
$project4->end = '2024-01-10';
r($projectTest->checkWorkdaysLegtimateTest($project4)) && p() && e('1'); // 步骤4:未设置工作日

$project5 = new stdclass();
$project5->begin = '2024-01-01';
$project5->end = '2024-01-10';
$project5->days = 15;
r($projectTest->checkWorkdaysLegtimateTest($project5)) && p('days') && e('可用工作日不能超过『10』天'); // 步骤5:工作日超出期间

$project6 = new stdclass();
$project6->begin = '2024-01-01';
$project6->end = '2024-01-31';
$project6->days = 20;
r($projectTest->checkWorkdaysLegtimateTest($project6)) && p() && e('1'); // 步骤6:跨月份的工作日验证

$project7 = new stdclass();
$project7->begin = '2024-01-01';
$project7->end = '2024-12-31';
$project7->days = 366;
r($projectTest->checkWorkdaysLegtimateTest($project7)) && p() && e('1'); // 步骤7:跨年份的工作日验证