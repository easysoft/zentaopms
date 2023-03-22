#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');

/**

title=测试 groupModel->create();
cid=1
pid=1

测试正常创建分组的名称 >> 我是一个分组
测试创建受限用户组分组 >> limited

*/

$limited = array('0' => 0);

$t_name    = array('name' => '我是一个分组', 'desc' => '');
$t_limited = array('name' => '我是一个分组', 'desc' => '', 'limited' => $limited);

$group = new groupTest();

r($group->createObject($t_name))    && p('name') && e('我是一个分组'); // 测试正常创建分组的名称
r($group->createObject($t_limited)) && p('role') && e('limited');      // 测试创建受限用户组分组

