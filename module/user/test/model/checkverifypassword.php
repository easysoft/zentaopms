#!/usr/bin/env php
<?php
/**
title=测试 userModel->checkVerifyPassword();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->config('user')->gen(1);

su('admin');

$random = updateSessionRandom();

$userTest = new userTest();

$result1 = $userTest->checkVerifyPasswordTest('');
r($result1) && p('result') && e(0);                                                         // 验证密码传空字符串返回 false。
r($result1) && p('errors:verifyPassword') && e('验证失败，请检查您的系统登录密码是否正确'); // 验证密码传空字符串提示信息。

$result2 = $userTest->checkVerifyPasswordTest(0);
r($result2) && p('result') && e(0);                                                         // 验证密码传数字 0 返回 false。
r($result2) && p('errors:verifyPassword') && e('验证失败，请检查您的系统登录密码是否正确'); // 验证密码传数字 0 提示信息。

$result3 = $userTest->checkVerifyPasswordTest('0');
r($result3) && p('result') && e(0);                                                         // 验证密码传字符串 0 返回 false。
r($result3) && p('errors:verifyPassword') && e('验证失败，请检查您的系统登录密码是否正确'); // 验证密码传字符串 0 提示信息。

$result4 = $userTest->checkVerifyPasswordTest(md5($random));
r($result4) && p('result') && e(0);                                                         // 验证密码不包含密码只包含随机数返回 false。
r($result4) && p('errors:verifyPassword') && e('验证失败，请检查您的系统登录密码是否正确'); // 验证密码不包含密码只包含随机数提示信息。

$result5 = $userTest->checkVerifyPasswordTest(md5(md5('123456')));
r($result5) && p('result') && e(0);                                                         // 验证密码只包含密码不包含随机数返回 false。
r($result5) && p('errors:verifyPassword') && e('验证失败，请检查您的系统登录密码是否正确'); // 验证密码只包含密码不包含随机数提示信息。

$result6 = $userTest->checkVerifyPasswordTest(md5(md5('654321') . $random));
r($result6) && p('result') && e(0);                                                         // 验证密码包含错误的密码和正确的随机数返回 false。
r($result6) && p('errors:verifyPassword') && e('验证失败，请检查您的系统登录密码是否正确'); // 验证密码包含错误的密码和正确的随机数提示信息。

$result7 = $userTest->checkVerifyPasswordTest(md5(md5('123456') . mt_rand()));
r($result7) && p('result') && e(0);                                                         // 验证密码包含正确的密码和错误的随机数返回 false。
r($result7) && p('errors:verifyPassword') && e('验证失败，请检查您的系统登录密码是否正确'); // 验证密码包含正确的密码和错误的随机数提示信息。

$result8 = $userTest->checkVerifyPasswordTest(md5(md5('123456') . $random));
r($result8) && p('result') && e(1);                   // 验证密码包含正确的密码和正确的随机数返回 true。
r($result8) && p('errors:verifyPassword') && e('~~'); // 验证密码包含正确的密码和正确的随机数提示信息。
