#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/stakeholder.class.php';
su('admin');

/**

title=测试 stakeholderModel->getExpectByID();
cid=1
pid=1

查询不存在的期望数据 >> 0
正常查询期望数据 >> 期望内容17

*/
global $tester;
$stakeholder = $tester->loadModel('stakeholder');

$expectIDList = array('0', '17');

r($stakeholder->getExpectByID($expectIDList[0])) && p()         && e('0');         //查询不存在的期望数据
r($stakeholder->getExpectByID($expectIDList[1])) && p('expect') && e('期望内容17');//正常查询期望数据