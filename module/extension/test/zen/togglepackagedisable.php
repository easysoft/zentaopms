#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::togglePackageDisable();
timeout=0
cid=0

- 步骤1：正常禁用插件 @0
- 步骤2：正常激活插件 @0
- 步骤3：不存在插件禁用 @0
- 步骤4：不存在插件激活 @0
- 步骤5：无效action参数 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/extension.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('extension');
$table->code->range('testplugin,sample,demo');
$table->name->range('测试插件,示例插件,演示插件');
$table->status->range('installed{3}');
$table->version->range('1.0,1.1,1.2');
$table->type->range('extension{3}');
$table->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$extensionTest = new extensionTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($extensionTest->togglePackageDisableTest('testplugin', 'disabled')) && p() && e('0');        // 步骤1：正常禁用插件
r($extensionTest->togglePackageDisableTest('testplugin', 'active')) && p() && e('0');          // 步骤2：正常激活插件
r($extensionTest->togglePackageDisableTest('nonexistent', 'disabled')) && p() && e('0');       // 步骤3：不存在插件禁用
r($extensionTest->togglePackageDisableTest('nonexistent', 'active')) && p() && e('0');         // 步骤4：不存在插件激活
r($extensionTest->togglePackageDisableTest('testplugin', 'invalid')) && p() && e('0');         // 步骤5：无效action参数