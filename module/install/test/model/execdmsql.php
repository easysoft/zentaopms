#!/usr/bin/env php
<?php
/**

title=测试 installModel->execDMSQL();
timeout=0
cid=1

- 测试是否能正常执行dm.sql里的SQL语句。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester, $config;
$tester->loadModel('install');

$tester->install->dbh = $tester->install->connectDB();
$tester->install->dbh->useDB($config->db->name);
r($tester->install->execDMSQL()) && p() && e('1'); // 测试是否能正常执行dm.sql里的SQL语句。
