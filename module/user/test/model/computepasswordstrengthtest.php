#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';
su('admin');

/**

title=测试 userModel->computePasswordStrength();
cid=1
pid=1

获取空密码的强度 >> 0
获取[123456]密码的强度 >> 0
获取[qaz123]密码的强度 >> 0
获取[Qaz67911334]密码的强度 >> 1
获取[!ENFBQfy3xpRarDwG3lN]密码的强度 >> 2

*/

$user = new userTest();

r($user->computePasswordStrengthTest(''))                     && p()  && e('0'); //获取空密码的强度
r($user->computePasswordStrengthTest('123456'))               && p()  && e('0'); //获取[123456]密码的强度
r($user->computePasswordStrengthTest('qaz123'))               && p()  && e('0'); //获取[qaz123]密码的强度
r($user->computePasswordStrengthTest('Qaz67911334'))          && p()  && e('1'); //获取[Qaz67911334]密码的强度
r($user->computePasswordStrengthTest('!ENFBQfy3xpRarDwG3lN')) && p()  && e('2'); //获取[!ENFBQfy3xpRarDwG3lN]密码的强度
