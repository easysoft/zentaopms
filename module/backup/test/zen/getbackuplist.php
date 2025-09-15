#!/usr/bin/env php
<?php

/**

title=测试 backupZen::getBackupList();
timeout=0
cid=0

- 执行backupTest模块的getBackupListTest方法  @0
- 执行$result['20240102']->name @20240102
- 执行$result @2
- 执行$result['20240101']->files @3
- 执行$backupNames
 -  @20240102
 - 属性1 @20240101

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/backup.unittest.class.php';

// 备份原始配置
global $config, $app;
if(!isset($config->backup)) $config->backup = new stdClass();
$originalBackupPath = isset($config->backup->settingDir) ? $config->backup->settingDir : '';

// 模拟用户登录
su('admin');

// 创建测试实例
$backupTest = new backupTest();

// 测试步骤1: 测试空备份目录情况
$config->backup->settingDir = '/tmp/empty_backup/';
r($backupTest->getBackupListTest()) && p() && e('0');

// 准备测试数据 - 设置包含测试文件的目录
$config->backup->settingDir = '/tmp/test_backup_files/';

// 测试步骤2: 测试包含SQL文件的备份目录
$result = $backupTest->getBackupListTest();
r($result['20240102']->name) && p() && e('20240102');

// 测试步骤3: 测试返回结果数量
$result = $backupTest->getBackupListTest();
r(count($result)) && p() && e('2');

// 测试步骤4: 测试20240101的文件数量（应该包含sql、file、code三个文件）
$result = $backupTest->getBackupListTest();
r(count($result['20240101']->files)) && p() && e('3');

// 测试步骤5: 测试结果按时间倒序排列
$result = $backupTest->getBackupListTest();
$backupNames = array_keys($result);
r($backupNames) && p('0,1') && e('20240102,20240101');

// 恢复原始配置
$config->backup->settingDir = $originalBackupPath;