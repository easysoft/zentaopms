#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::filterFieldsWithSettings();
timeout=0
cid=17363

- 执行$result1 @4
- 执行$result2 @0
- 执行$result3 @0
- 执行$result4 @3
- 执行$result5 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

su('admin');

$pivotTest = new pivotTest();

// 测试步骤1：正常输入情况，包含字段交集
$fields = array(
    'id' => array('type' => 'int', 'field' => 'id'),
    'name' => array('type' => 'string', 'field' => 'name'), 
    'status' => array('type' => 'string', 'field' => 'status'),
    'priority' => array('type' => 'string', 'field' => 'priority'),
    'unused' => array('type' => 'string', 'field' => 'unused')
);
$groups = array('name', 'status');
$columns = array(
    array('field' => 'priority', 'slice' => 'noSlice'),
    array('field' => 'id', 'slice' => 'noSlice')
);
$result1 = $pivotTest->filterFieldsWithSettingsTest($fields, $groups, $columns);
r(count($result1)) && p() && e('4');

// 测试步骤2：边界值输入，空groups和空columns
$fields = array(
    'id' => array('type' => 'int', 'field' => 'id'),
    'name' => array('type' => 'string', 'field' => 'name')
);
$groups = array();
$columns = array();
$result2 = $pivotTest->filterFieldsWithSettingsTest($fields, $groups, $columns);
r(count($result2)) && p() && e('0');

// 测试步骤3：边界值输入，fields为空数组
$fields = array();
$groups = array('name', 'status');
$columns = array(array('field' => 'priority'));
$result3 = $pivotTest->filterFieldsWithSettingsTest($fields, $groups, $columns);
r(count($result3)) && p() && e('0');

// 测试步骤4：复杂情况，有重复字段和不存在的字段
$fields = array(
    'name' => array('type' => 'string', 'field' => 'name'),
    'status' => array('type' => 'string', 'field' => 'status'),
    'priority' => array('type' => 'string', 'field' => 'priority')
);
$groups = array('name', 'status', 'name'); // 重复字段
$columns = array(
    array('field' => 'priority'),
    array('field' => 'nonexistent'), // 不存在的字段
    array('field' => 'name') // 与groups重复
);
$result4 = $pivotTest->filterFieldsWithSettingsTest($fields, $groups, $columns);
r(count($result4)) && p() && e('3');

// 测试步骤5：columns包含slice字段的情况
$fields = array(
    'name' => array('type' => 'string', 'field' => 'name'),
    'status' => array('type' => 'string', 'field' => 'status'),
    'priority' => array('type' => 'string', 'field' => 'priority'),
    'year' => array('type' => 'string', 'field' => 'year')
);
$groups = array('name');
$columns = array(
    array('field' => 'status', 'slice' => 'year'), // slice不是noSlice
    array('field' => 'priority', 'slice' => 'noSlice')
);
$result5 = $pivotTest->filterFieldsWithSettingsTest($fields, $groups, $columns);
r(count($result5)) && p() && e('4');