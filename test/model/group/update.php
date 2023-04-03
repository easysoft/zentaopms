#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');

/**

title=测试 groupModel->update();
cid=1
pid=1

测试更新分组id为2的分组 >> 我是一个分组
测试更新分组2为受限用户分组 >> 这是一个分组

*/
$t_name = array('name' => '我是一个分组', 'desc' => '');
$t_desc = array('name' => '我是一个分组', 'desc' => '这是一个分组');

$group = new groupTest();
a($group->updateTest(2, $t_name));

r($group->updateTest(2, $t_name)) && p('name') && e('我是一个分组'); //测试更新分组id为2的分组
r($group->updateTest(2, $t_desc)) && p('desc') && e('这是一个分组'); //测试更新分组2为受限用户分组

