#!/usr/bin/env php
<?php

/**

title=测试 jobZen::getCompileData();
timeout=0
cid=0

- 步骤1：正常情况属性taskID @1
- 步骤2：空测试任务属性taskID @0
- 步骤3：不存在的测试任务属性taskID @999
- 步骤4：获取套件数量 @2
- 步骤5：包含统计信息第summary条的1属性 @共1个用例，失败0个，耗时1秒

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$testtask = zenData('testtask');
$testtask->id->range('1-3');
$testtask->name->range('测试任务1,测试任务2,测试任务3');
$testtask->product->range('1{3}');
$testtask->status->range('wait,doing,done');
$testtask->gen(3);

$product = zenData('product');
$product->id->range('1');
$product->name->range('测试产品');
$product->status->range('normal');
$product->gen(1);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$jobTest = new jobTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($jobTest->getCompileDataTest((object)array('testtask' => 1))) && p('taskID') && e('1'); // 步骤1：正常情况
r($jobTest->getCompileDataTest((object)array('testtask' => 0))) && p('taskID') && e('0'); // 步骤2：空测试任务
r($jobTest->getCompileDataTest((object)array('testtask' => 999))) && p('taskID') && e('999'); // 步骤3：不存在的测试任务
r(count($jobTest->getCompileDataTest((object)array('testtask' => 1))['suites'])) && p() && e('2'); // 步骤4：获取套件数量
r($jobTest->getCompileDataTest((object)array('testtask' => 1))) && p('summary:1') && e('共1个用例，失败0个，耗时1秒'); // 步骤5：包含统计信息