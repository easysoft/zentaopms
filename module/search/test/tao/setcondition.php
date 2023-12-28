#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';

su('admin');

zdTable('module')->gen(5);
zdTable('scene')->gen(1);

/**

title=测试 searchModel->setCondition();
timeout=0
cid=1

- 测试 module 包含 id 为 1 的条件 @= '1'
- 测试 title 包含 test 的条件 @ LIKE '%test%'
- 测试 module 不包含 id 为 1 的条件 @ != '1'
- 测试 title 不包含 test 的条件 @ NOT LIKE '%test%'
- 测试 module 属于 id 为 1 的条件 @= '1'
- 测试 title 属于 test 的条件 @= 'test'
- 测试 dept 属于 id 为 1 的条件 @= '1'
- 测试 scene 属于 id 为 1 的条件 @= '1'
- 测试 date between $thisMonth 的条件 @between '$thisMonth'
- 测试 id = '1,2,3' 的条件 @in (1,2,3)

*/

$operators = array('include', 'notinclude', 'belong', 'between', '=');
$fields    = array('module', 'title', 'dept', 'scene', 'id', 'date');
$values    = array(1, 'test', '0', '$thisMonth', '1,2,3');
$thisMonth = 1;

$search = new searchTest();
r($search->setConditionTest($fields[0], $operators[0], $values[0])) && p() && e("= '1'");                //测试 module 包含 id 为 1 的条件
r($search->setConditionTest($fields[1], $operators[0], $values[1])) && p() && e(" LIKE '%test%'");       //测试 title 包含 test 的条件
r($search->setConditionTest($fields[0], $operators[1], $values[0])) && p() && e(" != '1'");              //测试 module 不包含 id 为 1 的条件
r($search->setConditionTest($fields[1], $operators[1], $values[1])) && p() && e(" NOT LIKE '%test%'");   //测试 title 不包含 test 的条件
r($search->setConditionTest($fields[0], $operators[2], $values[0])) && p() && e("= '1'");                //测试 module 属于 id 为 1 的条件
r($search->setConditionTest($fields[1], $operators[2], $values[1])) && p() && e("= 'test'");             //测试 title 属于 test 的条件
r($search->setConditionTest($fields[2], $operators[2], $values[0])) && p() && e("= '1'");                //测试 dept 属于 id 为 1 的条件
r($search->setConditionTest($fields[3], $operators[2], $values[0])) && p() && e("= '1'");                //测试 scene 属于 id 为 1 的条件
r($search->setConditionTest($fields[1], $operators[3], $values[3])) && p() && e("between '$thisMonth'"); //测试 date between $thisMonth 的条件
r($search->setConditionTest($fields[4], $operators[4], $values[4])) && p() && e("IN (1,2,3)");           //测试 id = '1,2,3' 的条件
