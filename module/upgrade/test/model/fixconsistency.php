#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->fixConsistency().
cid=1

- 判断修复一致性的sql中是否有失败执行，若有，则会记录HasError。@1
- 判断是否成功获取了修复一致性的sql，若成功，则会设置sql_mode。@1
- 判断是否成功的记录了修复一致性的sql。@1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$upgrade = new upgradeTest();

$consistencyFile = $upgrade->getConsistencyLogFile();
if(file_exists($consistencyFile)) unlink($consistencyFile);

$versionList = array("16.0");

$upgrade->fixConsistency($versionList[0]);

$file = new SplFileObject($consistencyFile, 'r');
$file->seek(PHP_INT_MAX);
$totalLines = $file->key();

$file->seek($totalLines - 1);
$error = trim($file->current()) === 'HasError';
r($error) && p('') && e(1);  //判断修复一致性的sql中是否有失败执行，若有，则会记录HasError。

$file->rewind();
$firstLine = $file->current();
r(strpos($firstLine, 'sql_mode') !== false) && p('') && e(1);   //判断是否成功获取了修复一致性的sql，若成功，则会设置sql_mode。

$secondLinePoint = $file->seek(1);
$secondLine      = $file->current();
$condition = trim($secondLine) === "ALTER TABLE `zt_acl` CHANGE `objectID` `objectID` mediumint(9) NOT NULL DEFAULT '0'";
r($condition) && p('') && e(1);  //判断是否成功的记录了修复一致性的sql。
