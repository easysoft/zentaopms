#!/usr/bin/env php
<?php

/**

title=测试 biModel::processDrills();
timeout=0
cid=0

- 测试步骤1：正常钻取条件处理 @originalField
- 测试步骤2：列不包含钻取字段时 @0
- 测试步骤3：空钻取字段输入 @originalField
- 测试步骤4：复杂钻取条件处理 @multiField
- 测试步骤5：钻取字段部分匹配 @partialField

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$biTest = new biTest();

// 4. 测试步骤（每个测试步骤必须在一行内）
r($biTest->processDrillsTest('field1', array('queryField1' => 'value1'), array('field1' => array('drillField' => 'originalField', 'condition' => array(array('queryField' => 'queryField1', 'operator' => '=', 'value' => '')))))) && p('0') && e('originalField'); // 测试步骤1：正常钻取条件处理

r($biTest->processDrillsTest('field2', array('queryField1' => 'value1'), array('field2' => array('notDrillField' => 'test')))) && p() && e('0'); // 测试步骤2：列不包含钻取字段时

r($biTest->processDrillsTest('field3', array(), array('field3' => array('drillField' => 'originalField', 'condition' => array(array('queryField' => 'queryField1', 'operator' => '=', 'value' => '')))))) && p('0') && e('originalField'); // 测试步骤3：空钻取字段输入

r($biTest->processDrillsTest('field4', array('query1' => 'val1', 'query2' => 'val2'), array('field4' => array('drillField' => 'multiField', 'condition' => array(array('queryField' => 'query1', 'operator' => '=', 'value' => ''), array('queryField' => 'query2', 'operator' => '!=', 'value' => '')))))) && p('0') && e('multiField'); // 测试步骤4：复杂钻取条件处理

r($biTest->processDrillsTest('field5', array('exist' => 'existValue', 'notmatch' => 'ignored'), array('field5' => array('drillField' => 'partialField', 'condition' => array(array('queryField' => 'exist', 'operator' => 'LIKE', 'value' => ''), array('queryField' => 'missing', 'operator' => '=', 'value' => '')))))) && p('0') && e('partialField'); // 测试步骤5：钻取字段部分匹配