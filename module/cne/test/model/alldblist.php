#!/usr/bin/env php
<?php

/**

title=测试 cneModel->allDBList();
timeout=0
cid=1

- 获取数据库列表
 - 第zentaopaas-mysql条的db_type属性 @mysql
 - 第zentaopaas-mysql条的release属性 @zentaopaas

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cne.class.php';

$cneModel = new cneTest();

r($cneModel->allDBListTest()) && p('zentaopaas-mysql:db_type,release') && e('mysql,zentaopaas'); // 获取数据库列表
