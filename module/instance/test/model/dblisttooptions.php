#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::dbListToOptions();
timeout=0
cid=16787

- 执行instanceTest模块的dbListToOptionsTest方法，参数是$dbListWithAlias 属性mysql @mysql-server
- 执行instanceTest模块的dbListToOptionsTest方法，参数是$dbListWithoutAlias 属性mongodb @mongodb
- 执行instanceTest模块的dbListToOptionsTest方法，参数是$dbListMixed
 - 属性mysql @mysql-server
 - 属性sqlite @sqlite
- 执行instanceTest模块的dbListToOptionsTest方法，参数是$dbListEmpty  @0
- 执行instanceTest模块的dbListToOptionsTest方法，参数是$dbListSingle 属性oracle @oracle-database
- 执行instanceTest模块的dbListToOptionsTest方法，参数是$dbListSpecialChars 属性db-test @DB Test Server (v1.0)
- 执行instanceTest模块的dbListToOptionsTest方法，参数是$dbListWithAlias  @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

su('admin');

$instanceTest = new instanceTest();

// 测试数据准备
$dbListWithAlias = array();
$dbListWithAlias[1] = new stdClass();
$dbListWithAlias[1]->name = 'mysql';
$dbListWithAlias[1]->alias = 'mysql-server';
$dbListWithAlias[2] = new stdClass();
$dbListWithAlias[2]->name = 'postgresql';
$dbListWithAlias[2]->alias = 'postgresql-server';

$dbListWithoutAlias = array();
$dbListWithoutAlias[1] = new stdClass();
$dbListWithoutAlias[1]->name = 'mongodb';
$dbListWithoutAlias[2] = new stdClass();
$dbListWithoutAlias[2]->name = 'redis';

$dbListMixed = array();
$dbListMixed[1] = new stdClass();
$dbListMixed[1]->name = 'mysql';
$dbListMixed[1]->alias = 'mysql-server';
$dbListMixed[2] = new stdClass();
$dbListMixed[2]->name = 'sqlite';

$dbListEmpty = array();

$dbListSingle = array();
$dbListSingle[1] = new stdClass();
$dbListSingle[1]->name = 'oracle';
$dbListSingle[1]->alias = 'oracle-database';

$dbListSpecialChars = array();
$dbListSpecialChars[1] = new stdClass();
$dbListSpecialChars[1]->name = 'db-test';
$dbListSpecialChars[1]->alias = 'DB Test Server (v1.0)';

r($instanceTest->dbListToOptionsTest($dbListWithAlias)) && p('mysql') && e('mysql-server');
r($instanceTest->dbListToOptionsTest($dbListWithoutAlias)) && p('mongodb') && e('mongodb');
r($instanceTest->dbListToOptionsTest($dbListMixed)) && p('mysql,sqlite') && e('mysql-server,sqlite');
r($instanceTest->dbListToOptionsTest($dbListEmpty)) && p() && e('0');
r($instanceTest->dbListToOptionsTest($dbListSingle)) && p('oracle') && e('oracle-database');
r($instanceTest->dbListToOptionsTest($dbListSpecialChars)) && p('db-test') && e('DB Test Server (v1.0)');
r(count($instanceTest->dbListToOptionsTest($dbListWithAlias))) && p() && e('2');