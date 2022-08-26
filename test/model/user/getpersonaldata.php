#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel::getPersonalData();
cid=1
pid=1

获取admin用户的createdBugs数量 >> 270
获取test2用户createdCases数量 >> 4
当获取一个空用户的数据时，系统默认返回当前登录用户的数据 >> 2

*/
$user = new userTest();
$adminData = $user->getPersonalDataTest('admin');
$test2Data = $user->getPersonalDataTest('test2');
$emptyData = $user->getPersonalDataTest('');

r($adminData) && p('createdBugs')  && e('270'); //获取admin用户的createdBugs数量
r($test2Data) && p('createdCases') && e('4');   //获取test2用户createdCases数量
r($emptyData) && p('createdTodos') && e('2');   //当获取一个空用户的数据时，系统默认返回当前登录用户的数据