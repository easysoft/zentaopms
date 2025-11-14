#!/usr/bin/env php
<?php

/**

title=测试 backupModel::getBackupDirProgress();
timeout=0
cid=15132

- 执行backupTest模块的getBackupDirProgressTest方法，参数是$testBackupName1
 - 属性allCount @0
 - 属性count @0
- 执行backupTest模块的getBackupDirProgressTest方法，参数是$testBackupName2
 - 属性size @1234
 - 属性allCount @10
 - 属性count @5
- 执行backupTest模块的getBackupDirProgressTest方法，参数是$testBackupName3  @0
- 执行backupTest模块的getBackupDirProgressTest方法，参数是$testBackupName4  @0
- 执行backupTest模块的getBackupDirProgressTest方法，参数是$testBackupName5
 - 属性allCount @100
 - 属性count @75
 - 属性size @2048576
 - 属性progress @75.5
 - 属性status @processing
- 执行backupTest模块的getBackupDirProgressTest方法，参数是''
 - 属性allCount @0
 - 属性count @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/backup.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$backupTest = new backupTest();

// 4. 执行测试步骤（至少5个）

// 测试步骤1：临时日志文件不存在时，返回默认值
$testBackupName1 = sys_get_temp_dir() . '/backup_test_nonexistent_' . time();
r($backupTest->getBackupDirProgressTest($testBackupName1)) && p('allCount,count') && e('0,0');

// 测试步骤2：临时日志文件存在且包含有效JSON数据
$testBackupName2 = sys_get_temp_dir() . '/backup_test_valid_' . time();
$summaryFile2 = $testBackupName2 . '.tmp.summary';
file_put_contents($summaryFile2, json_encode(array('size' => '1234', 'allCount' => 10, 'count' => 5)));
r($backupTest->getBackupDirProgressTest($testBackupName2)) && p('size,allCount,count') && e('1234,10,5');
if(file_exists($summaryFile2)) unlink($summaryFile2);

// 测试步骤3：临时日志文件存在但内容为空
$testBackupName3 = sys_get_temp_dir() . '/backup_test_empty_' . time();
$summaryFile3 = $testBackupName3 . '.tmp.summary';
file_put_contents($summaryFile3, '');
r($backupTest->getBackupDirProgressTest($testBackupName3)) && p() && e('0');
if(file_exists($summaryFile3)) unlink($summaryFile3);

// 测试步骤4：临时日志文件存在但包含无效JSON
$testBackupName4 = sys_get_temp_dir() . '/backup_test_invalid_' . time();
$summaryFile4 = $testBackupName4 . '.tmp.summary';
file_put_contents($summaryFile4, 'invalid json content');
r($backupTest->getBackupDirProgressTest($testBackupName4)) && p() && e('0');
if(file_exists($summaryFile4)) unlink($summaryFile4);

// 测试步骤5：测试包含多种字段的复杂JSON数据
$testBackupName5 = sys_get_temp_dir() . '/backup_test_complex_' . time();
$summaryFile5 = $testBackupName5 . '.tmp.summary';
$complexData = array(
    'allCount' => 100,
    'count' => 75,
    'size' => 2048576,
    'progress' => 75.5,
    'status' => 'processing'
);
file_put_contents($summaryFile5, json_encode($complexData));
r($backupTest->getBackupDirProgressTest($testBackupName5)) && p('allCount,count,size,progress,status') && e('100,75,2048576,75.5,processing');
if(file_exists($summaryFile5)) unlink($summaryFile5);

// 测试步骤6：测试空字符串作为backup参数
r($backupTest->getBackupDirProgressTest('')) && p('allCount,count') && e('0,0');