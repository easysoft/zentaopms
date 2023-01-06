#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel::getGroupsByVisions();
cid=1
pid=1

获取系统中所有研发界面分组的数量 >> 13
获取系统中所有迅捷界面分组的数量 >> 3
传空Visions，返回空数组 >> 0
获取系统中所有用户分组的数量 >> 16

*/
$user = new userTest();

r(count($user->getGroupsByVisionsTest(array('rnd'))))         && p() && e('13'); // 获取系统中所有研发界面分组的数量
r(count($user->getGroupsByVisionsTest(array('lite'))))        && p() && e('3');  // 获取系统中所有迅捷界面分组的数量
r($user->getGroupsByVisionsTest(array()))                     && p() && e('0');  // 传空Visions，返回空数组
r(count($user->getGroupsByVisionsTest(array('rnd','lite'))))  && p() && e('16'); // 获取系统中所有用户分组的数量
