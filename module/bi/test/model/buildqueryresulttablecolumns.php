#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::buildQueryResultTableColumns();
timeout=0
cid=0

- 测试步骤3：空数组输入测试 @0

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

r($biTest->buildQueryResultTableColumnsTest(array())) && p() && e(0); // 测试步骤3：空数组输入测试

r($biTest->buildQueryResultTableColumnsTest(array(
    'created_time' => array('zh-cn' => '创建时间', 'en' => 'Created Time', 'type' => 'datetime'),
    'user_count' => array('zh-cn' => '用户数量', 'en' => 'User Count', 'type' => 'int'),
    'description' => array('zh-cn' => '描述', 'en' => 'Description', 'type' => 'text')
))) && p() && e(3); // 测试步骤4：多种数据类型测试

r($biTest->buildQueryResultTableColumnsTest(array(
    'field_name' => array('zh-cn' => '字段名@#$%^&*()', 'en' => 'Field Name!@#', 'type' => 'string'),
    'test_123' => array('zh-cn' => '测试字段_中文', 'en' => 'Test Field_EN', 'type' => 'varchar')
))) && p('0:title;1:title') && e('字段名@#$%^&*();测试字段_中文'); // 测试步骤5：特殊字符和边界值测试

r($biTest->buildQueryResultTableColumnsTest(array(
    'priority' => array('zh-cn' => '优先级', 'zh-tw' => '優先級', 'en' => 'Priority', 'type' => 'int')
))) && p('0:name,0:title') && e('priority,优先级'); // 测试步骤6：语言设置优先级测试

r($biTest->buildQueryResultTableColumnsTest(array(
    'field1' => array('zh-cn' => '字段1', 'type' => 'string'),
    'field2' => array('zh-cn' => '字段2', 'type' => 'int'),
    'field3' => array('zh-cn' => '字段3', 'type' => 'text'),
    'field4' => array('zh-cn' => '字段4', 'type' => 'datetime'),
    'field5' => array('zh-cn' => '字段5', 'type' => 'varchar')
))) && p() && e(5); // 测试步骤7：大量字段处理测试