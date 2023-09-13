#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';
su('admin');

$product = zdTable('project');
$product->id->range('1-50');
$product->type->range('project{10},sprint{40}');
$product->project->range('0{10},1-10{4}');
$product->parent->range('0{10},1-10{4}');
$product->acl->range('private');
$product->gen(50)->fixPath();

zdTable('stakeholder')->config('stakeholder')->gen(50);

/**

title=测试 stakeholderModel->getParentStakeholderGroup();
cid=1
pid=1

正常输入项目项目集参数查询 >> user9
不输入项目集项目参数查询 >> 0
正常输入项目项目集参数查询统计 >> 2

*/
global $tester;
$stakeholder = $tester->loadModel('stakeholder');

$objectIDList   = array('11', '31', '100', '1');
$noObjectIDList = array();

$stakeholders = ($stakeholder->getParentStakeholderGroup($objectIDList));
r($stakeholders)                                                 && p('11:admin') && e('admin'); //正常输入项目项目集参数查询
r($stakeholders)                                                 && p('31:user5') && e('user5'); //正常输入项目项目集参数查询
r($stakeholder->getParentStakeholderGroup($noObjectIDList))      && p()           && e('0');     //不输入项目集项目参数查询
r(count($stakeholder->getParentStakeholderGroup($objectIDList))) && p()           && e('2');     //正常输入项目项目集参数查询统计
