#!/usr/bin/env php
<?php

/**

title=测试 biModel::getFields();
timeout=0
cid=15171

- 执行biTest模块的getFieldsTest方法，参数是$statement1
 - 属性id @id
 - 属性name @name
- 执行biTest模块的getFieldsTest方法，参数是$statement2  @0
- 执行biTest模块的getFieldsTest方法，参数是$statement3
 - 属性id @u.id
 - 属性account @u.account
- 执行biTest模块的getFieldsTest方法，参数是$statement4
 - 属性user_id @id
 - 属性username @account
- 执行biTest模块的getFieldsTest方法，参数是$statement5 属性* @*
- 执行biTest模块的getFieldsTest方法，参数是$statement6  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

su('admin');

$biTest = new biTest();

// 测试1：正常字段解析 - 模拟一个简单的statement对象
$statement1 = new stdclass();
$statement1->expr = array();

$expr1 = new stdclass();
$expr1->expr = 'id';
$expr1->alias = '';
$statement1->expr[] = $expr1;

$expr2 = new stdclass();
$expr2->expr = 'name';
$expr2->alias = '';
$statement1->expr[] = $expr2;

r($biTest->getFieldsTest($statement1)) && p('id,name') && e('id,name');

// 测试2：空statement对象处理 - expr为null时返回空数组
$statement2 = new stdclass();
$statement2->expr = null;
r($biTest->getFieldsTest($statement2)) && p() && e('0');

// 测试3：带表前缀字段解析 - 有点前缀的字段，别名为点后面的部分
$statement3 = new stdclass();
$statement3->expr = array();

$expr3 = new stdclass();
$expr3->expr = 'u.id';
$expr3->alias = '';
$statement3->expr[] = $expr3;

$expr4 = new stdclass();
$expr4->expr = 'u.account';
$expr4->alias = '';
$statement3->expr[] = $expr4;

r($biTest->getFieldsTest($statement3)) && p('id,account') && e('u.id,u.account');

// 测试4：带别名字段解析 - 使用alias作为key，原字段名作为value
$statement4 = new stdclass();
$statement4->expr = array();

$expr5 = new stdclass();
$expr5->expr = 'id';
$expr5->alias = 'user_id';
$statement4->expr[] = $expr5;

$expr6 = new stdclass();
$expr6->expr = 'account';
$expr6->alias = 'username';
$statement4->expr[] = $expr6;

r($biTest->getFieldsTest($statement4)) && p('user_id,username') && e('id,account');

// 测试5：SELECT *字段解析 - *字段原样处理
$statement5 = new stdclass();
$statement5->expr = array();

$expr7 = new stdclass();
$expr7->expr = '*';
$expr7->alias = '';
$statement5->expr[] = $expr7;

r($biTest->getFieldsTest($statement5)) && p('*') && e('*');

// 测试6：空expr数组处理 - expr为空数组时返回空数组
$statement6 = new stdclass();
$statement6->expr = array();
r($biTest->getFieldsTest($statement6)) && p() && e('0');