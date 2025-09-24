#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

su('admin');

/**

title=测试 biModel->buildQueryResultTableColumns();
timeout=0
cid=1

- 执行biTest模块的buildQueryResultTableColumnsTest方法，参数是array  @0

*/

$biTest = new biTest();

// 步骤1：空字段边界测试 - 让第一个测试返回0
r(count($biTest->buildQueryResultTableColumnsTest(array()))) && p() && e('0');

// 步骤2：正常多字段场景测试第一个字段name属性
r($biTest->buildQueryResultTableColumnsTest(array(
    'id' => array('zh-cn' => '编号', 'en' => 'ID', 'type' => 'int'),
    'name' => array('zh-cn' => '名称', 'en' => 'Name', 'type' => 'string')
))) && p('0:name') && e('id');

// 步骤3：正常多字段场景测试第一个字段title属性
r($biTest->buildQueryResultTableColumnsTest(array(
    'id' => array('zh-cn' => '编号', 'en' => 'ID', 'type' => 'int'),
    'name' => array('zh-cn' => '名称', 'en' => 'Name', 'type' => 'string')
))) && p('0:title') && e('编号');

// 步骤4：正常多字段场景测试第二个字段name属性
r($biTest->buildQueryResultTableColumnsTest(array(
    'id' => array('zh-cn' => '编号', 'en' => 'ID', 'type' => 'int'),
    'name' => array('zh-cn' => '名称', 'en' => 'Name', 'type' => 'string')
))) && p('1:name') && e('name');

// 步骤5：正常多字段场景测试第二个字段title属性
r($biTest->buildQueryResultTableColumnsTest(array(
    'id' => array('zh-cn' => '编号', 'en' => 'ID', 'type' => 'int'),
    'name' => array('zh-cn' => '名称', 'en' => 'Name', 'type' => 'string')
))) && p('1:title') && e('名称');

// 步骤6：无语言标题字段测试第一个字段name
r($biTest->buildQueryResultTableColumnsTest(array(
    'code' => array('type' => 'string'),
    'status' => array('type' => 'int')
))) && p('0:name') && e('code');

// 步骤7：无语言标题字段测试第一个字段title
r($biTest->buildQueryResultTableColumnsTest(array(
    'code' => array('type' => 'string'),
    'status' => array('type' => 'int')
))) && p('0:title') && e('code');

// 步骤8：英文语言环境测试
global $app;
$app->setClientLang('en');
r($biTest->buildQueryResultTableColumnsTest(array(
    'id' => array('zh-cn' => '编号', 'en' => 'ID', 'type' => 'int')
))) && p('0:title') && e('ID');

// 步骤9：特殊字符字段名测试
$app->setClientLang('zh-cn');
r($biTest->buildQueryResultTableColumnsTest(array(
    'field_with_underscore' => array('zh-cn' => '下划线字段', 'type' => 'string')
))) && p('0:name') && e('field_with_underscore');

// 步骤10：单字段处理测试
r($biTest->buildQueryResultTableColumnsTest(array(
    'single_field' => array('zh-cn' => '单一字段', 'type' => 'text')
))) && p('0:name') && e('single_field');

// 步骤11：多种字段类型混合测试
r($biTest->buildQueryResultTableColumnsTest(array(
    'int_field' => array('zh-cn' => '整数字段', 'type' => 'int'),
    'string_field' => array('zh-cn' => '字符串字段', 'type' => 'string'),
    'datetime_field' => array('zh-cn' => '日期时间字段', 'type' => 'datetime'),
    'text_field' => array('zh-cn' => '文本字段', 'type' => 'text'),
    'decimal_field' => array('zh-cn' => '小数字段', 'type' => 'decimal')
))) && p() && e('5');