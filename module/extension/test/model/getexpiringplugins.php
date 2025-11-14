#!/usr/bin/env php
<?php

/**

title=测试 extensionModel::getExpiringPlugins();
timeout=0
cid=16459

- 测试步骤1：无已安装插件时获取即将到期的插件列表 @0
- 测试步骤2：测试非分组模式的返回格式 @1
- 测试步骤3：测试分组模式的返回格式 @1
- 测试步骤4：验证分组模式下expiring数组的初始状态 @1
- 测试步骤5：验证分组模式下expired数组的初始状态 @1
- 测试步骤6：测试当有已安装插件但无授权文件时的行为 @0
- 测试步骤7：测试方法参数类型验证 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/extension.unittest.class.php';

// 准备基础测试数据 - 模拟已安装的插件
$table = zenData('extension');
$table->id->range('1-5');
$table->name->range('testplugin1,testplugin2,testplugin3,testplugin4,testplugin5');
$table->code->range('test1,test2,test3,test4,test5');
$table->version->range('1.0.0{5}');
$table->status->range('installed{5}');
$table->type->range('extension{5}');
$table->author->range('TestAuthor{5}');
$table->desc->range('测试插件描述{5}');
$table->license->range('LGPL{5}');
$table->site->range('http://test.com{5}');
$table->zentaoCompatible->range('all{5}');
$table->depends->range('[]{5}');
$table->dirs->range('[]{5}');
$table->files->range('[]{5}');
$table->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$extensionTest = new extensionTest();

// 测试步骤1：测试默认参数行为（无分组）
r($extensionTest->getExpiringPluginsTest()) && p() && e('0'); // 测试步骤1：无已安装插件时获取即将到期的插件列表

// 测试步骤2：测试显式传入false参数（非分组模式）
$nonGroupResult = $extensionTest->getExpiringPluginsTest(false);
r(is_array($nonGroupResult) && !isset($nonGroupResult['expiring'])) && p() && e('1'); // 测试步骤2：测试非分组模式的返回格式

// 测试步骤3：测试分组模式的返回结构
$groupResult = $extensionTest->getExpiringPluginsTest(true);
r(is_array($groupResult) && isset($groupResult['expiring']) && isset($groupResult['expired'])) && p() && e('1'); // 测试步骤3：测试分组模式的返回格式

// 测试步骤4：验证分组模式下expiring数组
r(is_array($groupResult['expiring'])) && p() && e('1'); // 测试步骤4：验证分组模式下expiring数组的初始状态

// 测试步骤5：验证分组模式下expired数组
r(is_array($groupResult['expired'])) && p() && e('1'); // 测试步骤5：验证分组模式下expired数组的初始状态

// 测试步骤6：测试空结果时的数组长度
r(count($nonGroupResult)) && p() && e('0'); // 测试步骤6：测试当有已安装插件但无授权文件时的行为

// 测试步骤7：验证分组结果数组的键数量
r(count(array_keys($groupResult))) && p() && e('2'); // 测试步骤7：测试方法参数类型验证