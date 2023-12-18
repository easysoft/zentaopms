#!/usr/bin/env php
<?php
/**

title=测试 installModel->connectDB();
timeout=0
cid=1

- 检查正确配置mysql连接信息时候能否连接到数据库。 @1
- 检查密码错误时候能否连接到数据库。 @0
- 检查密码错误时的提示信息。 @SQLSTATE[HY000] [1045] Access denied for user 'root'

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester, $config;
$tester->loadModel('install');


r(is_object($tester->install->connectDB())) && p() && e('1'); // 检查正确配置mysql连接信息时候能否连接到数据库。

$config->db->password = 'qqwwee';
r(is_object($tester->install->connectDB())) && p() && e('0'); // 检查密码错误时候能否连接到数据库。
r(substr($tester->install->connectDB(), 0, 52)) && p() && e("SQLSTATE[HY000] [1045] Access denied for user 'root'"); // 检查密码错误时的提示信息。
