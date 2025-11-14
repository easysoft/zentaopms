#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::appendWhereFilterToSql();
timeout=0
cid=17356

- 执行pivotTest模块的appendWhereFilterToSqlTest方法，参数是$sql, $filters, $driver  @select * from ( SELECT * FROM zt_user ) tt where tt.`account` = 'admin'
- 执行pivotTest模块的appendWhereFilterToSqlTest方法，参数是$sql, $filters, $driver  @select * from ( SELECT * FROM zt_user ) tt where 1=0
- 执行pivotTest模块的appendWhereFilterToSqlTest方法，参数是$sql, $filters, $driver  @SELECT * FROM zt_user
- 执行pivotTest模块的appendWhereFilterToSqlTest方法，参数是$sql, $filters, $driver  @select * from ( SELECT * FROM zt_user ) tt where  cast(tt.`account` as varchar)  = 'admin'
- 执行pivotTest模块的appendWhereFilterToSqlTest方法，参数是$sql, $filters, $driver  @select * from ( SELECT * FROM zt_user ) tt where tt.`account` = 'admin' and tt.`role` != 'guest'

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

su('admin');

$pivotTest = new pivotTest();

// 测试步骤1：正常过滤器情况
$sql = "SELECT * FROM zt_user";
$filters = array(
    'account' => array(
        'type' => 'input',
        'operator' => '=',
        'value' => "'admin'"
    )
);
$driver = 'mysql';
r($pivotTest->appendWhereFilterToSqlTest($sql, $filters, $driver)) && p() && e("select * from ( SELECT * FROM zt_user ) tt where tt.`account` = 'admin'");

// 测试步骤2：空过滤器情况
$sql = "SELECT * FROM zt_user";
$filters = array();
$driver = 'mysql';
r($pivotTest->appendWhereFilterToSqlTest($sql, $filters, $driver)) && p() && e("select * from ( SELECT * FROM zt_user ) tt where 1=0");

// 测试步骤3：filters为false情况
$sql = "SELECT * FROM zt_user";
$filters = false;
$driver = 'mysql';
r($pivotTest->appendWhereFilterToSqlTest($sql, $filters, $driver)) && p() && e("SELECT * FROM zt_user");

// 测试步骤4：DuckDB驱动的过滤器
$sql = "SELECT * FROM zt_user";
$filters = array(
    'account' => array(
        'type' => 'input',
        'operator' => '=',
        'value' => "'admin'"
    )
);
$driver = 'duckdb';
r($pivotTest->appendWhereFilterToSqlTest($sql, $filters, $driver)) && p() && e("select * from ( SELECT * FROM zt_user ) tt where  cast(tt.`account` as varchar)  = 'admin'");

// 测试步骤5：复杂过滤器情况（多个过滤条件）
$sql = "SELECT * FROM zt_user";
$filters = array(
    'account' => array(
        'type' => 'input',
        'operator' => '=',
        'value' => "'admin'"
    ),
    'role' => array(
        'type' => 'input',
        'operator' => '!=',
        'value' => "'guest'"
    )
);
$driver = 'mysql';
r($pivotTest->appendWhereFilterToSqlTest($sql, $filters, $driver)) && p() && e("select * from ( SELECT * FROM zt_user ) tt where tt.`account` = 'admin' and tt.`role` != 'guest'");