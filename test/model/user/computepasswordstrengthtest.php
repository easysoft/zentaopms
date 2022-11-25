#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->computePasswordStrength();
cid=1
pid=1

获取空密码的强度 >> 0
获取[123456]密码的强度 >> 0
获取[qaz123]密码的强度 >> 0
获取[qaz67911334]密码的强度 >> 1
获取[ZENFBQfy3xpRarDwG3lN]密码的强度 >> 2

*/

$user = new userTest();

r($user->computePasswordStrengthTest(''))                     && p()  && e('0'); //获取空密码的强度
r($user->computePasswordStrengthTest('123456'))               && p()  && e('0'); //获取[123456]密码的强度
r($user->computePasswordStrengthTest('qaz123'))               && p()  && e('0'); //获取[qaz123]密码的强度
r($user->computePasswordStrengthTest('qaz67911334'))          && p()  && e('1'); //获取[qaz67911334]密码的强度
r($user->computePasswordStrengthTest('ZENFBQfy3xpRarDwG3lN')) && p()  && e('2'); //获取[ZENFBQfy3xpRarDwG3lN]密码的强度