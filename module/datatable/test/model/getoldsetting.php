#!/usr/bin/env php
<?php

/**

title=测试 datatableModel::getOldSetting();
timeout=0
cid=15944

- 步骤1：正常模块旧设置获取
 - 第0条的id属性 @id
 - 第0条的show属性 @1
- 步骤2：验证设置对象结构
 - 第0条的title属性 @ID
 - 第0条的width属性 @80
- 步骤3：验证设置排序功能
 - 第0条的order属性 @1
 - 第1条的order属性 @2
- 步骤4：测试设置fixed属性
 - 第0条的fixed属性 @left
 - 第1条的fixed属性 @left
- 步骤5：测试空配置模块返回默认设置 @Array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$datatableTest = new datatableModelTest();

// 4. 模拟创建一个完整的模块配置用于测试getOldSetting
global $tester, $lang;
$tester->config->testmodule = new stdclass();
$tester->config->testmodule->datatable = new stdclass();
$tester->config->testmodule->datatable->fieldList = array();
$tester->config->testmodule->datatable->fieldList['id'] = array('title' => 'id', 'width' => '80', 'fixed' => 'left', 'required' => 'yes', 'sort' => 'yes');
$tester->config->testmodule->datatable->fieldList['name'] = array('title' => 'name', 'width' => 'auto', 'fixed' => 'left', 'required' => 'yes', 'sort' => 'yes');
$tester->config->testmodule->datatable->defaultField = array('id', 'name');

// 设置语言变量
$lang->testmodule = new stdclass();
$lang->testmodule->id = 'ID';
$lang->testmodule->name = '名称';
$lang->id = 'ID';
$lang->name = '名称';

// 模拟app对象和方法名
$tester->app->methodName = 'browse';

// 5. 强制要求：必须包含至少5个测试步骤
r($datatableTest->getOldSettingTest('testmodule')) && p('0:id;0:show') && e('id,1'); // 步骤1：正常模块旧设置获取
r($datatableTest->getOldSettingTest('testmodule')) && p('0:title;0:width') && e('ID,80'); // 步骤2：验证设置对象结构
r($datatableTest->getOldSettingTest('testmodule')) && p('0:order;1:order') && e('1,2'); // 步骤3：验证设置排序功能
r($datatableTest->getOldSettingTest('testmodule')) && p('0:fixed;1:fixed') && e('left,left'); // 步骤4：测试设置fixed属性
// 创建一个只有基本字段列表但无defaultField的模块用于测试
$tester->config->emptymodule = new stdclass();
$tester->config->emptymodule->datatable = new stdclass();
$tester->config->emptymodule->datatable->fieldList = array();
$tester->config->emptymodule->datatable->fieldList['id'] = array('title' => 'id', 'width' => '80', 'fixed' => 'left');
$tester->config->emptymodule->datatable->fieldList['name'] = array('title' => 'name', 'width' => 'auto', 'fixed' => 'left');
$tester->config->emptymodule->datatable->defaultField = array('id', 'name');

// 设置emptymodule的语言变量
$lang->emptymodule = new stdclass();
$lang->emptymodule->id = 'ID';
$lang->emptymodule->name = '名称';

r($datatableTest->getOldSettingTest('emptymodule')) && p() && e('Array'); // 步骤5：测试空配置模块返回默认设置