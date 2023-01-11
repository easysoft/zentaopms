#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';

su('admin');

$user = zdTable('user');
$user->gen(300);

/**

title=测试 userModel->identifyByPhpAuth();
cid=1
pid=1

验证admin用户 >> 1
验证游客用户 >> 0
获取不存在的用户 >> 0

*/

$user = new userTest();

$_SERVER['PHP_AUTH_USER'] = 'admin';
$_SERVER['PHP_AUTH_PW']   = 'a0933c1218a4e745bacdcf572b10eba7';
$normalUser = $user->identifyByPhpAuthTest('admin');

$_SERVER['PHP_AUTH_USER'] = 'guest';
$_SERVER['PHP_AUTH_PW']   = '';
$guest = $user->identifyByPhpAuthTest('guest');

$_SERVER['PHP_AUTH_USER'] = 'asdqwe';
$_SERVER['PHP_AUTH_PW']   = 'a0933c1218a4e745bacdcf572b10eba7';
$notExistUser = $user->identifyByPhpAuthTest('asdqwe');

r($normalUser)    && p() && e('1'); //验证admin用户
r($guest)         && p() && e('0'); //验证游客用户
r($notExistsUser) && p() && e('0'); //获取不存在的用户
