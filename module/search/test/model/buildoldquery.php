#!/usr/bin/env php
<?php

/**

title=测试 searchModel::buildOldQuery();
timeout=0
cid=0

- 测试步骤1:基本相等查询 @0
- 测试步骤2:包含操作符查询 @0
- 测试步骤3:不包含操作符查询 @0
- 测试步骤4:日期查询 @0
- 测试步骤5:多条件AND查询 @0
- 测试步骤6:OR组合查询 @0
- 测试步骤7:字段值为0 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$searchTest = new searchModelTest();

// 测试步骤1:构建基本的相等查询
$_POST = array();
$_POST['module'] = 'bug';
$_POST['groupAndOr'] = 'AND';
$_POST['field1'] = 'id';
$_POST['operator1'] = '=';
$_POST['value1'] = '1';
$_POST['andOr1'] = 'AND';
$_SESSION['bugsearchParams'] = array('module' => 'bug', 'fields' => array('id' => 'ID'), 'params' => array('id' => array('control' => 'input', 'operator' => '=')));
r($searchTest->buildOldQueryTest()) && p() && e('0'); // 测试步骤1:基本相等查询

// 测试步骤2:构建包含操作符的查询
$_POST = array();
$_POST['module'] = 'bug';
$_POST['groupAndOr'] = 'AND';
$_POST['field1'] = 'title';
$_POST['operator1'] = 'include';
$_POST['value1'] = 'test';
$_POST['andOr1'] = 'AND';
$_SESSION['bugsearchParams'] = array('module' => 'bug', 'fields' => array('title' => 'Title'), 'params' => array('title' => array('control' => 'input', 'operator' => 'include')));
r($searchTest->buildOldQueryTest()) && p() && e('0'); // 测试步骤2:包含操作符查询

// 测试步骤3:构建不包含操作符的查询
$_POST = array();
$_POST['module'] = 'bug';
$_POST['groupAndOr'] = 'AND';
$_POST['field1'] = 'title';
$_POST['operator1'] = 'notinclude';
$_POST['value1'] = 'bug';
$_POST['andOr1'] = 'AND';
$_SESSION['bugsearchParams'] = array('module' => 'bug', 'fields' => array('title' => 'Title'), 'params' => array('title' => array('control' => 'input', 'operator' => 'notinclude')));
r($searchTest->buildOldQueryTest()) && p() && e('0'); // 测试步骤3:不包含操作符查询

// 测试步骤4:构建between日期查询
$_POST = array();
$_POST['module'] = 'bug';
$_POST['groupAndOr'] = 'AND';
$_POST['field1'] = 'openedDate';
$_POST['operator1'] = '=';
$_POST['value1'] = '2024-01-01';
$_POST['andOr1'] = 'AND';
$_SESSION['bugsearchParams'] = array('module' => 'bug', 'fields' => array('openedDate' => 'Opened Date'), 'params' => array('openedDate' => array('control' => 'input', 'operator' => '=')));
r($searchTest->buildOldQueryTest()) && p() && e('0'); // 测试步骤4:日期查询

// 测试步骤5:构建多条件AND查询
$_POST = array();
$_POST['module'] = 'bug';
$_POST['groupAndOr'] = 'AND';
$_POST['field1'] = 'status';
$_POST['operator1'] = '=';
$_POST['value1'] = 'active';
$_POST['andOr1'] = 'AND';
$_POST['field2'] = 'pri';
$_POST['operator2'] = '=';
$_POST['value2'] = '1';
$_POST['andOr2'] = 'AND';
$_SESSION['bugsearchParams'] = array('module' => 'bug', 'fields' => array('status' => 'Status', 'pri' => 'Priority'), 'params' => array('status' => array('control' => 'select', 'operator' => '='), 'pri' => array('control' => 'select', 'operator' => '=')));
r($searchTest->buildOldQueryTest()) && p() && e('0'); // 测试步骤5:多条件AND查询

// 测试步骤6:构建OR组合查询
$_POST = array();
$_POST['module'] = 'bug';
$_POST['groupAndOr'] = 'OR';
$_POST['field1'] = 'status';
$_POST['operator1'] = '=';
$_POST['value1'] = 'active';
$_POST['andOr1'] = 'AND';
$_POST['field4'] = 'pri';
$_POST['operator4'] = '=';
$_POST['value4'] = '1';
$_POST['andOr4'] = 'AND';
$_SESSION['bugsearchParams'] = array('module' => 'bug', 'fields' => array('status' => 'Status', 'pri' => 'Priority'), 'params' => array('status' => array('control' => 'select', 'operator' => '='), 'pri' => array('control' => 'select', 'operator' => '=')));
r($searchTest->buildOldQueryTest()) && p() && e('0'); // 测试步骤6:OR组合查询

// 测试步骤7:测试字段值为0的特殊情况
$_POST = array();
$_POST['module'] = 'bug';
$_POST['groupAndOr'] = 'AND';
$_POST['field1'] = 'id';
$_POST['operator1'] = '=';
$_POST['value1'] = '0';
$_POST['andOr1'] = 'AND';
$_SESSION['bugsearchParams'] = array('module' => 'bug', 'fields' => array('id' => 'ID'), 'params' => array('id' => array('control' => 'input', 'operator' => '=')));
r($searchTest->buildOldQueryTest()) && p() && e('0'); // 测试步骤7:字段值为0