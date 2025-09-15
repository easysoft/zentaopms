#!/usr/bin/env php
<?php

/**

title=测试 productZen::getCustomFieldsForTrack();
timeout=0
cid=0

- 步骤1：测试story类型显示字段第一个元素第show条的0属性 @story
- 步骤2：测试requirement类型显示字段第一个元素第show条的0属性 @requirement
- 步骤3：测试epic类型包含用户需求字段第list条的requirement属性 @用户需求
- 步骤4：测试story类型包含所属项目字段第list条的project属性 @所属项目
- 步骤5：测试requirement类型包含相关设计字段第list条的design属性 @相关设计

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('config');
$table->owner->range('admin');
$table->module->range('product');
$table->section->range('trackFields');
$table->key->range('story,requirement,epic');
$table->value->range('project,execution,design', 'task,bug,case', '');
$table->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->getCustomFieldsForTrackTest('story')) && p('show:0') && e('story'); // 步骤1：测试story类型显示字段第一个元素
r($productTest->getCustomFieldsForTrackTest('requirement')) && p('show:0') && e('requirement'); // 步骤2：测试requirement类型显示字段第一个元素
r($productTest->getCustomFieldsForTrackTest('epic')) && p('list:requirement') && e('用户需求'); // 步骤3：测试epic类型包含用户需求字段
r($productTest->getCustomFieldsForTrackTest('story')) && p('list:project') && e('所属项目'); // 步骤4：测试story类型包含所属项目字段
r($productTest->getCustomFieldsForTrackTest('requirement')) && p('list:design') && e('相关设计'); // 步骤5：测试requirement类型包含相关设计字段