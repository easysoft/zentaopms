#!/usr/bin/env php
<?php
/**
title=测试 userModel->processDisplayValue();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$users = array
(
    'user1' => (object)array('id' => 1, 'account' => 'user1', 'realname' => '用户1', 'deleted' => '0'),
    'user2' => (object)array('id' => 2, 'account' => 'user2', 'realname' => '',      'deleted' => '0'),
    'user3' => (object)array('id' => 3, 'account' => 'user3', 'realname' => '用户3', 'deleted' => '1')
);

$userTest = new userTest();

r($userTest->processDisplayValueTest($users, ''))                         && p('user1,user2,user3') && e('U:用户1,U:user2,U:user3'); // 参数为空，用户 1 显示首字母 + 姓名，用户 2 显示首字母 + 用户名，用户 3 显示首字母 + 用户名。
r($userTest->processDisplayValueTest($users, 'showid'))                   && p('user1,user2,user3') && e('1,2,3');                   // 参数为 showid，用户 1 显示用户 ID，用户 2 显示用户 ID，用户 3 显示用户 ID。
r($userTest->processDisplayValueTest($users, 'noletter'))                 && p('user1,user2,user3') && e('用户1,user2,user3');       // 参数为 noletter，用户 1 显示姓名，用户 2 显示用户名，用户 3 显示用户名。
r($userTest->processDisplayValueTest($users, 'realname'))                 && p('user1,user2,user3') && e('U:用户1,U:user2,U:用户3'); // 参数为 realname，用户 1 显示首字母 + 姓名，用户 2 显示首字母 + 用户名，用户 3 显示首字母 + 姓名。
r($userTest->processDisplayValueTest($users, 'showid,noletter'))          && p('user1,user2,user3') && e('1,2,3');                   // 参数为 showid,noletter，用户 1 显示用户 ID，用户 2 显示用户 ID，用户 3 显示用户 ID。
r($userTest->processDisplayValueTest($users, 'showid,realname'))          && p('user1,user2,user3') && e('1,2,3');                   // 参数为 showid,realname，用户 1 显示用户 ID，用户 2 显示用户 ID，用户 3 显示用户 ID。
r($userTest->processDisplayValueTest($users, 'noletter,realname'))        && p('user1,user2,user3') && e('用户1,user2,用户3');       // 参数为 noletter,realname，用户 1 显示姓名，用户 2 显示用户名，用户 3 显示姓名。
r($userTest->processDisplayValueTest($users, 'showid,noletter,realname')) && p('user1,user2,user3') && e('1,2,3');                   // 参数为 showid,noletter,realname，用户 1 显示用户 ID，用户 2 显示用户 ID，用户 3 显示用户 ID。
