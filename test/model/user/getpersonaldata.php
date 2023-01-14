#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
zdTable('user')->gen(500);
zdTable('story')->gen(400);
zdTable('case')->gen(400);
zdTable('bug')->gen(300);

su('admin');

/**

title=测试 userModel::getPersonalData();
cid=1
pid=1

获取admin用户的createdBugs数量 >> 30
获取test2用户createdCases数量 >> 4
当获取一个空用户的数据时，系统默认返回当前登录用户的数据 >> 3

*/
$user = new userTest();
$adminData = $user->getPersonalDataTest('admin');
$test2Data = $user->getPersonalDataTest('test2');
$emptyData = $user->getPersonalDataTest('');

r($adminData) && p('createdBugs')  && e('30'); //获取admin用户的createdBugs数量
r($test2Data) && p('createdCases') && e('4');   //获取test2用户createdCases数量
r($emptyData) && p('createdTodos') && e('3');   //当获取一个空用户的数据时，系统默认返回当前登录用户的数据