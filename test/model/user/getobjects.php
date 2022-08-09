#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel::getObjects();
cid=1
pid=1

获取admin的所属项目列表,取ID为11的项目名字 >> 项目1
获取admin的所属项目列表,取ID为11的项目类型 >> project
获取admin的所属执行列表,取ID为120的项目名字 >> 迭代20
获取admin的已完成的所属项目列表,返回空 >> 0
获取test2用户的所属项目列表，返回空 >> 0
传入一个空用户名字段，返回空 >> 0

*/
$user = new userTest();
$adminProjects     = $user->getObjectsTest('admin', 'project',   'all',  'id_desc');
$adminDoneProjects = $user->getObjectsTest('admin', 'project',   'done', 'id_desc');
$adminExecutions   = $user->getObjectsTest('admin', 'execution', 'all',  'id_desc');
$test2Projects     = $user->getObjectsTest('test2', 'project',   'all',  'id_desc');
$emptyUser         = $user->getObjectsTest('',      'execution', 'all',  'id_desc');

r($adminProjects)     && p('11:name')  && e('项目1');   //获取admin的所属项目列表,取ID为11的项目名字
r($adminProjects)     && p('11:type')  && e('project'); //获取admin的所属项目列表,取ID为11的项目类型
r($adminExecutions)   && p('120:name') && e('迭代20');  //获取admin的所属执行列表,取ID为120的项目名字
r($adminDoneProjects) && p('')         && e('0');       //获取admin的已完成的所属项目列表,返回空
r($test2Projects)     && p('')         && e('0');       //获取test2用户的所属项目列表，返回空
r($emptyUser)         && p('')         && e('0');       //传入一个空用户名字段，返回空