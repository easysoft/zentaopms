#!/usr/bin/env php
<?php

/**

title=测试 backupZen::getBackupList();
timeout=0
cid=0

- 步骤1：测试获取备份列表返回类型为数组 @array
- 步骤2：测试创建备份文件后列表数量大于0 @1
- 步骤4：测试备份列表元素包含name属性 @1
- 步骤5：测试备份列表元素包含files属性 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$backupTest = new backupZenTest();

// 4. 准备测试数据 - 创建测试备份文件
global $tester;
$backupPath = $tester->loadModel('backup')->getBackupPath();
$testBackupFile = $backupPath . 'test_getbackuplist_' . time() . '.sql.php';
file_put_contents($testBackupFile, '<?php die(); ?>');

// 5. 测试步骤 - 至少5个测试步骤
r(gettype($backupTest->getBackupListTest())) && p() && e('array'); // 步骤1：测试获取备份列表返回类型为数组
r(count($backupTest->getBackupListTest()) > 0) && p() && e('1'); // 步骤2：测试创建备份文件后列表数量大于0
$result = $backupTest->getBackupListTest(); $firstItem = reset($result); r(property_exists($firstItem, 'time') ? 1 : 0) && p() && e('1'); // 步骤3：测试备份列表元素包含time属性
r(property_exists($firstItem, 'name') ? 1 : 0) && p() && e('1'); // 步骤4：测试备份列表元素包含name属性
r(property_exists($firstItem, 'files') ? 1 : 0) && p() && e('1'); // 步骤5：测试备份列表元素包含files属性

// 6. 清理测试文件
if(file_exists($testBackupFile)) unlink($testBackupFile);