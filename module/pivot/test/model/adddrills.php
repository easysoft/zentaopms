#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::addDrills();
timeout=0
cid=17354

- 步骤1：正常情况 @1
- 步骤2：空settings @1
- 步骤3：无效settings @1
- 步骤4：无columns属性 @1
- 步骤5：无drill数据 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 注释掉pivotdrill表数据准备，因为使用模拟对象测试
// $table = zenData('pivotdrill');
// $table->loadYaml('pivotdrill_adddrills', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 强制要求：必须包含至少5个测试步骤
$result1 = $pivotTest->addDrillsTest('normal_case');
r(isset($result1->settings['columns'][0]['drill'])) && p() && e('1'); // 步骤1：正常情况

$result2 = $pivotTest->addDrillsTest('empty_settings');
r($result2 == '1') && p() && e('1'); // 步骤2：空settings

$result3 = $pivotTest->addDrillsTest('invalid_settings');
r($result3 == '1') && p() && e('1'); // 步骤3：无效settings

$result4 = $pivotTest->addDrillsTest('no_columns');
r($result4 == '1') && p() && e('1'); // 步骤4：无columns属性

$result5 = $pivotTest->addDrillsTest('no_drill_data');
r($result5->settings['columns'][0]['drill'] == 'nonexistent_field') && p() && e('1'); // 步骤5：无drill数据