#!/usr/bin/env php
<?php

/**

title=测试 devModel::setField();
timeout=0
cid=16019

- 执行devTest模块的setFieldTest方法，参数是$enumField, $enumRawField, 'enum', 4
 - 第options条的enum属性 @open
- 执行devTest模块的setFieldTest方法，参数是$varcharField, $varcharRawField, 'varchar', 7 属性type @varchar
- 执行devTest模块的setFieldTest方法，参数是$intField, $intRawField, 'int', 3 属性type @int
- 执行devTest模块的setFieldTest方法，参数是$floatField, $floatRawField, 'double', 6 属性type @float
- 执行devTest模块的setFieldTest方法，参数是$textField, $textRawField, 'text', -1 属性type @text

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$devTest = new devModelTest();

// 测试步骤1：enum类型字段处理，期望返回完整的枚举选项列表
$enumField = array('name' => 'status', 'null' => 'NO');
$enumRawField = (object)array('field' => 'status', 'type' => "enum('open','done','closed')", 'null' => 'NO');
r($devTest->setFieldTest($enumField, $enumRawField, 'enum', 4)) && p('options:enum') && e('open,done,closed');

// 测试步骤2：varchar类型字段处理，期望返回varchar类型
$varcharField = array('name' => 'title', 'null' => 'NO');
$varcharRawField = (object)array('field' => 'title', 'type' => 'varchar(255)', 'null' => 'NO');
r($devTest->setFieldTest($varcharField, $varcharRawField, 'varchar', 7)) && p('type') && e('varchar');

// 测试步骤3：int类型字段处理，期望返回int类型
$intField = array('name' => 'count', 'null' => 'YES');
$intRawField = (object)array('field' => 'count', 'type' => 'int(11)', 'null' => 'YES');
r($devTest->setFieldTest($intField, $intRawField, 'int', 3)) && p('type') && e('int');

// 测试步骤4：double类型字段处理，期望返回float类型
$floatField = array('name' => 'price', 'null' => 'YES');
$floatRawField = (object)array('field' => 'price', 'type' => 'double(10,2)', 'null' => 'YES');
r($devTest->setFieldTest($floatField, $floatRawField, 'double', 6)) && p('type') && e('float');

// 测试步骤5：text类型字段处理，期望返回text类型
$textField = array('name' => 'content', 'null' => 'YES');
$textRawField = (object)array('field' => 'content', 'type' => 'text', 'null' => 'YES');
r($devTest->setFieldTest($textField, $textRawField, 'text', -1)) && p('type') && e('text');