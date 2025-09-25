#!/usr/bin/env php
<?php

/**

title=测试 devModel::setField();
timeout=0
cid=0

- 测试步骤1：enum类型字段处理 >> 期望正确解析enum选项
- 测试步骤2：varchar类型字段处理 >> 期望设置最大长度限制
- 测试步骤3：int类型字段处理 >> 期望设置数值范围限制
- 测试步骤4：float类型字段处理 >> 期望设置为float类型
- 测试步骤5：text类型字段处理 >> 期望设置基本类型属性

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';

su('admin');

$devTest = new devTest();

// 测试步骤1：enum类型字段处理
$enumField = array('name' => 'status', 'null' => 'NO');
$enumRawField = (object)array('field' => 'status', 'type' => "enum('open','done','closed')", 'null' => 'NO');
r($devTest->setFieldTest($enumField, $enumRawField, 'enum', 4)) && p('options:enum') && e('open,done,closed');

// 测试步骤2：varchar类型字段处理
$varcharField = array('name' => 'title', 'null' => 'NO');
$varcharRawField = (object)array('field' => 'title', 'type' => 'varchar(255)', 'null' => 'NO');
r($devTest->setFieldTest($varcharField, $varcharRawField, 'varchar', 7)) && p('type') && e('varchar');

// 测试步骤3：int类型字段处理
$intField = array('name' => 'count', 'null' => 'YES');
$intRawField = (object)array('field' => 'count', 'type' => 'int(11)', 'null' => 'YES');
r($devTest->setFieldTest($intField, $intRawField, 'int', 3)) && p('type') && e('int');

// 测试步骤4：float类型字段处理
$floatField = array('name' => 'price', 'null' => 'YES');
$floatRawField = (object)array('field' => 'price', 'type' => 'double(10,2)', 'null' => 'YES');
r($devTest->setFieldTest($floatField, $floatRawField, 'double', 6)) && p('type') && e('float');

// 测试步骤5：text类型字段处理
$textField = array('name' => 'content', 'null' => 'YES');
$textRawField = (object)array('field' => 'content', 'type' => 'text', 'null' => 'YES');
r($devTest->setFieldTest($textField, $textRawField, 'text', -1)) && p('type') && e('text');