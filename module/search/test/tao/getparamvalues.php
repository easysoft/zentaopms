#!/usr/bin/env php
<?php

/**

title=测试 searchModel->getParamValuesTest();
timeout=0
cid=1

- 测试获取产品变量的产品数据
 - 第0条的admin属性 @A:admin
 - 第0条的user1属性 @U:用户1
 - 第0条的user2属性 @U:用户2
 - 第0条的user3属性 @U:用户3
 - 第0条的user4属性 @U:用户4
- 测试获取用户变量的用户数据
 - 第1条的5属性 @正常产品5
 - 第1条的4属性 @正常产品4
 - 第1条的3属性 @正常产品3
 - 第1条的2属性 @正常产品2
 - 第1条的1属性 @正常产品1
- 测试获取执行变量的执行数据
 - 第2条的5属性 @/迭代3
 - 第2条的4属性 @/迭代2
 - 第2条的3属性 @/迭代1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';
su('admin');

zdTable('user')->gen(5);
zdTable('product')->gen(5);

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$fields = array('product', 'user', 'execution');

$params1 = array();
$params1['user']['values'] = 'users';

$params2 = array();
$params2['product']['values'] = 'products';

$params3 = array();
$params3['execution']['values'] = 'executions';

$search = new searchTest();
r($search->getParamValuesTest($fields, $params1)) && p('0:admin,user1,user2,user3,user4') && e('A:admin,U:用户1,U:用户2,U:用户3,U:用户4'); //测试获取产品变量的产品数据
r($search->getParamValuesTest($fields, $params2)) && p('1:5,4,3,2,1') && e('正常产品5,正常产品4,正常产品3,正常产品2,正常产品1');           //测试获取用户变量的用户数据
r($search->getParamValuesTest($fields, $params3)) && p('2:5,4,3') && e('/迭代3,/迭代2,/迭代1');                                               //测试获取执行变量的执行数据