#!/usr/bin/env php
<?php

/**

title=测试 extensionModel::saveExtension();
timeout=0
cid=16471

- 步骤1：正常情况 - 保存插件类型 @1
- 步骤2：patch类型 - 保存补丁类型 @1
- 步骤3：边界值 - 空类型参数 @1
- 步骤4：业务规则 - 不同插件代号 @1
- 步骤5：权限验证 - 自定义类型 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/extension.unittest.class.php';

// 2. zendata数据准备
zenData('extension')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$extensionTest = new extensionTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($extensionTest->saveExtensionTest('testplugin', 'extension')) && p() && e(1); // 步骤1：正常情况 - 保存插件类型
r($extensionTest->saveExtensionTest('testpatch', 'patch')) && p() && e(1); // 步骤2：patch类型 - 保存补丁类型
r($extensionTest->saveExtensionTest('defaulttype', '')) && p() && e(1); // 步骤3：边界值 - 空类型参数
r($extensionTest->saveExtensionTest('anotherplugin', 'extension')) && p() && e(1); // 步骤4：业务规则 - 不同插件代号
r($extensionTest->saveExtensionTest('lastplugin', 'custom')) && p() && e(1); // 步骤5：权限验证 - 自定义类型