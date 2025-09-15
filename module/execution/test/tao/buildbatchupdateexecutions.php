#!/usr/bin/env php
<?php

/**

title=测试 executionTao::buildBatchUpdateExecutions();
timeout=0
cid=0

- 执行executionTest模块的buildBatchUpdateExecutionsTest方法，参数是$validPostData, $validOldExecutions 第6条的name属性 @更新的执行1
- 执行executionTest模块的buildBatchUpdateExecutionsTest方法，参数是$duplicateNameData, $duplicateOldExecutions 属性name[7] @阶段名称不能相同！
- 执行executionTest模块的buildBatchUpdateExecutionsTest方法，参数是$emptyCodeData, $validOldExecutions 属性code[6] @『执行代号』不能为空。
- 执行executionTest模块的buildBatchUpdateExecutionsTest方法，参数是$invalidDateData, $validOldExecutions 属性end[6] @『2023-02-01』应当不小于计划开始时间『2023-03-01』。
- 执行executionTest模块的buildBatchUpdateExecutionsTest方法，参数是$excessDaysData, $validOldExecutions 属性days[6] @可用工作日不能超过『28』天

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

su('admin');

$executionTest = new executionTest();

// 设置code为必填字段
global $config;
$config->execution->edit->requiredFields = 'name,code,begin,end';

// 构造正常的批量更新数据
$validPostData = new stdClass();
$validPostData->id = array(6 => 6, 7 => 7, 8 => 8);
$validPostData->name = array(6 => '更新的执行1', 7 => '更新的执行2', 8 => '更新的执行3');
$validPostData->code = array(6 => 'updated_exec1', 7 => 'updated_exec2', 8 => 'updated_exec3');
$validPostData->PM = array(6 => 'admin', 7 => 'user1', 8 => 'user2');
$validPostData->PO = array(6 => 'admin', 7 => 'user1', 8 => 'user2');
$validPostData->QD = array(6 => 'admin', 7 => 'user1', 8 => 'user2');
$validPostData->RD = array(6 => 'admin', 7 => 'user1', 8 => 'user2');
$validPostData->begin = array(6 => '2023-02-01', 7 => '2023-03-01', 8 => '2023-04-01');
$validPostData->end = array(6 => '2023-02-28', 7 => '2023-03-31', 8 => '2023-04-30');
$validPostData->team = array(6 => '', 7 => '', 8 => '');
$validPostData->desc = array(6 => '更新描述1', 7 => '更新描述2', 8 => '更新描述3');
$validPostData->days = array(6 => '20', 7 => '25', 8 => '22');

$validOldExecutions = array();
for($i = 6; $i <= 8; $i++) {
    $execution = new stdClass();
    $execution->id = $i;
    $execution->name = '执行' . ($i-5);
    $execution->parent = 1;
    $execution->project = 1;
    $execution->type = 'sprint';
    $validOldExecutions[$i] = $execution;
}

// 构造名称重复的数据
$duplicateNameData = clone $validPostData;
$duplicateNameData->name = array(6 => '重复名称', 7 => '重复名称', 8 => '执行3');

$duplicateOldExecutions = array();
for($i = 6; $i <= 8; $i++) {
    $execution = new stdClass();
    $execution->id = $i;
    $execution->name = '执行' . ($i-5);
    $execution->parent = 1;
    $execution->project = 1;
    $execution->type = 'sprint';
    $duplicateOldExecutions[$i] = $execution;
}

// 构造代码为空的数据
$emptyCodeData = clone $validPostData;
$emptyCodeData->code = array(6 => '', 7 => 'exec2', 8 => 'exec3');

// 构造时间范围错误的数据
$invalidDateData = clone $validPostData;
$invalidDateData->begin = array(6 => '2023-03-01', 7 => '2023-03-01', 8 => '2023-04-01');
$invalidDateData->end = array(6 => '2023-02-01', 7 => '2023-03-31', 8 => '2023-04-30');

// 构造工作日超出范围的数据
$excessDaysData = clone $validPostData;
$excessDaysData->days = array(6 => '50', 7 => '25', 8 => '22');

r($executionTest->buildBatchUpdateExecutionsTest($validPostData, $validOldExecutions)) && p('6:name') && e('更新的执行1');
r($executionTest->buildBatchUpdateExecutionsTest($duplicateNameData, $duplicateOldExecutions)) && p('name[7]') && e('阶段名称不能相同！');
r($executionTest->buildBatchUpdateExecutionsTest($emptyCodeData, $validOldExecutions)) && p('code[6]') && e('『执行代号』不能为空。');
r($executionTest->buildBatchUpdateExecutionsTest($invalidDateData, $validOldExecutions)) && p('end[6]') && e('『2023-02-01』应当不小于计划开始时间『2023-03-01』。');
r($executionTest->buildBatchUpdateExecutionsTest($excessDaysData, $validOldExecutions)) && p('days[6]') && e('可用工作日不能超过『28』天');