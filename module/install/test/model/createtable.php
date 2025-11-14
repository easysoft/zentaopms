#!/usr/bin/env php
<?php
/**

title=测试 installModel->createTable();
timeout=0
cid=16769

- 测试是否能正常生成mysql7.0版本的数据库表。 @1
- 测试生成的数据库product表是否含有数据。 @0
- 测试生成的数据库story表是否含有数据。 @0
- 测试生成的数据库project表是否含有数据。 @0
- 测试生成的数据库bug表是否含有数据。 @0
- 测试生成的数据库task表是否含有数据。 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('company')->gen(1);
zenData('product')->gen(10);
zenData('project')->gen(10);
zenData('task')->gen(10);

global $tester, $config;
$tester->loadModel('install');

r($tester->install->createTable('7.0', false, 1)) && p() && e('1'); // 测试是否能正常生成mysql7.0版本的数据库表。
zenData('company')->gen(1); //为了防止后面执行的单测无法运行。

r($tester->install->fetchByID(1, 'product')) && p() && e('0'); // 测试生成的数据库product表是否含有数据。
r($tester->install->fetchByID(1, 'story'))   && p() && e('0'); // 测试生成的数据库story表是否含有数据。
r($tester->install->fetchByID(1, 'project')) && p() && e('0'); // 测试生成的数据库project表是否含有数据。
r($tester->install->fetchByID(1, 'bug'))     && p() && e('0'); // 测试生成的数据库bug表是否含有数据。
r($tester->install->fetchByID(1, 'task'))    && p() && e('0'); // 测试生成的数据库task表是否含有数据。

