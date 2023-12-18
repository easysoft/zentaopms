#!/usr/bin/env php
<?php
/**

title=测试 installModel->getDatabaseVersion();
timeout=0
cid=1

- 检查是否能成功获取当前数据库版本。 @1
- 当driver为orical时候，获取数据库版本。 @8

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester, $config;
$tester->loadModel('install');

r($tester->install->getDatabaseVersion() >= 1) && p() && e(1); // 检查是否能成功获取当前数据库版本。

$config->db->driver = 'orical';
r($tester->install->getDatabaseVersion()) && p() && e(8);      // 当driver为orical时候，获取数据库版本。

