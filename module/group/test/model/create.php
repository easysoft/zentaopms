#!/usr/bin/env php
<?php

/**

title=测试 groupModel->create();
timeout=0
cid=1

- 测试正常创建分组的名称属性name @我是一个分组
- 测试正常创建同名分组第name条的0属性 @『分组名称』已经有『这是一个新的用户分组5』这条记录了，请调整后再试。
- 测试创建受限用户组分组属性role @limited

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

zdTable('group')->gen(5);
su('admin');

$normalGroup  = array('name' => '我是一个分组', 'desc' => '');
$repeatGroup  = array('name' => '这是一个新的用户分组5', 'desc' => '');
$limitedGroup = array('name' => '我是一个受限分组', 'desc' => '', 'role' => 'limited');

$group = new groupTest();

r($group->createObject($normalGroup))  && p('name')   && e('我是一个分组');                                                          // 测试正常创建分组的名称
r($group->createObject($repeatGroup))  && p('name:0') && e('『分组名称』已经有『这是一个新的用户分组5』这条记录了，请调整后再试。'); // 测试正常创建同名分组
r($group->createObject($limitedGroup)) && p('role')   && e('limited');                                                               // 测试创建受限用户组分组