#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::installExtension();
timeout=0
cid=0

- 步骤1：正常安装新插件（由于缺少文件会失败） @0
- 步骤2：升级已存在插件（由于缺少文件会失败） @0
- 步骤3：安装不存在的插件 @0
- 步骤4：安装有数据库文件的插件（由于缺少文件会失败） @0
- 步骤5：安装主题插件（由于缺少文件会失败） @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/extension.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$extensionTest = new extensionTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($extensionTest->installExtensionTest('testplugin1', 'extension', 'no')) && p() && e('0'); // 步骤1：正常安装新插件（由于缺少文件会失败）
r($extensionTest->installExtensionTest('sampleplugin1', 'extension', 'yes')) && p() && e('0'); // 步骤2：升级已存在插件（由于缺少文件会失败）
r($extensionTest->installExtensionTest('nonexistent', 'extension', 'no')) && p() && e('0'); // 步骤3：安装不存在的插件
r($extensionTest->installExtensionTest('testplugin2', 'extension', 'no')) && p() && e('0'); // 步骤4：安装有数据库文件的插件（由于缺少文件会失败）
r($extensionTest->installExtensionTest('testtheme1', 'theme', 'no')) && p() && e('0'); // 步骤5：安装主题插件（由于缺少文件会失败）