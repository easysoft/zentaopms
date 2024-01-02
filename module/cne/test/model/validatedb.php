#!/usr/bin/env php
<?php

/**

title=测试 cneModel->validateDB();
timeout=0
cid=1

- 空的数据
 - 属性user @1
 - 属性database @1
- 正确的数据库
 - 属性user @1
 - 属性database @1
- 正确的数据库和用户
 - 属性user @1
 - 属性database @1
- 正确的数据库、用户和数据库名
 - 属性user @1
 - 属性database @1
- 正确的所有数据，表名重复
 - 属性user @1
 - 属性database @~~
- 正确的数据库、用户、数据库名和表名
 - 属性user @1
 - 属性database @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cne.class.php';

$cneModel = new cneTest();

$dbService = '';
$dbUser    = '';
$dbName    = '';
$namespace = '';
r($cneModel->validateDBTest($dbService, $dbUser, $dbName, $namespace)) && p('user,database') && e('1,1'); // 空的数据

$dbService = 'zentaopaas-mysql';
r($cneModel->validateDBTest($dbService, $dbUser, $dbName, $namespace)) && p('user,database') && e('1,1'); // 正确的数据库

$dbUser = 'root';
r($cneModel->validateDBTest($dbService, $dbUser, $dbName, $namespace)) && p('user,database') && e('1,1'); // 正确的数据库和用户

$dbName = 'zentaopaas';
r($cneModel->validateDBTest($dbService, $dbUser, $dbName, $namespace)) && p('user,database') && e('1,1'); // 正确的数据库、用户和数据库名

$namespace = 'quickon-system';
r($cneModel->validateDBTest($dbService, $dbUser, $dbName, $namespace)) && p('user,database') && e('1,~~'); // 正确的所有数据，表名重复

$dbName = 'zentaopaas_test';
r($cneModel->validateDBTest($dbService, $dbUser, $dbName, $namespace)) && p('user,database') && e('1,1'); // 正确的数据库、用户、数据库名和表名