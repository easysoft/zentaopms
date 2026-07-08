#!/usr/bin/env php
<?php

/**

title=测试 userModel::checkBeforeCreateOrEdit();
timeout=0
cid=19584

- 执行$guestUser属性result @0
- 执行$guestUser属性errors[account] @用户名已被系统预留
- 执行$noPassUser, true属性result @1
- 执行$normalUser属性result @1
- 执行$noPassUser2属性result @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $app;

$userTest = new userModelTest();
$random   = updateSessionRandom();
$verify   = md5($app->user->password . $random);

$guestUser   = (object)array('account' => 'guest',      'password1' => '123456', 'password2' => '123456', 'passwordLength' => 6, 'passwordStrength' => 1, 'verifyPassword' => $verify);
$noPassUser  = (object)array('account' => 'normaluser', 'password1' => '',       'password2' => '',       'verifyPassword' => $verify);
$normalUser  = (object)array('account' => 'testuser',   'password1' => '123456', 'password2' => '123456', 'passwordLength' => 6, 'passwordStrength' => 1, 'verifyPassword' => $verify);
$noPassUser2 = (object)array('account' => 'nopassuser', 'password1' => '',       'password2' => '',       'verifyPassword' => $verify);

r($userTest->checkBeforeCreateOrEditTest($guestUser))          && p('result')         && e(0);
r($userTest->checkBeforeCreateOrEditTest($guestUser))          && p('errors:account') && e('用户名已被系统预留');
r($userTest->checkBeforeCreateOrEditTest($noPassUser, true))   && p('result')         && e(1);
r($userTest->checkBeforeCreateOrEditTest($normalUser))         && p('result')         && e(1);
r($userTest->checkBeforeCreateOrEditTest($noPassUser2, false)) && p('result')         && e(0);
