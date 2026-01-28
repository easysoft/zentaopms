#!/usr/bin/env php
<?php

/**

title=测试 cronModel::logCron();
timeout=0
cid=15884

- 执行cron模块的logCronTest方法，参数是'test_log_content'  @0
- 执行$cliFile @1
- 执行$cliFile), 'test_log_content') !== false @1
- 执行$cliFile), '<?php') === 0 @1
- 执行cron模块的logCronTest方法，参数是"multiline\nlog\ncontent"  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$cron = new cronModelTest();
$logRoot = $tester->app->getLogRoot();
$dateStr = date('Ymd');

$cliFile = $logRoot . 'cron_cli.' . $dateStr . '.log.php';
$webFile = $logRoot . 'cron.' . $dateStr . '.log.php';
if(is_file($cliFile)) unlink($cliFile);
if(is_file($webFile)) unlink($webFile);

r($cron->logCronTest('test_log_content')) && p() && e('0');
r(file_exists($cliFile)) && p() && e('1');
r(strpos(file_get_contents($cliFile), 'test_log_content') !== false) && p() && e('1');
r(strpos(file_get_contents($cliFile), '<?php') === 0) && p() && e('1');
r($cron->logCronTest("multiline\nlog\ncontent")) && p() && e('0');