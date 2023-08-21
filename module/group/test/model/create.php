#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

zdTable('group')->gen(5);
su('admin');

/**

title=测试 groupModel->create();
timeout=0
cid=1

*/

$normal_name = array('name' => '我是一个分组', 'desc' => '');
$repeat_name = array('name' => '这是一个新的用户分组5', 'desc' => '');
$limited     = array('name' => '我是一个受限分组', 'desc' => '', 'role' => 'limited');

$group = new groupTest();

r($group->createObject($normal_name)) && p('name')   && e('我是一个分组');                                                          // 测试正常创建分组的名称
r($group->createObject($repeat_name)) && p('name:0') && e('『分组名称』已经有『这是一个新的用户分组5』这条记录了，请调整后再试。'); // 测试正常创建同名分组
r($group->createObject($limited))     && p('role')   && e('limited');                                                               // 测试创建受限用户组分组

