#!/usr/bin/env php
<?php

/**

title=测试 datatableModel::getOldFieldList();
timeout=0
cid=15943

- 步骤1：正常模块字段列表获取
 - 第id条的title属性 @ID
 - 第name条的title属性 @名称
- 步骤2：验证字段宽度配置
 - 第id条的width属性 @80
 - 第name条的width属性 @auto
- 步骤3：验证字段fixed属性
 - 第id条的fixed属性 @left
 - 第name条的fixed属性 @left
- 步骤4：验证字段required属性
 - 第id条的required属性 @yes
 - 第name条的required属性 @yes
- 步骤5：测试空配置模块返回空数组 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/datatable.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$datatableTest = new datatableTest();

// 4. 模拟创建一个简单的模块配置用于测试
global $tester, $lang;
$tester->config->testmodule = new stdclass();
$tester->config->testmodule->datatable = new stdclass();
$tester->config->testmodule->datatable->fieldList = array();
$tester->config->testmodule->datatable->fieldList['id'] = array('title' => 'id', 'width' => '80', 'fixed' => 'left', 'required' => 'yes');
$tester->config->testmodule->datatable->fieldList['name'] = array('title' => 'name', 'width' => 'auto', 'fixed' => 'left', 'required' => 'yes');

// 设置语言变量
$lang->testmodule = new stdclass();
$lang->testmodule->id = 'ID';
$lang->testmodule->name = '名称';
$lang->id = 'ID';
$lang->name = '名称';

// 5. 强制要求：必须包含至少5个测试步骤
r($datatableTest->getOldFieldListTest('testmodule')) && p('id:title;name:title') && e('ID,名称'); // 步骤1：正常模块字段列表获取
r($datatableTest->getOldFieldListTest('testmodule')) && p('id:width;name:width') && e('80,auto'); // 步骤2：验证字段宽度配置
r($datatableTest->getOldFieldListTest('testmodule')) && p('id:fixed;name:fixed') && e('left,left'); // 步骤3：验证字段fixed属性
r($datatableTest->getOldFieldListTest('testmodule')) && p('id:required;name:required') && e('yes,yes'); // 步骤4：验证字段required属性
// 创建一个空配置的模块用于测试
$tester->config->emptymodule = new stdclass();
$tester->config->emptymodule->datatable = new stdclass();
$tester->config->emptymodule->datatable->fieldList = array();

r($datatableTest->getOldFieldListTest('emptymodule')) && p() && e('0'); // 步骤5：测试空配置模块返回空数组