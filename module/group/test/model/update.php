#!/usr/bin/env php
<?php

/**

title=测试 groupModel->update();
timeout=0
cid=16723

- 测试更新分组为2的分组属性name @更新分组
- 测试更新分组为2的分组属性desc @更新描述
- 测试更新分组为3的分组
 - 属性id @3
 - 属性name @这是一个新的用户分组5
- 分组名称已存在属性name @我是一个分组

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/group.unittest.class.php';
zenData('group')->gen(5);
su('admin');

$updateName = array('name' => '更新分组',              'desc' => '');
$updateDesc = array('name' => '我是一个分组',          'desc' => '更新描述');
$repeatName = array('name' => '这是一个新的用户分组5', 'desc' => '');

$group = new groupTest();

r($group->updateTest(1, $updateName)) && p('name')    && e('更新分组');                // 测试更新分组为2的分组
r($group->updateTest(2, $updateDesc)) && p('desc')    && e('更新描述');                // 测试更新分组为2的分组
r($group->updateTest(3, $repeatName)) && p('id,name') && e('3,这是一个新的用户分组5'); // 测试更新分组为3的分组
r($group->updateTest(3, $updateDesc)) && p('name')    && e('我是一个分组');            // 分组名称已存在
