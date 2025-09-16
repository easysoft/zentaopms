#!/usr/bin/env php
<?php

/**

title=测试 projectZen::checkWorkdaysLegtimate();
timeout=0
cid=0

- 执行projectTest模块的checkWorkdaysLegtimateTest方法，参数是$project1  @1
- 执行projectTest模块的checkWorkdaysLegtimateTest方法，参数是$project2  @1
- 执行projectTest模块的checkWorkdaysLegtimateTest方法，参数是$project3  @0
- 执行projectTest模块的checkWorkdaysLegtimateTest方法，参数是$project4  @1
- 执行projectTest模块的checkWorkdaysLegtimateTest方法，参数是$project5  @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备
zenData('project')->loadYaml('project_checkworkdayslegtimate', false, 2)->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$projectTest = new projectZenTest();

// 5. 测试步骤（必须至少5个）

// 步骤1：正常情况，工作天数(20)小于日期差(30+1=31)
$project1 = new stdClass();
$project1->begin = '2024-01-01';
$project1->end = '2024-01-31';
$project1->days = 20;
r($projectTest->checkWorkdaysLegtimateTest($project1)) && p() && e('1');

// 步骤2：边界情况，工作天数(31)等于日期差(30+1=31)
$project2 = new stdClass();
$project2->begin = '2024-01-01';
$project2->end = '2024-01-31';
$project2->days = 31;
r($projectTest->checkWorkdaysLegtimateTest($project2)) && p() && e('1');

// 步骤3：无效情况，工作天数(40)大于日期差(30+1=31)
$project3 = new stdClass();
$project3->begin = '2024-01-01';
$project3->end = '2024-01-31';
$project3->days = 40;
dao::$errors = array(); // 清除之前的错误
r($projectTest->checkWorkdaysLegtimateTest($project3)) && p() && e('0');

// 步骤4：无工作天数字段情况
$project4 = new stdClass();
$project4->begin = '2024-01-01';
$project4->end = '2024-01-31';
r($projectTest->checkWorkdaysLegtimateTest($project4)) && p() && e('1');

// 步骤5：工作天数为0的情况
$project5 = new stdClass();
$project5->begin = '2024-01-01';
$project5->end = '2024-01-31';
$project5->days = 0;
r($projectTest->checkWorkdaysLegtimateTest($project5)) && p() && e('1');