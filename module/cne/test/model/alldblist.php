#!/usr/bin/env php
<?php

/**

title=测试 cneModel::allDBList();
timeout=0
cid=15598

- 执行cneTest模块的allDBListTest方法，参数是'success'
 - 第zentaopaas-mysql条的db_type属性 @mysql
 - 第zentaopaas-mysql条的release属性 @zentaopaas
- 执行cneTest模块的allDBListTest方法，参数是'empty'  @0
- 执行cneTest模块的allDBListTest方法，参数是'error'  @0
- 执行cneTest模块的allDBListTest方法，参数是'network_error'  @0
- 执行cneTest模块的allDBListTest方法，参数是'invalid_config'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

su('admin');

$cneTest = new cneTest();

r($cneTest->allDBListTest('success')) && p('zentaopaas-mysql:db_type,release') && e('mysql,zentaopaas');
r($cneTest->allDBListTest('empty')) && p() && e('0');
r($cneTest->allDBListTest('error')) && p() && e('0');
r($cneTest->allDBListTest('network_error')) && p() && e('0');
r($cneTest->allDBListTest('invalid_config')) && p() && e('0');
