#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->gen(300);
su('admin');

/**

title=测试 userModel->identifyByCookie();
cid=1
pid=1

验证admin用户 >> 1
验证游客用户 >> 0
获取不存在的用户 >> 0

*/

$user = new userTest();

$_COOKIE['za'] = 'admin';
$_COOKIE['zp'] = 'a0933c1218a4e745bacdcf572b10eba7';
$normalUser = $user->identifyByCookieTest('admin');

$_COOKIE['za'] = 'guest';
$_COOKIE['zp'] = '';
$guest = $user->identifyByCookieTest('guest');

$_COOKIE['za'] = 'asdqwe';
$_COOKIE['zp'] = 'a0933c1218a4e745bacdcf572b10eba7';
$notExistUser = $user->identifyByCookieTest('asdqwe');

r($normalUser)    && p() && e('1'); //验证admin用户
r($guest)         && p() && e('0'); //验证游客用户
r($notExistUser)  && p() && e('0'); //获取不存在的用户
