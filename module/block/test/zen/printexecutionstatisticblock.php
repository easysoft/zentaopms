#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printExecutionStatisticBlock();
timeout=0
cid=0

- 步骤1：正常情况 @1
- 步骤2：非法参数 @0
- 步骤3：无执行数据 @0
- 步骤4：指定项目 @1
- 步骤5：指定活跃执行 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->id->range('1-5');
$table->name->range('执行1,执行2,执行3,执行4,执行5');
$table->type->range('sprint{3},execution{2}');
$table->status->range('wait,doing,done,closed,wait');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($blockTest->printExecutionStatisticBlockTest('normal', 'my')) && p() && e('1'); // 步骤1：正常情况
r($blockTest->printExecutionStatisticBlockTest('invalid_type!', 'my')) && p() && e('0'); // 步骤2：非法参数
r($blockTest->printExecutionStatisticBlockTest('none', 'my')) && p() && e('0'); // 步骤3：无执行数据
r($blockTest->printExecutionStatisticBlockTest('normal', 'project', 1)) && p() && e('1'); // 步骤4：指定项目
r($blockTest->printExecutionStatisticBlockTest('normal', 'my', 0, 2)) && p() && e('2'); // 步骤5：指定活跃执行