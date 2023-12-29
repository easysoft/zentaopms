#!/usr/bin/env php
<?php

/**

title=测试 loadModel->computeOrders()
cid=0

- 传入空参数 @0
- 传入空orders参数
 -  @30
 - 属性1 @35
 - 属性2 @40
 - 属性3 @45
- 传入有部分orders参数
 -  @5
 - 属性1 @30
 - 属性2 @35
 - 属性3 @40
- 传入orders数量大于计划数
 -  @5
 - 属性1 @10
 - 属性2 @15
 - 属性3 @20
 - 属性4 @25

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

$project = zdTable('project');
$project->parent->range('0,1{100}');
$project->type->range('project,stage{100}');
$project->milestone->range('0{3},1{100}');
$project->gen(5);

global $tester;
$tester->loadModel('programplan');
$plans = $tester->programplan->dao->select('*')->from(TABLE_PROJECT)->where('type')->eq('stage')->fetchAll();

r($tester->programplan->computeOrders(array(), array()))              && p()            && e('0');             //传入空参数
r($tester->programplan->computeOrders(array(), $plans))               && p('0,1,2,3')   && e('30,35,40,45');   //传入空orders参数
r($tester->programplan->computeOrders(array('5'), $plans))            && p('0,1,2,3')   && e('5,30,35,40');    //传入有部分orders参数
r($tester->programplan->computeOrders(array(5,10,15,20,25), $plans))  && p('0,1,2,3,4') && e('5,10,15,20,25'); //传入orders数量大于计划数
