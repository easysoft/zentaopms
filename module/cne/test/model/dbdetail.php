#!/usr/bin/env php
<?php

/**

title=测试 cneModel->dbDetail();
timeout=0
cid=1

- 空的数据 @0
- 错误的空间 @0
- 错误的数据库名 @0
- 正确的参数
 - 属性host @zentaopaas-mysql.quickon-system.svc
 - 属性username @root

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cne.class.php';

$cneModel  = new cneTest();
$dbService = '';
$namespace = '';

r($cneModel->dbDetailTest($dbService, $namespace)) && p() && e('0'); // 空的数据

$dbService = 'mysql';
r($cneModel->dbDetailTest($dbService, $namespace)) && p() && e('0'); // 错误的空间

$namespace = 'quickon-system';
r($cneModel->dbDetailTest($dbService, $namespace)) && p() && e('0'); // 错误的数据库名

$dbService = 'zentaopaas-mysql';
r($cneModel->dbDetailTest($dbService, $namespace)) && p('host,username') && e('zentaopaas-mysql.quickon-system.svc,root'); // 正确的参数