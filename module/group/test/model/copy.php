#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

zdTable('group')->gen(5);
su('admin');

/**

title=测试 groupModel->copy();
timeout=0
cid=1

*/

$groupID = 2;

$data      = array('name' => '这是一个复制的分组');
$existData = array('name' => '这是一个新的用户分组5');

$group = new groupTest();
r($group->copyTest($groupID, $data))      && p('name')   && e('这是一个复制的分组'); // 正常复制
r($group->copyTest($groupID, $existData)) && p('name:0') && e('『分组名称』已经有『这是一个新的用户分组5』这条记录了，请调整后再试。'); // 分组名称已存在
