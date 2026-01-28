#!/usr/bin/env php
<?php

/**

title=测试 groupModel->create();
timeout=0
cid=16699

- 测试正常创建分组的名称属性name @我是一个分组
- 测试正常创建同名分组第name条的0属性 @『分组名称』已经有『这是一个新的用户分组5』这条记录了，请调整后再试。
- 测试分组的所属界面属性vision @rnd
- 测试创建受限用户组分组属性role @limited
- 测试创建另一种受限用户组分组属性role @limited

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('group')->gen(5);
su('admin');

$normalGroup   = array('name' => '我是一个分组', 'desc' => '', 'project' => '0');
$repeatGroup   = array('name' => '这是一个新的用户分组5', 'desc' => '', 'project' => '0');
$visionGroup   = array('name' => '我是当前管理界面的分组', 'desc' => '', 'project' => '0');
$limitedGroup1 = array('name' => '我是一个受限分组', 'desc' => '', 'role' => 'limited', 'project' => '0');
$limitedGroup2 = array('name' => '我是另一个受限分组', 'desc' => '', 'limited' => 1, 'project' => '0');

$group = new groupModelTest();

r($group->createObject($normalGroup))  && p('name')   && e('我是一个分组');                                                          // 测试正常创建分组的名称
r($group->createObject($repeatGroup))  && p('name:0') && e('『分组名称』已经有『这是一个新的用户分组5』这条记录了，请调整后再试。'); // 测试正常创建同名分组
r($group->createObject($visionGroup))  && p('vision') && e('rnd');                                                                   // 测试分组的所属界面
r($group->createObject($limitedGroup1)) && p('role')  && e('limited');                                                               // 测试创建受限用户组分组
r($group->createObject($limitedGroup2)) && p('role')  && e('limited');                                                               // 测试创建另一种受限用户组分组