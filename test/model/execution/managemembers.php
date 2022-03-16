#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->manageMembersTest();
cid=1
pid=1

团队管理 >> 101,execution,dev10
团队人员管理统计 >> 3

*/

$executionID = '101';
$realnames   = array('研发主管82', '测试92');
$roles       = array('研发', '研发', '研发');
$hours       = array('3.0', '6.0', '7.0');
$accounts    = array('po82', 'user92', 'dev10');
$limited     = array('yes', 'no', 'yes');
$days        = array('17', '27', '127');

$manageMembers = array('realnames' => $realnames, 'roles' => $roles, 'hours' => $hours, 'accounts' => $accounts, 'limited' => $limited, 'days' => $days);
$count         = array('0','1');

$execution = new executionTest();
r($execution->manageMembersTest($executionID, $count[0], $manageMembers)) && p('0:root,type,account') && e('101,execution,dev10'); // 团队管理
r($execution->manageMembersTest($executionID, $count[1], $manageMembers)) && p()                      && e('3'); // 团队人员管理统计