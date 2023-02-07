#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
zdTable('usertpl')->gen(10);
zdTable('user')->gen(500);
su('admin');

/**

title=测试 userModel::saveUserTemplate();
cid=1
pid=1

 >> bug
 >> task
 >> task
插入一条Bug用户模板，查看数量 >> 1
插入两条任务类型的用户模板，查看数量 >> 2
查看插入的模板二的名字 >> Admin的模板二
查看插入的模板二的内容 >> <p>这是一段模板内容</p>

*/
$user = new userTest();
$tpl1['title']   = 'Admin的模板一';
$tpl1['content'] = '<p>这是一段模板内容</p>';

$tpl2['title']   = 'Admin的模板二';
$tpl2['content'] = '<p>这是二段模板内容</p>';

$tpl3['title']   = 'Admin的模板三';
$tpl3['content'] = '<p>这是三段模板内容</p>';

$_POST = $tpl1;
$tpls1 = $user->saveUserTemplate('bug');

$_POST = $tpl2;
$tpls2 = $user->saveUserTemplate('task');

$_POST = $tpl3;
$tpls3 = $user->saveUserTemplate('task');

r(count($tpls1)) && p()            && e('1');                       //插入一条Bug用户模板，查看数量
r(count($tpls3)) && p()            && e('2');                       //插入两条任务类型的用户模板，查看数量
r($tpls2)        && p('0:title')   && e('Admin的模板二');           //查看插入的模板二的名字
r($tpls2)        && p('0:content') && e('<p>这是一段模板内容</p>'); //查看插入的模板二的内容