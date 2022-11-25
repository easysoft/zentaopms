#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');

/**

title=测试 groupModel->getList();
cid=1
pid=1

测试获取 groupList 的信息 >> 其他,others,其他
测试获取 groupList 的信息 >> guest,guest,For guest
测试获取 groupList 的信息 >> 项目管理员,projectAdmin,项目管理员可以维护项目的权限

*/

$group = new groupTest();

r($group->getListTest()) && p('9:name,role,desc')  && e('其他,others,其他');                                     // 测试获取 groupList 的信息
r($group->getListTest()) && p('10:name,role,desc') && e('guest,guest,For guest');                                // 测试获取 groupList 的信息
r($group->getListTest()) && p('12:name,role,desc') && e('项目管理员,projectAdmin,项目管理员可以维护项目的权限'); // 测试获取 groupList 的信息