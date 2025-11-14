#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->fixConsistency().
cid=19517

- 判断修复一致性的sql中是否有失败执行，若有，则会记录HasError。 @1
- 判断是否成功的记录了修复一致性的sql。 @1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';

$upgrade = new upgradeTest();

$consistencyFile = $upgrade->getConsistencyLogFile();
if(file_exists($consistencyFile)) unlink($consistencyFile);

$version = '18.9';
$standardSqlFile = $tester->app->getAppRoot() . 'db' . DS . 'standard' . DS . 'zentao' . $version . '.sql';
$standardSqls    = file_get_contents($standardSqlFile);
$rawStandardSqls = $standardSqls;

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
r(strpos(trim($lines[2]), '`zt_account`') !== false) && p() && e('1');  //判断是否成功的记录了修复一致性的sql。

file_put_contents($standardSqlFile, $rawStandardSqls);
$upgrade->objectModel->dao->exec('ALTER TABLE `zt_account` CHANGE `id` `id` smallint(5) NOT NULL');
