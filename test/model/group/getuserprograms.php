#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');

/**

title=测试 groupModel->getUserPrograms();
cid=1
pid=1



*/

$groupList  = array(1,2,3,4,5);
$userIDList = array('td81','dev100','pd10','td50','td64');
$resultList = array(74,29,13,43,57);

$group = new groupTest();

r($group->getUserProgramsTest($groupList[0])) && p($userIDList[0]) && e(74); //检查权限分组 1 的用户项目信息
r($group->getUserProgramsTest($groupList[1])) && p($userIDList[1]) && e(29); //检查权限分组 2 的用户项目信息
r($group->getUserProgramsTest($groupList[2])) && p($userIDList[2]) && e(13); //检查权限分组 3 的用户项目信息
r($group->getUserProgramsTest($groupList[3])) && p($userIDList[3]) && e(43); //检查权限分组 4 的用户项目信息
r($group->getUserProgramsTest($groupList[4])) && p($userIDList[4]) && e(57); //检查权限分组 5 的用户项目信息