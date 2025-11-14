#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::rebuildFieldSettings();
timeout=0
cid=15214

- 步骤1：正常情况下重建字段设置 @1
- 步骤2：输入空字段对数组 @1
- 步骤3：字段设置为数组格式 @1
- 步骤4：字段设置中缺少字段配置 @1
- 步骤5：字段设置中存在兼容性问题 @1

*/

$biTest = new biTest();

// 步骤1：正常情况下重建字段设置
$fieldPairs = array('id' => '编号', 'name' => '名称');
$columns = (object)array('id' => 'int', 'name' => 'string');
$relatedObject = array('id' => 'user', 'name' => 'user');
$fieldSettings = (object)array();
$objectFields = array('user' => array('id' => array('type' => 'int'), 'name' => array('type' => 'string')));
$result1 = $biTest->rebuildFieldSettingsTest($fieldPairs, $columns, $relatedObject, $fieldSettings, $objectFields);
r(is_object($result1) && isset($result1->id)) && p() && e('1'); // 步骤1：正常情况下重建字段设置

// 步骤2：输入空字段对数组
$emptyFieldPairs = array();
$emptyColumns = (object)array();
$emptyRelatedObject = array();
$emptyFieldSettings = (object)array();
$emptyObjectFields = array();
$result2 = $biTest->rebuildFieldSettingsTest($emptyFieldPairs, $emptyColumns, $emptyRelatedObject, $emptyFieldSettings, $emptyObjectFields);
r(is_object($result2)) && p() && e('1'); // 步骤2：输入空字段对数组

// 步骤3：字段设置为数组格式
$arrayFieldSettings = array();
$result3 = $biTest->rebuildFieldSettingsTest($fieldPairs, $columns, $relatedObject, $arrayFieldSettings, $objectFields);
r(is_array($result3)) && p() && e('1'); // 步骤3：字段设置为数组格式

// 步骤4：字段设置中缺少字段配置
$incompleteFieldSettings = (object)array('id' => (object)array('name' => '编号'));
$result4 = $biTest->rebuildFieldSettingsTest($fieldPairs, $columns, $relatedObject, $incompleteFieldSettings, $objectFields);
r(is_object($result4) && isset($result4->id) && isset($result4->name)) && p() && e('1'); // 步骤4：字段设置中缺少字段配置

// 步骤5：字段设置中存在兼容性问题
$compatibilityFieldSettings = (object)array(
    'id' => (object)array('name' => '编号', 'object' => true, 'field' => ''),
    'name' => (object)array('name' => '名称', 'object' => false)
);
$result5 = $biTest->rebuildFieldSettingsTest($fieldPairs, $columns, $relatedObject, $compatibilityFieldSettings, $objectFields);
r(is_object($result5) && $result5->id->object === 'user' && $result5->name->object === 'user') && p() && e('1'); // 步骤5：字段设置中存在兼容性问题