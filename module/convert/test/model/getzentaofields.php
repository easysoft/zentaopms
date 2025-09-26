#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getZentaoFields();
timeout=0
cid=0

- 步骤1：正常情况-story模块字段数量 @6
- 步骤2：正常情况-bug模块字段数量 @13
- 步骤3：正常情况-task模块字段数量 @5
- 步骤4：不存在模块返回空数组 @0
- 步骤5：空字符串模块返回空数组 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 3. 强制要求：必须包含至少5个测试步骤
r(count($convertTest->getZentaoFieldsTest('story'))) && p() && e('6'); // 步骤1：正常情况-story模块字段数量
r(count($convertTest->getZentaoFieldsTest('bug'))) && p() && e('13'); // 步骤2：正常情况-bug模块字段数量
r(count($convertTest->getZentaoFieldsTest('task'))) && p() && e('5'); // 步骤3：正常情况-task模块字段数量
r(count($convertTest->getZentaoFieldsTest('unknown'))) && p() && e('0'); // 步骤4：不存在模块返回空数组
r(count($convertTest->getZentaoFieldsTest(''))) && p() && e('0'); // 步骤5：空字符串模块返回空数组