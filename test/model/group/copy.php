#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');

/**

title=测试 groupModel->copy();
cid=1
pid=1

这是一个复制的分组 >> 这是一个复制的分组
『分组名称』已经有『我是一个分组』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。 >> 『分组名称』已经有『我是一个分组』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。

*/

$groupID = 2;

$data      = array('name' => '这是一个复制的分组');
$existData = array('name' => '我是一个分组');

$group = new groupTest();
r($group->copyTest($groupID, $data)) && p('name') && e('这是一个复制的分组'); // 这是一个复制的分组
r($group->copyTest($groupID, $existData)) && p('name:0') && e('『分组名称』已经有『我是一个分组』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。'); // 『分组名称』已经有『我是一个分组』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。