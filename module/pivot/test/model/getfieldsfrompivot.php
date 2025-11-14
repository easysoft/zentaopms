#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getFieldsFromPivot();
timeout=0
cid=17384

- 步骤1：获取存在的字符串字段 @SELECT * FROM test_table
- 步骤2：获取JSON字段并解码为对象属性field1 @value1
- 步骤3：获取JSON字段并解码为数组 @filter1
- 步骤4：获取不存在的字段 @default_value
- 步骤5：获取空字段 @default_for_empty

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（此测试不需要数据库数据）
// $table = zenData('pivot');
// $table->gen(0);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$pivotTest = new pivotTest();

// 创建测试用的pivot对象
$pivot1 = new stdClass();
$pivot1->fields = '{"field1":"value1","field2":"value2"}';
$pivot1->filters = '["filter1","filter2"]';
$pivot1->sql = 'SELECT * FROM test_table';
$pivot1->name = '测试透视表';
$pivot1->empty_field = '';

$pivot2 = new stdClass();
// 这个对象没有fields属性

// 5. 测试步骤
r($pivotTest->getFieldsFromPivotTest($pivot1, 'sql', 'default_sql')) && p() && e('SELECT * FROM test_table'); // 步骤1：获取存在的字符串字段
r($pivotTest->getFieldsFromPivotTest($pivot1, 'fields', array(), true, false)) && p('field1') && e('value1'); // 步骤2：获取JSON字段并解码为对象
r($pivotTest->getFieldsFromPivotTest($pivot1, 'filters', array(), true, true)) && p('0') && e('filter1'); // 步骤3：获取JSON字段并解码为数组
r($pivotTest->getFieldsFromPivotTest($pivot2, 'nonexistent', 'default_value')) && p() && e('default_value'); // 步骤4：获取不存在的字段
r($pivotTest->getFieldsFromPivotTest($pivot1, 'empty_field', 'default_for_empty')) && p() && e('default_for_empty'); // 步骤5：获取空字段