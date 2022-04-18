#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/stakeholder.class.php';
su('admin');

/**

title=测试 stakeholderModel->getStakeholderGroup();
cid=1
pid=1

正常查询干系人分组 >> admin
正常查询干系人分组统计 >> 4
空数组查询 >> 0

*/
global $tester;
$stakeholder = $tester->loadModel('stakeholder');

$objectIDList   = array('11', '31', '100', '1');
$noObjectIDList = array();

r($stakeholder->getStakeholderGroup($objectIDList))        && p('11:admin') && e('admin');//正常查询干系人分组
r(count($stakeholder->getStakeholderGroup($objectIDList))) && p()           && e('4');    //正常查询干系人分组统计
r($stakeholder->getStakeholderGroup($noObjectIDList))      && p()           && e('0');    //空数组查询