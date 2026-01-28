#!/usr/bin/env php
<?php

/**

title=测试 compileModel::getListByJobID();
timeout=0
cid=15749

- 步骤1：正常jobID查询第2条的name属性 @构建2
- 步骤2：不存在的jobID @0
- 步骤3：jobID为0 @0
- 步骤4：负数jobID @0
- 步骤5：字符串jobID @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$compileTable = zenData('compile');
$compileTable->id->range('1-5');
$compileTable->name->range('构建1,构建2,构建3,构建4,构建5');
$compileTable->job->range('1{2},2{1},3{1},0{1}');
$compileTable->status->range('success,failure,building,success,cancelled');
$compileTable->deleted->range('0');
$compileTable->gen(5);

$jobTable = zenData('job');
$jobTable->id->range('1-3');
$jobTable->name->range('任务1,任务2,任务3');
$jobTable->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$compileTest = new compileModelTest();

// 5. 执行测试步骤（至少5个）
r($compileTest->getListByJobIDTest(1)) && p('2:name') && e('构建2'); // 步骤1：正常jobID查询
r($compileTest->getListByJobIDTest(999)) && p() && e('0'); // 步骤2：不存在的jobID
r($compileTest->getListByJobIDTest(0)) && p() && e('0'); // 步骤3：jobID为0
r($compileTest->getListByJobIDTest(-1)) && p() && e('0'); // 步骤4：负数jobID
r($compileTest->getListByJobIDTest('abc')) && p() && e('0'); // 步骤5：字符串jobID