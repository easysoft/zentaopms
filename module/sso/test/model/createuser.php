#!/usr/bin/env php
<?php

/**

title=ssoModel->createUser();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/sso.class.php';
su('admin');

zdTable('user')->gen(5);

$sso = new ssoTest();

$newUser = new stdclass();
$newUser->account  = 'admin';
$newUser->realname = '绑定用户1';
$newUser->email    = 'user@test.com';
$newUser->gender   = 'm';
$newUser->ranzhi   = 'bindUser1';

r($sso->createTest($newUser)) && p('data')  && e('该用户名已经存在，请更换用户名，或直接绑定到该用户。');      // 用户存在的情况

$newUser->account  = 'bindUser1';
r($sso->createTest($newUser))    && p('id') && e('6'); // 用户不存在的情况

$newUser->account  = 'bindUser2';
$newUser->gender   = '';
r($sso->createTest($newUser)['data']['gender'][0]) && p() && e('『性别』不符合格式，应当为:『/f|m/』。');      // 用户信息错误的情况
