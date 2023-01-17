#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
zdTable('usertpl')->gen(10);
zdTable('user')->gen(1000);
su('admin');

/**

title=测试 userModel::getUserTemplates();
cid=1
pid=1

查询当前用户能够使用的exporttask类型的模板 >> admin
查询当前用户能够使用的story类型的模板 >> dev10
查询当前用户能够使用的exportbug类型的模板 >> 0
查询当前用户能够使用的exportstory类型的模板 >> test10

*/
$typeList = array('exporttask', 'story', 'exportbug', 'exportstory');

$user = new userTest();
r($user->getUserTemplatesTest($typeList[0])) && p('0:account') && e('admin');  //查询当前用户能够使用的exporttask类型的模板
r($user->getUserTemplatesTest($typeList[1])) && p('0:account') && e('dev10');  //查询当前用户能够使用的story类型的模板
r($user->getUserTemplatesTest($typeList[2])) && p('0:account') && e('0');      //查询当前用户能够使用的exportbug类型的模板
r($user->getUserTemplatesTest($typeList[3])) && p('0:account') && e('test10'); //查询当前用户能够使用的exportstory类型的模板