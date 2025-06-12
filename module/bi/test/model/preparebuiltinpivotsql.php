#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareBuiltinPivotSQLTest();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

$bi = new biTest();

r(count($bi->prepareBuiltinPivotSQLTest('insert'))) && p('') && e('10'); //测试普通SQL语句获取表是否正确
r(count($bi->prepareBuiltinPivotSQLTest('insert'))) && p('') && e('10'); //测试普通SQL语句获取表是否正确
r(count($bi->prepareBuiltinPivotSQLTest('insert'))) && p('') && e('10'); //测试普通SQL语句获取表是否正确
r(count($bi->prepareBuiltinPivotSQLTest('insert'))) && p('') && e('10'); //测试普通SQL语句获取表是否正确
r(count($bi->prepareBuiltinPivotSQLTest('insert'))) && p('') && e('10'); //测试普通SQL语句获取表是否正确