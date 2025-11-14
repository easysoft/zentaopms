#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::initSql();
timeout=0
cid=17409

- 执行pivot模块的initSqlTest方法，参数是'select id, name from zt_user;;;;', array  @select id,name from zt_user

- 执行pivot模块的initSqlTest方法，参数是'select * from zt_user where name = $username', array  @select * from zt_user where name = ''
- 执行pivot模块的initSqlTest方法，参数是'select id from zt_user', array 属性1 @~~
- 执行pivot模块的initSqlTest方法，参数是'select * from zt_task', array 属性1 @ where tt.`status` like %wait%
- 执行pivot模块的initSqlTest方法，参数是'select count 属性2 @ group by

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivot = new pivotTest();

r($pivot->initSqlTest('select id,name from zt_user;;;;', array('name' => array('field' => 'name', 'operator' => '=', 'value' => 'admin')), 'id,name')) && p('0') && e('select id,name from zt_user');
r($pivot->initSqlTest('select * from zt_user where name = $username', array(), 'id')) && p('0') && e("select * from zt_user where name = ''");
r($pivot->initSqlTest('select id from zt_user', array(), 'id')) && p('1') && e('~~');
r($pivot->initSqlTest('select * from zt_task', array('status' => array('field' => 'status', 'operator' => 'like', 'value' => '%wait%')), 'status')) && p('1') && e(' where tt.`status` like %wait%');
r($pivot->initSqlTest('select count(*) from zt_bug', array(), '')) && p('2') && e(' group by ');