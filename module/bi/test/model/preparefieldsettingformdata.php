#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareFieldSettingFormData();
timeout=0
cid=0

- 执行biTest模块的prepareFieldSettingFormDataTest方法 
 - 第0条的key属性 @field1
 - 第0条的name属性 @Field One
- 执行biTest模块的prepareFieldSettingFormDataTest方法，参数是array 
 - 第1条的key属性 @field2
 - 第1条的name属性 @Field Two
- 执行biTest模块的prepareFieldSettingFormDataTest方法，参数是new stdClass  @0
- 执行biTest模块的prepareFieldSettingFormDataTest方法，参数是array  @0
- 执行biTest模块的prepareFieldSettingFormDataTest方法 
 - 第0条的key属性 @complexField
 - 第0条的name属性 @Complex Field
 - 第0条的type属性 @object

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

su('admin');

$biTest = new biTest();

r($biTest->prepareFieldSettingFormDataTest((object)array('field1' => array('name' => 'Field One', 'type' => 'string'), 'field2' => array('name' => 'Field Two', 'type' => 'number')))) && p('0:key,name') && e('field1,Field One');
r($biTest->prepareFieldSettingFormDataTest(array('field1' => array('name' => 'Field One', 'type' => 'string'), 'field2' => array('name' => 'Field Two', 'type' => 'number')))) && p('1:key,name') && e('field2,Field Two');
r($biTest->prepareFieldSettingFormDataTest(new stdClass())) && p() && e('0');
r($biTest->prepareFieldSettingFormDataTest(array())) && p() && e('0');
r($biTest->prepareFieldSettingFormDataTest((object)array('complexField' => array('name' => 'Complex Field', 'type' => 'object', 'nested' => array('subfield' => 'value'))))) && p('0:key,name,type') && e('complexField,Complex Field,object');