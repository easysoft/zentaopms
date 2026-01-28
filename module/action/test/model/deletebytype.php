#!/usr/bin/env php
<?php

/**

title=测试 actionModel::deleteByType();
timeout=0
cid=14885

- 步骤1：测试删除空objectType @1
- 步骤2：测试删除story类型 @1
- 步骤3：测试删除task类型 @1
- 步骤4：测试删除不存在类型 @1
- 步骤5：测试删除bug类型 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$table = zenData('action');
$table->objectType->range('story{5},task{3},bug{2},\'\'');
$table->objectID->range('1-10');
$table->actor->range('admin{5},user{3},test{2}');
$table->action->range('opened{5},closed{3},edited{2}');
$table->date->range('`2024-01-01 10:00:00`,`2024-01-02 10:00:00`,`2024-01-03 10:00:00`');
$table->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$actionTest = new actionModelTest();

// 5. 执行至少5个测试步骤
r($actionTest->deleteByTypeTest('')) && p() && e('1'); // 步骤1：测试删除空objectType
r($actionTest->deleteByTypeTest('story')) && p() && e('1'); // 步骤2：测试删除story类型
r($actionTest->deleteByTypeTest('task')) && p() && e('1'); // 步骤3：测试删除task类型
r($actionTest->deleteByTypeTest('nonexistent')) && p() && e('1'); // 步骤4：测试删除不存在类型
r($actionTest->deleteByTypeTest('bug')) && p() && e('1'); // 步骤5：测试删除bug类型