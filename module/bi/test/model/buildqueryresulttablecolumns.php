#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::buildQueryResultTableColumns();
timeout=0
cid=0

- 测试步骤3：空数组输入测试 @~~

*/

$biTest = new biTest();

r($biTest->buildQueryResultTableColumnsTest(array(
    'id' => array('zh-cn' => '编号', 'en' => 'ID', 'type' => 'int'),
    'name' => array('zh-cn' => '名称', 'en' => 'Name', 'type' => 'string')
))) && p('0:name,0:title;1:name,1:title') && e('id,编号;name,名称'); // 测试步骤1：正常字段设置转换

r($biTest->buildQueryResultTableColumnsTest(array(
    'code' => array('type' => 'string'),
    'status' => array('type' => 'int')
))) && p('0:name,0:title;1:name,1:title') && e('code,code;status,status'); // 测试步骤2：无多语言设置情况

r($biTest->buildQueryResultTableColumnsTest(array())) && p() && e('~~'); // 测试步骤3：空数组输入测试

r($biTest->buildQueryResultTableColumnsTest(array(
    'created_time' => array('zh-cn' => '创建时间', 'en' => 'Created Time', 'type' => 'datetime'),
    'user_count' => array('zh-cn' => '用户数量', 'en' => 'User Count', 'type' => 'int'),
    'description' => array('zh-cn' => '描述', 'en' => 'Description', 'type' => 'text')
))) && p() && e(3); // 测试步骤4：多种数据类型测试

r($biTest->buildQueryResultTableColumnsTest(array(
    'field_name' => array('zh-cn' => '字段名@#$%^&*()', 'en' => 'Field Name!@#', 'type' => 'string'),
    'test_123' => array('zh-cn' => '测试字段_中文', 'en' => 'Test Field_EN', 'type' => 'varchar')
))) && p('0:title;1:title') && e('字段名@#$%^&*();测试字段_中文'); // 测试步骤5：特殊字符和边界值测试