#!/usr/bin/env php
<?php

/**

title=测试 markModel::hasMark();
timeout=0
cid=17045

- 步骤1：正常情况，查询存在的标记 @1
- 步骤2：查询不存在对象的标记 @0
- 步骤3：查询不存在版本的标记 @0
- 步骤4：测试onlyMajor参数为true时主版本查询 @1
- 步骤5：测试onlyMajor参数为true时子版本查询 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mark.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('mark');
$table->id->range('1-10');
$table->objectType->range('story{5},task{3},bug{2}');
$table->objectID->range('1-10');
$table->version->range('1{3},1.1{2},2{3},2.5{2}');
$table->account->range('admin{10}');
$table->mark->range('view{8},edit{2}');
$table->date->range('`2024-01-01 10:00:00`');
$table->extra->range('``{10}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$markTest = new markTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($markTest->hasMarkTest('story', 1, '1', 'view', false)) && p() && e('1'); // 步骤1：正常情况，查询存在的标记
r($markTest->hasMarkTest('story', 999, '1', 'view', false)) && p() && e('0'); // 步骤2：查询不存在对象的标记
r($markTest->hasMarkTest('story', 1, '3.0', 'view', false)) && p() && e('0'); // 步骤3：查询不存在版本的标记
r($markTest->hasMarkTest('story', 1, 'all', 'view', true)) && p() && e('1'); // 步骤4：测试onlyMajor参数为true时主版本查询
r($markTest->hasMarkTest('story', 4, 'all', 'view', true)) && p() && e('0'); // 步骤5：测试onlyMajor参数为true时子版本查询