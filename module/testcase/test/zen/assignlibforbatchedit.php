#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignLibForBatchEdit();
timeout=0
cid=19071

- 步骤1：测试有效库ID(1)属性libID @1
- 步骤2：测试有效库ID(2)属性libID @2
- 步骤3：测试库ID为0的边界情况属性libID @0
- 步骤4：测试不存在的库ID属性libID @999
- 步骤5：测试负数库ID属性libID @-1
- 步骤6：验证方法调用成功属性methodCalled @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('testsuite');
$table->id->range('1-10');
$table->project->range('0');
$table->product->range('0');
$table->name->range('基础功能测试库{2},性能测试库{2},安全测试库{2},回归测试库{2},接口测试库{2}');
$table->desc->range('基础功能测试用例集合,性能测试相关用例,安全测试用例库,回归测试用例集,API接口测试用例');
$table->type->range('library');
$table->order->range('1-10');
$table->addedBy->range('admin{5},user{5}');
$table->addedDate->range('`2024-01-01 10:00:00`');
$table->lastEditedBy->range('admin{3},user{3},tester{4}');
$table->lastEditedDate->range('`2024-01-01 10:00:00`');
$table->deleted->range('0{8},1{2}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignLibForBatchEditTest(1)) && p('libID') && e('1'); // 步骤1：测试有效库ID(1)
r($testcaseTest->assignLibForBatchEditTest(2)) && p('libID') && e('2'); // 步骤2：测试有效库ID(2)
r($testcaseTest->assignLibForBatchEditTest(0)) && p('libID') && e('0'); // 步骤3：测试库ID为0的边界情况
r($testcaseTest->assignLibForBatchEditTest(999)) && p('libID') && e('999'); // 步骤4：测试不存在的库ID
r($testcaseTest->assignLibForBatchEditTest(-1)) && p('libID') && e('-1'); // 步骤5：测试负数库ID
r($testcaseTest->assignLibForBatchEditTest(1)) && p('methodCalled') && e('1'); // 步骤6：验证方法调用成功