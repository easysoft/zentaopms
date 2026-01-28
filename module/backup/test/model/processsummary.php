#!/usr/bin/env php
<?php

/**

title=测试 backupModel::processSummary();
timeout=0
cid=0

- 执行backupTest模块的processSummaryTest方法，参数是$backupFile, 5, 1024, array  @1
- 执行$summaryFile), true)['test.file']['allCount'] @10
- 执行backupTest模块的processSummaryTest方法，参数是$backupFile, 3, 512, array  @1
- 执行$summaryFile), true)['test.file']['errorFiles']
 -  @error1.txt
 - 属性1 @error2.txt
- 执行backupTest模块的processSummaryTest方法，参数是$backupFile, 7, 2048, array  @1
- 执行backupTest模块的processSummaryTest方法，参数是$backupFile, 0, 0, array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$backupTest = new backupModelTest();

$testDir     = sys_get_temp_dir() . '/backup_test_' . time() . '/';
$backupFile  = $testDir . 'test.file';
$summaryFile = $testDir . 'summary';

if(!is_dir($testDir)) mkdir($testDir, 0777, true);

r($backupTest->processSummaryTest($backupFile, 5, 1024, array(), 10, 'add')) && p() && e('1');
r(json_decode(file_get_contents($summaryFile), true)['test.file']['allCount']) && p() && e('10');
r($backupTest->processSummaryTest($backupFile, 3, 512, array('error1.txt', 'error2.txt'), 8, 'add')) && p() && e('1');
r(json_decode(file_get_contents($summaryFile), true)['test.file']['errorFiles']) && p('0,1') && e('error1.txt,error2.txt');
r($backupTest->processSummaryTest($backupFile, 7, 2048, array(), 15, 'add')) && p() && e('1');
r($backupTest->processSummaryTest($backupFile, 0, 0, array(), 0, 'delete')) && p() && e('1');

if(file_exists($summaryFile)) unlink($summaryFile);
if(is_dir($testDir)) rmdir($testDir);