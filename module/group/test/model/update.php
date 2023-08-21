#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';
zdTable('group')->gen(5);
su('admin');

/**

title=测试 groupModel->update();
timeout=0
cid=1


*/
$real_name   = array('name' => '我是一个分组', 'desc' => '');
$real_desc   = array('name' => '我是一个分组', 'desc' => '这是描述');
$repeat_name = array('name' => '这是一个新的用户分组5', 'desc' => '');

$group = new groupTest();

r($group->updateTest(2, $real_name))   && p('name')   && e('我是一个分组');                                                          // 测试更新分组为2的分组
r($group->updateTest(2, $real_desc))   && p('desc')   && e('这是描述');                                                              // 测试更新分组为2的分组
r($group->updateTest(2, $repeat_name)) && p('name:0') && e('『分组名称』已经有『这是一个新的用户分组5』这条记录了，请调整后再试。'); // 分组名称已存在
