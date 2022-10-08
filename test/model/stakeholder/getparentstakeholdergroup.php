#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/stakeholder.class.php';
su('admin');

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

r($stakeholder->getParentStakeholderGroup($objectIDList))        && p('31:user9') && e('user9');//正常输入项目项目集参数查询
r($stakeholder->getParentStakeholderGroup($noObjectIDList))      && p()           && e('0');    //不输入项目集项目参数查询
r(count($stakeholder->getParentStakeholderGroup($objectIDList))) && p()           && e('2');    //正常输入项目项目集参数查询统计