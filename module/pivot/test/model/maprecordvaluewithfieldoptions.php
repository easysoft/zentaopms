#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::mapRecordValueWithFieldOptions();
timeout=0
cid=17414

- 执行pivotTest模块的mapRecordValueWithFieldOptionsTest方法，参数是$records1, $fields1, 'mysql' 第0条的name属性 @Test Record 1
- 执行pivotTest模块的mapRecordValueWithFieldOptionsTest方法，参数是$records2, $fields2, 'mysql' 第0条的content属性 @Test Content
- 执行pivotTest模块的mapRecordValueWithFieldOptionsTest方法，参数是$emptyRecords, $emptyFields, 'mysql'  @0
- 执行pivotTest模块的mapRecordValueWithFieldOptionsTest方法，参数是$records4, $fields4, 'mysql' 第0条的name属性 @Test Name
- 执行pivotTest模块的mapRecordValueWithFieldOptionsTest方法，参数是$records5, $fields5, 'mysql' 第0条的priority属性 @1
- 执行pivotTest模块的mapRecordValueWithFieldOptionsTest方法，参数是$records6, $fields6, 'mysql' 第0条的status_origin属性 @active
- 执行pivotTest模块的mapRecordValueWithFieldOptionsTest方法，参数是$records7, $fields7, 'mysql' 第0条的description属性 @Quoted text

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：测试正常字段映射处理
$records1 = array(
    (object)array('name' => 'Test Record 1', 'title' => 'Title 1'),
    (object)array('name' => 'Test Record 2', 'title' => 'Title 2')
);
$fields1 = array(
    'name' => array('object' => 'story', 'field' => 'title', 'type' => 'string'),
    'title' => array('object' => 'story', 'field' => 'title', 'type' => 'string')
);
r($pivotTest->mapRecordValueWithFieldOptionsTest($records1, $fields1, 'mysql')) && p('0:name') && e('Test Record 1');

// 步骤2：测试HTML实体解码功能
$records2 = array(
    (object)array('content' => '&quot;Test Content&quot;', 'title' => 'Test&amp;Title')
);
$fields2 = array(
    'content' => array('object' => 'story', 'field' => 'spec', 'type' => 'string'),
    'title' => array('object' => 'story', 'field' => 'title', 'type' => 'string')
);
r($pivotTest->mapRecordValueWithFieldOptionsTest($records2, $fields2, 'mysql')) && p('0:content') && e('Test Content');

// 步骤3：测试空记录集处理
$emptyRecords = array();
$emptyFields = array();
r($pivotTest->mapRecordValueWithFieldOptionsTest($emptyRecords, $emptyFields, 'mysql')) && p() && e('0');

// 步骤4：测试字段过滤功能
$records4 = array(
    (object)array('name' => 'Test Name', 'unknown_field' => 'value', 'other' => 'data')
);
$fields4 = array(
    'name' => array('object' => 'story', 'field' => 'title', 'type' => 'string')
);
r($pivotTest->mapRecordValueWithFieldOptionsTest($records4, $fields4, 'mysql')) && p('0:name') && e('Test Name');

// 步骤5：测试数值类型字段处理
$records5 = array(
    (object)array('priority' => '1', 'estimate' => '8.5')
);
$fields5 = array(
    'priority' => array('object' => 'story', 'field' => 'pri', 'type' => 'number'),
    'estimate' => array('object' => 'task', 'field' => 'estimate', 'type' => 'number')
);
r($pivotTest->mapRecordValueWithFieldOptionsTest($records5, $fields5, 'mysql')) && p('0:priority') && e('1');

// 步骤6：测试origin字段的生成
$records6 = array(
    (object)array('status' => 'active')
);
$fields6 = array(
    'status' => array('object' => 'story', 'field' => 'status', 'type' => 'string')
);
r($pivotTest->mapRecordValueWithFieldOptionsTest($records6, $fields6, 'mysql')) && p('0:status_origin') && e('active');

// 步骤7：测试引号处理功能
$records7 = array(
    (object)array('description' => '"Quoted text"')
);
$fields7 = array(
    'description' => array('object' => 'story', 'field' => 'spec', 'type' => 'text')
);
r($pivotTest->mapRecordValueWithFieldOptionsTest($records7, $fields7, 'mysql')) && p('0:description') && e('Quoted text');