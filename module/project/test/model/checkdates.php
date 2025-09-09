#!/usr/bin/env php
<?php

/**

title=测试 projectModel::checkDates();
timeout=0
cid=0

- 步骤1：无子执行项目 @1
- 步骤2：日期检查通过 @1
- 步骤3：结束日期早于子执行 @0
- 步骤4：开始日期晚于子执行 @0
- 步骤5：开始和结束日期都有问题 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

zenData('project')->loadYaml('project_checkdates', false, 2)->gen(20);

su('admin');

$projectTest = new Project();

// 创建测试项目对象
$project1 = new stdClass();
$project1->begin = '2024-01-01';
$project1->end = '2024-12-31';

$project2 = new stdClass();
$project2->begin = '2024-01-15';
$project2->end = '2024-02-15';

$project3 = new stdClass();
$project3->begin = '2024-01-15';
$project3->end = '2024-01-31';

$project4 = new stdClass();
$project4->begin = '2024-03-01';
$project4->end = '2024-02-15';

$project5 = new stdClass();
$project5->begin = '2024-04-01';
$project5->end = '2024-01-31';

r($projectTest->checkDatesTest(999, $project1)) && p() && e('1'); // 步骤1：无子执行项目
r($projectTest->checkDatesTest(1, $project2)) && p() && e('1'); // 步骤2：日期检查通过
r($projectTest->checkDatesTest(2, $project3)) && p() && e('0'); // 步骤3：结束日期早于子执行
r($projectTest->checkDatesTest(1, $project4)) && p() && e('0'); // 步骤4：开始日期晚于子执行
r($projectTest->checkDatesTest(1, $project5)) && p() && e('0'); // 步骤5：开始和结束日期都有问题