#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/stakeholder.class.php';
su('admin');

/**

title=测试 stakeholderModel->getByID();
cid=1
pid=1

正常根据id查询干系人 >> 测试59
查询id不存在的干系人 >> 0

*/
global $tester;
$stakeholder = $tester->loadModel('stakeholder');

$stakeholderIDList = array('0', '17');

r($stakeholder->getByID($stakeholderIDList[1])) && p('name') && e('测试59');//正常根据id查询干系人
r($stakeholder->getByID($stakeholderIDList[0])) && p()       && e('0');     //查询id不存在的干系人