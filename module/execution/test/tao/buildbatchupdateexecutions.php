#!/usr/bin/env php
<?php

/**

title=测试 executionTao::buildBatchUpdateExecutions();
timeout=0
cid=16382

- 执行executionTest模块的buildBatchUpdateExecutionsTest方法，参数是$validPostData, $oldExecutions 第1条的name属性 @更新的执行1
- 执行executionTest模块的buildBatchUpdateExecutionsTest方法，参数是$duplicateNameData, $oldExecutions 属性name[2] @阶段名称不能相同！
- 执行executionTest模块的buildBatchUpdateExecutionsTest方法，参数是$emptyCodeData, $oldExecutions 属性code[1] @『执行代号』不能为空。
- 执行executionTest模块的buildBatchUpdateExecutionsTest方法，参数是$invalidDateData, $oldExecutions 属性end[1] @『2023-02-01』应当不小于计划开始时间『2023-03-01』。
- 执行executionTest模块的buildBatchUpdateExecutionsTest方法，参数是$excessDaysData, $oldExecutions 属性days[1] @可用工作日不能超过『28』天
- 执行executionTest模块的buildBatchUpdateExecutionsTest方法，参数是$emptyBeginData, $oldExecutions 属性begin[1] @『计划开始』不能为空。
- 执行executionTest模块的buildBatchUpdateExecutionsTest方法，参数是$emptyEndData, $oldExecutions 属性end[1] @『计划完成』不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 注释掉zenData调用，避免数据库连接问题
// zenData('user')->gen(10);
// zenData('project')->gen(10);

su('admin');

global $config;
$config->execution->edit->requiredFields = 'name,code,begin,end';

$executionTest = new executionTaoTest();

// 构造基础的老执行数据
$oldExecutions = array();
for($i = 1; $i <= 3; $i++) {
    $execution = new stdClass();
    $execution->id = $i;
    $execution->name = '执行' . $i;
    $execution->parent = 1;
    $execution->project = 1;
    $execution->type = 'sprint';
    $oldExecutions[$i] = $execution;
}

// 1. 正常的批量更新数据
$validPostData = new stdClass();
$validPostData->id = array(1 => 1, 2 => 2, 3 => 3);
$validPostData->name = array(1 => '更新的执行1', 2 => '更新的执行2', 3 => '更新的执行3');
$validPostData->code = array(1 => 'updated_exec1', 2 => 'updated_exec2', 3 => 'updated_exec3');
$validPostData->begin = array(1 => '2023-02-01', 2 => '2023-03-01', 3 => '2023-04-01');
$validPostData->end = array(1 => '2023-02-28', 2 => '2023-03-31', 3 => '2023-04-30');
$validPostData->days = array(1 => '20', 2 => '25', 3 => '22');

// 2. 名称重复的数据 (在同一parent下名称重复)
$duplicateNameData = clone $validPostData;
$duplicateNameData->name = array(1 => '执行1', 2 => '执行1', 3 => '执行3');

// 3. 代码为空的数据
$emptyCodeData = clone $validPostData;
$emptyCodeData->code = array(1 => '', 2 => 'exec2', 3 => 'exec3');

// 4. 时间范围错误的数据
$invalidDateData = clone $validPostData;
$invalidDateData->begin = array(1 => '2023-03-01', 2 => '2023-03-01', 3 => '2023-04-01');
$invalidDateData->end = array(1 => '2023-02-01', 2 => '2023-03-31', 3 => '2023-04-30');

// 5. 工作日超出范围的数据
$excessDaysData = clone $validPostData;
$excessDaysData->days = array(1 => '50', 2 => '25', 3 => '22');

// 6. 开始时间为空的数据
$emptyBeginData = clone $validPostData;
$emptyBeginData->begin = array(1 => '', 2 => '2023-03-01', 3 => '2023-04-01');

// 7. 结束时间为空的数据
$emptyEndData = clone $validPostData;
$emptyEndData->end = array(1 => '', 2 => '2023-03-31', 3 => '2023-04-30');

r($executionTest->buildBatchUpdateExecutionsTest($validPostData, $oldExecutions)) && p('1:name') && e('更新的执行1');
r($executionTest->buildBatchUpdateExecutionsTest($duplicateNameData, $oldExecutions)) && p('name[2]') && e('阶段名称不能相同！');
r($executionTest->buildBatchUpdateExecutionsTest($emptyCodeData, $oldExecutions)) && p('code[1]') && e('『执行代号』不能为空。');
r($executionTest->buildBatchUpdateExecutionsTest($invalidDateData, $oldExecutions)) && p('end[1]') && e('『2023-02-01』应当不小于计划开始时间『2023-03-01』。');
r($executionTest->buildBatchUpdateExecutionsTest($excessDaysData, $oldExecutions)) && p('days[1]') && e('可用工作日不能超过『28』天');
r($executionTest->buildBatchUpdateExecutionsTest($emptyBeginData, $oldExecutions)) && p('begin[1]') && e('『计划开始』不能为空。');
r($executionTest->buildBatchUpdateExecutionsTest($emptyEndData, $oldExecutions)) && p('end[1]') && e('『计划完成』不能为空。');