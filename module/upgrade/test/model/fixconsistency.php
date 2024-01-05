#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->fixConsistency().
cid=1

- 判断修复一致性的sql中是否有失败执行，若有，则会记录HasError。@1
- 判断是否成功的记录了修复一致性的sql。@1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$upgrade = new upgradeTest();

$consistencyFile = $upgrade->getConsistencyLogFile();
if(file_exists($consistencyFile)) unlink($consistencyFile);

$version = '18.9';
$standardSqlFile = $tester->app->getAppRoot() . 'db' . DS . 'standard' . DS . 'zentao' . $version . '.sql';
$standardSqls    = file_get_contents($standardSqlFile);

$standardSqls = explode(';', $standardSqls);
$standardSqls[0] = str_replace('`id` smallint(5)', '`id` varchar(10)', $standardSqls[0]);
$standardSqls = implode(';', $standardSqls);

file_put_contents($standardSqlFile, $standardSqls);

$upgrade->fixConsistency($version);

$lines = file($consistencyFile);
$totalLines = count($lines);
r(trim($lines[$totalLines - 2]) === 'HasError') && p() && e(1);  //判断修复一致性的sql中是否有失败执行，若有，则会记录HasError。

$standardSqls = explode(';', $standardSqls);
$standardSqls[0] = str_replace(' unsigned', '', $standardSqls[0]);
$standardSqls[0] = str_replace(' AUTO_INCREMENT', '', $standardSqls[0]);
$standardSqls = implode(';', $standardSqls);
file_put_contents($standardSqlFile, $standardSqls);

$upgrade->fixConsistency($version);

$lines = file($consistencyFile);
r(trim($lines[2]) === 'ALTER TABLE `zt_account` CHANGE `id` `id` varchar(10) NOT NULL') && p() && e(1);  //判断是否成功的记录了修复一致性的sql。

$standardSqls = explode(';', $standardSqls);
$standardSqls[0] = str_replace('`id` varchar(10)', '`id` smallint(5) unsigned', $standardSqls[0]);
$standardSqls[0] = str_replace('NOT NULL', 'NOT NULL AUTO_INCREMENT', $standardSqls[0]);
$standardSqls = implode(';', $standardSqls);
file_put_contents($standardSqlFile, $standardSqls);
$upgrade->fixConsistency($version);
