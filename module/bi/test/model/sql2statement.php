#!/usr/bin/env php
<?php

/**

title=测试 biModel::sql2Statement();
timeout=0
cid=15216

- 测试正常的SELECT语句转换为statement对象 @1
- 测试空SQL字符串输入(text模式) @请输入一条正确的SQL语句
- 测试空SQL字符串输入(builder模式) @请正确配置构建器
- 测试包含多个SQL语句的输入 @只能输入一条SQL语句
- 测试非SELECT类型的SQL语句(INSERT) @只允许SELECT查询
- 测试非SELECT类型的SQL语句(UPDATE) @只允许SELECT查询
- 测试带WHERE条件的SELECT语句 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app, $lang;
$app->loadLang('dataview');
if(!isset($lang->dataview)) $lang->dataview = new stdclass();
if(!isset($lang->dataview->empty)) $lang->dataview->empty = '请输入一条正确的SQL语句';
if(!isset($lang->dataview->emptyBuilder)) $lang->dataview->emptyBuilder = '请正确配置构建器';
if(!isset($lang->dataview->onlyOne)) $lang->dataview->onlyOne = '只能输入一条SQL语句';
if(!isset($lang->dataview->allowSelect)) $lang->dataview->allowSelect = '只允许SELECT查询';

su('admin');

$bi = new biModelTest();

r(is_object($bi->sql2StatementTest('SELECT id, name FROM zt_user', 'text'))) && p() && e('1'); // 测试正常的SELECT语句转换为statement对象
r($bi->sql2StatementTest('', 'text')) && p() && e('请输入一条正确的SQL语句'); // 测试空SQL字符串输入(text模式)
r($bi->sql2StatementTest('', 'builder')) && p() && e('请正确配置构建器'); // 测试空SQL字符串输入(builder模式)
r($bi->sql2StatementTest('SELECT id FROM zt_user; SELECT name FROM zt_user', 'text')) && p() && e('只能输入一条SQL语句'); // 测试包含多个SQL语句的输入
r($bi->sql2StatementTest('INSERT INTO zt_user (name) VALUES ("test")', 'text')) && p() && e('只允许SELECT查询'); // 测试非SELECT类型的SQL语句(INSERT)
r($bi->sql2StatementTest('UPDATE zt_user SET name = "test" WHERE id = 1', 'text')) && p() && e('只允许SELECT查询'); // 测试非SELECT类型的SQL语句(UPDATE)
r(is_object($bi->sql2StatementTest('SELECT id, name FROM zt_user WHERE deleted = "0"', 'text'))) && p() && e('1'); // 测试带WHERE条件的SELECT语句
