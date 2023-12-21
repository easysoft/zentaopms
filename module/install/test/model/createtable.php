#!/usr/bin/env php
<?php
/**

title=测试 installModel->createTable();
timeout=0
cid=1

- 测试是否能正常生成18.0版本的数据库表。 @1
- 测试生成的数据库product表是否含有数据。 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('product')->gen(10);

global $tester, $config;
$tester->loadModel('install');

r($tester->install->createTable('18.0', false, 1)) && p() && e('1'); // 测试是否能正常生成18.0版本的数据库表。
r($tester->install->fetchByID(1, 'product'))       && p() && e('0'); // 测试生成的数据库product表是否含有数据。

zdTable('company')->gen(1); //为了防止后面执行的单测无法运行。
