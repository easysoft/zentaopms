#!/usr/bin/env php
<?php

/**

title=测试 groupModel->update();
timeout=0
cid=1

- 测试更新分组为2的分组属性name @更新分组
- 测试更新分组为2的分组属性desc @更新描述
- 分组名称已存在第name条的0属性 @『分组名称』已经有『这是一个新的用户分组5』这条记录了，请调整后再试。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';
zdTable('group')->gen(5);
su('admin');

$updateName = array('name' => '更新分组',              'desc' => '');
$updateDesc = array('name' => '我是一个分组',          'desc' => '更新描述');
$repeatName = array('name' => '这是一个新的用户分组5', 'desc' => '');

$group = new groupTest();

r($group->updateTest(1, $updateName)) && p('name')   && e('更新分组');                                                              // 测试更新分组为2的分组
r($group->updateTest(2, $updateDesc)) && p('desc')   && e('更新描述');                                                              // 测试更新分组为2的分组
r($group->updateTest(3, $repeatName)) && p('name:0') && e('『分组名称』已经有『这是一个新的用户分组5』这条记录了，请调整后再试。'); // 分组名称已存在