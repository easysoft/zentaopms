#!/usr/bin/env php
<?php

/**

title=测试 biModel::buildQueryResultTableColumns();
timeout=0
cid=0

- 步骤3：空数组输入边界值测试 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$biTest = new biTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($biTest->buildQueryResultTableColumnsTest(array(
    'id' => array('zh-cn' => '编号', 'en' => 'ID', 'type' => 'int'),
    'name' => array('zh-cn' => '名称', 'en' => 'Name', 'type' => 'string')
))) && p('0:name,0:title,0:sortType;1:name,1:title,1:sortType') && e('id,编号,0;name,名称,0'); // 步骤1：正常多语言字段配置转换测试

r($biTest->buildQueryResultTableColumnsTest(array(
    'code' => array('type' => 'string'),
    'status' => array('type' => 'int')
))) && p('0:name,0:title,0:sortType;1:name,1:title,1:sortType') && e('code,code,0;status,status,0'); // 步骤2：缺少多语言配置的字段处理测试

r($biTest->buildQueryResultTableColumnsTest(array())) && p() && e('0'); // 步骤3：空数组输入边界值测试

r($biTest->buildQueryResultTableColumnsTest(array(
    'user_id' => array('zh-cn' => '用户ID', 'type' => 'int')
))) && p('0:name,0:title,0:sortType') && e('user_id,用户ID,0'); // 步骤4：单个字段配置功能测试

r($biTest->buildQueryResultTableColumnsTest(array(
    'field_name' => array('zh-cn' => '字段名@#$%^&*()', 'type' => 'string')
))) && p('0:name,0:title,0:sortType') && e('field_name,字段名@#$%^&*(),0'); // 步骤5：特殊字符和复杂标题处理测试