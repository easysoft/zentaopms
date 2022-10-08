#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/stakeholder.class.php';
su('admin');

/**

title=测试 stakeholderModel->getExpectByUser();
cid=1
pid=1

查询不存在的期望 >> 0
正常查询期望 >> 11

*/
global $tester;
$stakeholder = $tester->loadModel('stakeholder');

$stakeholderIDList = array('0', '32');

r($stakeholder->getExpectByUser($stakeholderIDList[0])) && p()          && e('0'); //查询不存在的期望
r($stakeholder->getExpectByUser($stakeholderIDList[1])) && p('0:project') && e('11');//正常查询期望