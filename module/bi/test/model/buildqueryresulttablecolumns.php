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

// 测试步骤1：空字段设置数组边界测试
r(count($biTest->buildQueryResultTableColumnsTest(array()))) && p() && e('0');

// 测试步骤2：单个字段正常构建测试
r($biTest->buildQueryResultTableColumnsTest(array(
    'id' => array('zh-cn' => '编号', 'en' => 'ID', 'type' => 'int')
))) && p('0:name,0:title,0:sortType') && e('id,编号,~~');

// 测试步骤3：多语言环境字段标题获取测试
global $app;
$originalLang = $app->getClientLang();
$app->setClientLang('en');
r($biTest->buildQueryResultTableColumnsTest(array(
    'user_id' => array('zh-cn' => '用户编号', 'en' => 'User ID', 'type' => 'int')
))) && p('0:title') && e('User ID');
$app->setClientLang($originalLang);

// 测试步骤4：缺少语言标识字段回退测试
r($biTest->buildQueryResultTableColumnsTest(array(
    'code' => array('type' => 'string'),
    'score' => array('type' => 'decimal')
))) && p('0:name,0:title,1:name,1:title') && e('code,code,score,score');

// 测试步骤5：列属性配置验证测试
r($biTest->buildQueryResultTableColumnsTest(array(
    'name' => array('zh-cn' => '名称', 'type' => 'string'),
    'status' => array('zh-cn' => '状态', 'type' => 'int'),
    'date' => array('zh-cn' => '日期', 'type' => 'datetime')
))) && p('0:sortType,1:sortType,2:sortType') && e('~~,~~,~~');