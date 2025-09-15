#!/usr/bin/env php
<?php

/**

title=测试 biModel::processDrills();
timeout=0
cid=0

- 测试步骤1：正常钻取条件处理
 - 第0,1条的0:value属性 @originalField
- 测试步骤2：列不包含钻取字段时 @~~
- 测试步骤3：空钻取字段输入 @~~
- 测试步骤4：复杂钻取条件处理
 - 第0,1条的0:value属性 @multiField
 - 第0,1条的1:1:value属性 @val1
- 测试步骤5：钻取字段部分匹配
 - 第0,1条的0:value属性 @partialField

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

su('admin');

$biTest = new biTest();

r($biTest->processDrillsTest('field1', array('queryField1' => 'value1'), array('field1' => array('drillField' => 'originalField', 'condition' => array(array('queryField' => 'queryField1', 'operator' => '=', 'value' => '')))))) && p('0,1:0:value') && e('originalField,value1'); // 测试步骤1：正常钻取条件处理
r($biTest->processDrillsTest('field2', array('queryField1' => 'value1'), array('field2' => array('notDrillField' => 'test')))) && p() && e('~~'); // 测试步骤2：列不包含钻取字段时
r($biTest->processDrillsTest('field3', array(), array('field3' => array('drillField' => 'originalField', 'condition' => array(array('queryField' => 'queryField1', 'operator' => '=', 'value' => '')))))) && p() && e('~~'); // 测试步骤3：空钻取字段输入
r($biTest->processDrillsTest('field4', array('query1' => 'val1', 'query2' => 'val2'), array('field4' => array('drillField' => 'multiField', 'condition' => array(array('queryField' => 'query1', 'operator' => '=', 'value' => ''), array('queryField' => 'query2', 'operator' => '!=', 'value' => '')))))) && p('0,1:0:value,1:1:value') && e('multiField,val1,val2'); // 测试步骤4：复杂钻取条件处理
r($biTest->processDrillsTest('field5', array('exist' => 'existValue', 'notmatch' => 'ignored'), array('field5' => array('drillField' => 'partialField', 'condition' => array(array('queryField' => 'exist', 'operator' => 'LIKE', 'value' => ''), array('queryField' => 'missing', 'operator' => '=', 'value' => '')))))) && p('0,1:0:value') && e('partialField,existValue'); // 测试步骤5：钻取字段部分匹配