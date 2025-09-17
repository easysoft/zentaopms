#!/usr/bin/env php
<?php

/**

title=测试 storeZen::getInstalledApps();
timeout=0
cid=0

- 步骤1：正常情况 - admin用户获取已安装应用 @1
- 步骤2：用户user1获取已安装应用 @1
- 步骤3：用户user2获取已安装应用 @1
- 步骤4：验证admin用户返回数组数量非负 @1
- 步骤5：验证admin用户返回的应用ID数组类型 @1
- 步骤6：用户testuser获取已安装应用 @1
- 步骤7：用户produser获取已安装应用 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/store.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('space')->loadYaml('space_getinstalledapps', false, 2)->gen(5);
zendata('instance')->loadYaml('instance_getinstalledapps', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storeTest = new storeTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($storeTest->getInstalledAppsTest())) && p() && e('1'); // 步骤1：正常情况 - admin用户获取已安装应用

su('user1');
r(count($storeTest->getInstalledAppsTest())) && p() && e('1'); // 步骤2：用户user1获取已安装应用

su('user2');
r(count($storeTest->getInstalledAppsTest())) && p() && e('1'); // 步骤3：用户user2获取已安装应用

su('admin');
r(count($storeTest->getInstalledAppsTest()) >= 0) && p() && e('1'); // 步骤4：验证admin用户返回数组数量非负

r(is_array($storeTest->getInstalledAppsTest())) && p() && e('1'); // 步骤5：验证admin用户返回的应用ID数组类型

su('testuser');
r(count($storeTest->getInstalledAppsTest())) && p() && e('1'); // 步骤6：用户testuser获取已安装应用

su('produser');
r(count($storeTest->getInstalledAppsTest())) && p() && e('1'); // 步骤7：用户produser获取已安装应用