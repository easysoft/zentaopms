#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

$user = zdTable('user');
$user->gen(50);
$team = zdTable('team');
$team->gen(200);
$project = zdTable('project');
$project->id->range('1-10,11-50,51-100');
$project->type->range('program{10},project{40},sprint{20},stage{20},kanban{10}');
$project->status->range('wait{50},done{10},closed{40}');
$project->vision->range('[rnd,lite]{100!}');
$project->name->range('1-100')->prefix('Object');
$project->multiple->range('1');
$project->deleted->range('0{90},1{10}');
$project->gen(100);

/**

title=测试 userModel::getObjects();
cid=1
pid=1

获取admin的所属项目列表,取ID为11的项目名字 >> Object11
获取admin的所属项目列表,取ID为11的项目类型 >> project
获取admin的所属执行列表,取ID为70的执行名字 >> 0
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

r($adminProjects)     && p('11:name')  && e('Object11'); //获取admin的所属项目列表,取ID为11的项目名字
r($adminProjects)     && p('11:type')  && e('project');  //获取admin的所属项目列表,取ID为11的项目类型
r($adminExecutions)   && p('70:name')  && e('0');        //获取admin的所属执行列表,取ID为70的执行名字,返回空
r($adminDoneProjects) && p('')         && e('0');        //获取admin的已完成的所属项目列表,返回空
r($test2Projects)     && p('')         && e('0');        //获取test2用户的所属项目列表，返回空
r($emptyUser)         && p('')         && e('0');        //传入一个空用户名字段，返回空
