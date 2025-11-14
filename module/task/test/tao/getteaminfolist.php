#!/usr/bin/env php
<?php

/**

title=测试 taskTao::getTeamInfoList();
timeout=0
cid=18885

- 执行taskTest模块的getTeamInfoListTest方法，参数是array 
 - 第0条的account属性 @admin
 - 第0条的estimate属性 @8
 - 第0条的consumed属性 @2
 - 第0条的left属性 @6
 - 第1条的account属性 @user1
 - 第1条的estimate属性 @16
 - 第1条的consumed属性 @8
 - 第1条的left属性 @8
 - 第2条的account属性 @user2
 - 第2条的estimate属性 @4
 - 第2条的consumed属性 @0
 - 第2条的left属性 @4
- 执行taskTest模块的getTeamInfoListTest方法，参数是array  @0
- 执行taskTest模块的getTeamInfoListTest方法，参数是array 
 - 第0条的account属性 @admin
 - 第0条的estimate属性 @8
 - 第0条的consumed属性 @2
 - 第0条的left属性 @6
 - 第2条的account属性 @user2
 - 第2条的estimate属性 @4
 - 第2条的consumed属性 @0
 - 第2条的left属性 @4
- 执行taskTest模块的getTeamInfoListTest方法，参数是array 
 - 第0条的account属性 @admin
 - 第0条的estimate属性 @8
 - 第0条的consumed属性 @2
 - 第0条的left属性 @6
 - 第1条的account属性 @user1
 - 第1条的estimate属性 @16
 - 第1条的consumed属性 @8
 - 第1条的left属性 @8
- 执行taskTest模块的getTeamInfoListTest方法，参数是array 
 - 第0条的account属性 @admin
 - 第0条的estimate属性 @0
 - 第0条的consumed属性 @0
 - 第0条的left属性 @0
 - 第1条的account属性 @user1
 - 第1条的estimate属性 @5
 - 第1条的consumed属性 @3
 - 第1条的left属性 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

su('admin');

$taskTest = new taskTest();

r($taskTest->getTeamInfoListTest(array('admin', 'user1', 'user2'), array('user', 'user', 'user'), array(8, 16, 4), array(2, 8, 0), array(6, 8, 4))) && p('0:account,estimate,consumed,left;1:account,estimate,consumed,left;2:account,estimate,consumed,left') && e('admin,8,2,6;user1,16,8,8;user2,4,0,4');
r($taskTest->getTeamInfoListTest(array(), array(), array(), array(), array())) && p() && e('0');
r($taskTest->getTeamInfoListTest(array('admin', '', 'user2'), array('user', 'user', 'user'), array(8, 16, 4), array(2, 8, 0), array(6, 8, 4))) && p('0:account,estimate,consumed,left;2:account,estimate,consumed,left') && e('admin,8,2,6;user2,4,0,4');
r($taskTest->getTeamInfoListTest(array('admin', 'user1'), array('user', 'user'), array(8, 16), array(2, 8), array(6, 8))) && p('0:account,estimate,consumed,left;1:account,estimate,consumed,left') && e('admin,8,2,6;user1,16,8,8');
r($taskTest->getTeamInfoListTest(array('admin', 'user1'), array('user', 'user'), array(0, 5), array(0, 3), array(0, 2))) && p('0:account,estimate,consumed,left;1:account,estimate,consumed,left') && e('admin,0,0,0;user1,5,3,2');