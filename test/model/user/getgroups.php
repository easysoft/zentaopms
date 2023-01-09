#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

$user = zdTable('user');
$user->gen(50);

$userGroup = zdTable('usergroup');
$userGroup->gen(500);

/**

title=测试 userModel::getGroups();
cid=1
pid=1

获取admin所在的分组,为空 >> 0
通过test2所在的分组数量，1个 >> 1
传空用户名，返回空 >> 0

*/
$user = new userTest();

r($user->getGroupsTest('admin'))        && p() && e('0'); // 获取admin所在的分组,为空
r(count($user->getGroupsTest('test2'))) && p() && e('1'); // 通过test2所在的分组数量，1个
r($user->getGroupsTest(''))             && p() && e('0'); // 传空用户名，返回空
