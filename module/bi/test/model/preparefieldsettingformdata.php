#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareFieldSettingFormData();
timeout=0
cid=0

- 测试步骤1：对象格式字段设置 >> 期望转换为数组并添加key
- 测试步骤2：数组格式字段设置 >> 期望转换为数组并添加key
- 测试步骤3：空对象输入 >> 期望返回空数组
- 测试步骤4：空数组输入 >> 期望返回空数组
- 测试步骤5：复杂嵌套对象 >> 期望正确处理并添加key

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

su('admin');

$biTest = new biTest();

r($biTest->prepareFieldSettingFormDataTest((object)array('field1' => array('name' => 'Field One', 'type' => 'string'), 'field2' => array('name' => 'Field Two', 'type' => 'number')))) && p('0:key,name') && e('field1,Field One');
r($biTest->prepareFieldSettingFormDataTest(array('field1' => array('name' => 'Field One', 'type' => 'string'), 'field2' => array('name' => 'Field Two', 'type' => 'number')))) && p('1:key,name') && e('field2,Field Two');
r($biTest->prepareFieldSettingFormDataTest(new stdClass())) && p() && e('0');
r($biTest->prepareFieldSettingFormDataTest(array())) && p() && e('0');
r($biTest->prepareFieldSettingFormDataTest((object)array('complexField' => array('name' => 'Complex Field', 'type' => 'object', 'nested' => array('subfield' => 'value'))))) && p('0:key,name,nested') && e('complexField,Complex Field,Array');