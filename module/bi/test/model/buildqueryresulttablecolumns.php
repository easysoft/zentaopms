#!/usr/bin/env php
<?php

/**

title=测试 biModel::buildQueryResultTableColumns();
timeout=0
cid=0

- 执行biTest模块的buildQueryResultTableColumnsTest方法，参数是array  @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$biTest = new biTest();

// 步骤1：空字段设置边界测试
r(count($biTest->buildQueryResultTableColumnsTest(array()))) && p() && e('0');

// 步骤2：单个字段正常处理测试
r($biTest->buildQueryResultTableColumnsTest(array(
    'id' => array('zh-cn' => '编号', 'en' => 'ID', 'type' => 'int')
))) && p('0:name,0:title') && e('id,编号');

// 步骤3：多个字段混合类型测试
r(count($biTest->buildQueryResultTableColumnsTest(array(
    'id' => array('zh-cn' => '编号', 'en' => 'ID', 'type' => 'int'),
    'name' => array('zh-cn' => '名称', 'en' => 'Name', 'type' => 'string'),
    'status' => array('zh-cn' => '状态', 'en' => 'Status', 'type' => 'int')
)))) && p() && e('3');

// 步骤4：缺少语言标识字段测试
r($biTest->buildQueryResultTableColumnsTest(array(
    'code' => array('type' => 'string'),
    'score' => array('type' => 'decimal')
))) && p('0:name,0:title,1:name,1:title') && e('code,code,score,score');

// 步骤5：多语言环境切换测试
global $app;
$app->setClientLang('en');
r($biTest->buildQueryResultTableColumnsTest(array(
    'user_id' => array('zh-cn' => '用户编号', 'en' => 'User ID', 'type' => 'int')
))) && p('0:title') && e('User ID');

// 步骤6：特殊字符字段名处理测试
$app->setClientLang('zh-cn');
r($biTest->buildQueryResultTableColumnsTest(array(
    'field_with_underscore' => array('zh-cn' => '下划线字段', 'type' => 'string'),
    'field-with-dash' => array('zh-cn' => '连字符字段', 'type' => 'string')
))) && p('0:name,1:name') && e('field_with_underscore,field-with-dash');

// 步骤7：复杂字段设置数据类型测试
r(count($biTest->buildQueryResultTableColumnsTest(array(
    'int_field' => array('zh-cn' => '整数字段', 'type' => 'int'),
    'string_field' => array('zh-cn' => '字符串字段', 'type' => 'string'),
    'datetime_field' => array('zh-cn' => '日期时间字段', 'type' => 'datetime'),
    'text_field' => array('zh-cn' => '文本字段', 'type' => 'text'),
    'decimal_field' => array('zh-cn' => '小数字段', 'type' => 'decimal')
)))) && p() && e('5');

// 步骤8：不存在的语言标识测试
$app->setClientLang('fr');
r($biTest->buildQueryResultTableColumnsTest(array(
    'test_field' => array('zh-cn' => '测试字段', 'en' => 'Test Field', 'type' => 'string')
))) && p('0:title') && e('test_field');

// 步骤9：sortType属性验证测试
$app->setClientLang('zh-cn');
r($biTest->buildQueryResultTableColumnsTest(array(
    'field1' => array('zh-cn' => '字段1', 'type' => 'string')
))) && p('0:sortType') && e('~~');

// 步骤10：大量字段性能测试
r(count($biTest->buildQueryResultTableColumnsTest(array(
    'field1' => array('zh-cn' => '字段1', 'type' => 'string'),
    'field2' => array('zh-cn' => '字段2', 'type' => 'int'),
    'field3' => array('zh-cn' => '字段3', 'type' => 'datetime'),
    'field4' => array('zh-cn' => '字段4', 'type' => 'text'),
    'field5' => array('zh-cn' => '字段5', 'type' => 'decimal'),
    'field6' => array('zh-cn' => '字段6', 'type' => 'string'),
    'field7' => array('zh-cn' => '字段7', 'type' => 'int'),
    'field8' => array('zh-cn' => '字段8', 'type' => 'string')
)))) && p() && e('8');