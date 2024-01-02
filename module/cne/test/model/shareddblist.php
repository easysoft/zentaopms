#!/usr/bin/env php
<?php

/**

title=测试 cneModel->sharedDBList();
timeout=0
cid=1

- 空的数据 @0
- 错误的类型 @0
- 正确的类型
 - 第zentaopaas-mysql条的host属性 @zentaopaas-mysql.quickon-system.svc
 - 第zentaopaas-mysql条的port属性 @3306

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cne.class.php';

$cneModel = new cneTest();

$type = '';
r($cneModel->sharedDBListTest($type)) && p() && e('0'); // 空的数据

$type = 'mariadb';
r($cneModel->sharedDBListTest($type)) && p() && e('0'); // 错误的类型

$type = 'mysql';
r($cneModel->sharedDBListTest($type)) && p('zentaopaas-mysql:host,port') && e('zentaopaas-mysql.quickon-system.svc,3306'); // 正确的类型