#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel::getVisionList();
cid=1
pid=1

获取系统visions的数量 >> 2
获取test2用户的真实姓名 >> 研发综合界面

*/
$user = new userTest();
$visions = $user->getVisionListTest();

r(count($visions)) && p()      && e('2');            //获取系统visions的数量
r($visions)        && p('rnd') && e('研发综合界面'); //获取test2用户的真实姓名