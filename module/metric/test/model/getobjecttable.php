#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getObjectTable();
timeout=0
cid=17114

- 执行$result1[0]第0条的name属性 @project
- 执行$result2[0]第1条的headerGroup属性 @2023年
- 执行$result3[0]第1条的title属性 @2023-10-16年
- 执行$result4[1]第0条的project属性 @project1
- 执行$result5[1] @0
- 执行$result6[0]第1条的headerGroup属性 @2023年

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

su('admin');

$metricTest = new metricTest();

// 准备测试数据
$testHeader = array(
    array('name' => 'project', 'title' => '项目')
);

$normalData = array();
$normalData[0] = new stdClass();
$normalData[0]->dateString = '2023-10-15';
$normalData[0]->scope = 'project1';
$normalData[0]->value = '10';
$normalData[0]->calcTime = '2023-10-15 10:00:00';
$normalData[0]->calcType = 'cron';
$normalData[0]->calculatedBy = 'admin';

$normalData[1] = new stdClass();
$normalData[1]->dateString = '2023-10-16';
$normalData[1]->scope = 'project1';
$normalData[1]->value = '15';
$normalData[1]->calcTime = '2023-10-16 10:00:00';
$normalData[1]->calcType = 'cron';
$normalData[1]->calculatedBy = 'admin';

$normalData[2] = new stdClass();
$normalData[2]->dateString = '2023-10-15';
$normalData[2]->scope = 'project2';
$normalData[2]->value = '20';
$normalData[2]->calcTime = '2023-10-15 10:00:00';
$normalData[2]->calcType = 'inference';
$normalData[2]->calculatedBy = 'user1';

// 测试步骤1：正常day类型处理，检查表头字段名
$result1 = $metricTest->getObjectTableTest($testHeader, $normalData, 'day', true);
r($result1[0]) && p('0:name') && e('project');

// 测试步骤2：month类型处理，检查表头分组  
$result2 = $metricTest->getObjectTableTest($testHeader, $normalData, 'month', true);
r($result2[0]) && p('1:headerGroup') && e('2023年');

// 测试步骤3：year类型处理，检查表头标题
$result3 = $metricTest->getObjectTableTest($testHeader, $normalData, 'year', true);
r($result3[0]) && p('1:title') && e('2023-10-16年');

// 测试步骤4：withCalcTime为false，检查数据对象字段
$result4 = $metricTest->getObjectTableTest($testHeader, $normalData, 'day', false);
r($result4[1]) && p('0:project') && e('project1');

// 测试步骤5：空数据处理，检查数据部分长度
$result5 = $metricTest->getObjectTableTest($testHeader, array(), 'day', true);
r($result5[1]) && p() && e('0');

// 测试步骤6：week类型处理，检查表头分组
$result6 = $metricTest->getObjectTableTest($testHeader, $normalData, 'week', true);
r($result6[0]) && p('1:headerGroup') && e('2023年');