#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

zdTable('user')->gen(10);

/**

title=测试 userModel::getById();
cid=1
pid=1

判断用户id为1，action为空时，是否可点击 >> 1
判断用户id为1，action为unbind，是否可点击 >> 1
判断用户id为1，action为unlock，是否可点击 >> 0
判断用户id为1，action为undefind，是否可点击 >> 1
判断用户id为10，action为unbind，是否可点击 >> 0
判断用户id为10，action为unlock，是否可点击 >> 0

*/
$actionList[0] = 'unbind';
$actionList[1] = 'unlock';
$actionList[2] = 'undefind';

$user = new userTest();

r($user->isClickableTest(1))                 && p() && e('1'); // 判断用户id为1，action为空时，是否可点击
r($user->isClickableTest(1, $actionList[0])) && p() && e('1'); // 判断用户id为1，action为unbind，是否可点击
r($user->isClickableTest(1, $actionList[1])) && p() && e('0'); // 判断用户id为1，action为unlock，是否可点击
r($user->isClickableTest(1, $actionList[2])) && p() && e('1'); // 判断用户id为1，action为undefind，是否可点击
r($user->isClickableTest(10,$actionList[0])) && p() && e('0'); // 判断用户id为10，action为unbind，是否可点击
r($user->isClickableTest(10,$actionList[1])) && p() && e('0'); // 判断用户id为10，action为unlock，是否可点击