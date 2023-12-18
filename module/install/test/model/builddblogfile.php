#!/usr/bin/env php
<?php
/**

title=测试 installModel->buildDBLogFile();
timeout=0
cid=1

- 获取数据库config日志存储路径。 @1
- 获取数据库error日志存储路径。 @1
- 获取数据库success日志存储路径。 @1
- 获取数据库progress日志存储路径。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester, $config;
$tester->loadModel('install');

r(strpos($tester->install->buildDBLogFile('config'),   'db.cnf')         !== false) && p() && e(1); // 获取数据库config日志存储路径。
r(strpos($tester->install->buildDBLogFile('error'),    'dberror.log')    !== false) && p() && e(1); // 获取数据库error日志存储路径。
r(strpos($tester->install->buildDBLogFile('success'),  'dbsuccess.log')  !== false) && p() && e(1); // 获取数据库success日志存储路径。
r(strpos($tester->install->buildDBLogFile('progress'), 'dbprogress.log') !== false) && p() && e(1); // 获取数据库progress日志存储路径。
