#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getZentaoObjectList();
timeout=0
cid=15789

- 步骤1：默认配置下返回完整对象列表 @7
- 步骤2：关闭enableER配置时不包含epic属性epic @~~
- 步骤3：关闭URAndSR配置时不包含requirement属性requirement @~~
- 步骤4：同时关闭enableER和URAndSR配置 @5
- 步骤5：验证返回的数据类型为数组 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
global $config;
$config->enableER = true;
$config->URAndSR  = true;
r(count($convertTest->getZentaoObjectListTest())) && p() && e('7'); // 步骤1：默认配置下返回完整对象列表

$config->enableER = false;
$config->URAndSR  = true;
r($convertTest->getZentaoObjectListTest()) && p('epic') && e('~~'); // 步骤2：关闭enableER配置时不包含epic

$config->enableER = true;
$config->URAndSR  = false;
r($convertTest->getZentaoObjectListTest()) && p('requirement') && e('~~'); // 步骤3：关闭URAndSR配置时不包含requirement

$config->enableER = false;
$config->URAndSR  = false;
r(count($convertTest->getZentaoObjectListTest())) && p() && e('5'); // 步骤4：同时关闭enableER和URAndSR配置

$config->enableER = true;
$config->URAndSR  = true;
r(is_array($convertTest->getZentaoObjectListTest())) && p() && e('1'); // 步骤5：验证返回的数据类型为数组