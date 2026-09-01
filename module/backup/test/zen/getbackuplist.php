#!/usr/bin/env php
<?php

/**

title=测试 backupZen::getBackupList();
timeout=0
cid=15146

- 步骤1：测试获取备份列表返回类型为数组 @array
- 步骤2：测试创建备份文件后列表数量大于0 @1
- 步骤3：测试备份列表元素包含time属性 @1
- 步骤4：测试备份列表元素包含name属性 @1
- 步骤5：测试备份列表元素包含files属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$backupTest = new backupZenTest();

/* 使用独立可写的备份目录，避免影响其他测试 */
global $tester;
$backupPath = '/tmp/zentao_backup_test_' . getmypid();
if(!is_dir($backupPath)) mkdir($backupPath, 0777, true);
$tester->config->backup->settingDir = $backupPath;
$backupPath = $tester->loadModel('backup')->getBackupPath();
$testBackupFile = $backupPath . 'test_getbackuplist_' . time() . '.sql.php';
file_put_contents($testBackupFile, '<?php die(); ?>');

r(gettype($backupTest->getBackupListTest())) && p() && e('array'); // 步骤1：测试获取备份列表返回类型为数组
r(count($backupTest->getBackupListTest()) > 0 ? 1 : 0) && p() && e('1'); // 步骤2：测试创建备份文件后列表数量大于0
$result = $backupTest->getBackupListTest();
$firstItem = reset($result);
r(property_exists($firstItem, 'time') ? 1 : 0) && p() && e('1'); // 步骤3：测试备份列表元素包含time属性
r(property_exists($firstItem, 'name') ? 1 : 0) && p() && e('1'); // 步骤4：测试备份列表元素包含name属性
r(property_exists($firstItem, 'files') ? 1 : 0) && p() && e('1'); // 步骤5：测试备份列表元素包含files属性

/* 清理测试文件 */
if(file_exists($testBackupFile)) unlink($testBackupFile);
if(is_dir($backupPath)) rmdir($backupPath);
