#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getCustomCategories();
timeout=0
cid=15029

- 步骤1：空数据情况 - 期望返回空数组 @0
- 步骤2：有数据情况 - 期望返回3个配置项 @3
- 步骤3：检查具体配置项值属性category1 @开发工具
- 步骤4：检查第二个配置项值属性category2 @数据分析
- 步骤5：检查第三个配置项值属性category3 @项目管理

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. 清空config表数据
$table = zenData('config');
$table->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->getCustomCategoriesTest()) && p() && e(0); // 步骤1：空数据情况 - 期望返回空数组

// 插入测试数据
global $tester;
$dao = $tester->dao;
$dao->insert(TABLE_CONFIG)->data(array('vision' => 'rnd', 'owner' => '', 'module' => 'ai', 'section' => 'miniProgram', 'key' => 'category1', 'value' => '开发工具'))->exec();
$dao->insert(TABLE_CONFIG)->data(array('vision' => 'rnd', 'owner' => '', 'module' => 'ai', 'section' => 'miniProgram', 'key' => 'category2', 'value' => '数据分析'))->exec();
$dao->insert(TABLE_CONFIG)->data(array('vision' => 'rnd', 'owner' => '', 'module' => 'ai', 'section' => 'miniProgram', 'key' => 'category3', 'value' => '项目管理'))->exec();
$dao->insert(TABLE_CONFIG)->data(array('vision' => 'rnd', 'owner' => '', 'module' => 'user', 'section' => 'setting', 'key' => 'test', 'value' => '测试'))->exec();

$result = $aiTest->getCustomCategoriesTest();
r(count($result)) && p() && e(3); // 步骤2：有数据情况 - 期望返回3个配置项
r($result) && p('category1') && e('开发工具'); // 步骤3：检查具体配置项值
r($result) && p('category2') && e('数据分析'); // 步骤4：检查第二个配置项值
r($result) && p('category3') && e('项目管理'); // 步骤5：检查第三个配置项值