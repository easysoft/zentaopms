#!/usr/bin/env php
<?php
/**
title=测试 userModel->checkBeforeBatchUpdate();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->config('user')->gen(1);

su('admin');

global $app, $config, $tester;

/* 必填项为空的用户。*/
$users1 = array
(
    (object)array('realname' => '', 'visions' => '', 'email' => '', 'phone' => '', 'mobile' => ''),
);

/* 联系方式不符合格式的用户。*/
$users2 = array
(
    (object)array('realname' => 'user1', 'visions' => 'rnd,lite', 'email' => '@a.com', 'phone' => '868930', 'mobile' => '1388888888')
);

/* 符合所有检查项的用户。*/
$users3 = array
(
    (object)array('realname' => 'user1', 'visions' => 'rnd', 'email' => 'a@a.com', 'phone' => '86893032', 'mobile' => '13888888888'),
);

$random         = updateSessionRandom();
$verifyPassword = md5(md5('123456') . $random);

$userTest = new userTest();

/* 必填项为空的用户。*/
$result = $userTest->checkBeforeBatchUpdateTest($users1, '');
r($result) && p('result')                && e(0);                                          // 检查未通过，返回 false。
r($result) && p('errors:realname[0]')    && e('『姓名』不能为空。');                       // 姓名为空错误提示。
r($result) && p('errors:visions[0][]')   && e('『界面类型』不能为空。');                   // 界面类型为空错误提示。
r($result) && p('errors:email[0]')       && e('` `');                                       // 邮箱无错误提示。
r($result) && p('errors:phone[0]')       && e('` `');                                       // 电话无错误提示。
r($result) && p('errors:mobile[0]')      && e('` `');                                       // 手机无错误提示。
r($result) && p('errors:verifyPassword') && e('验证失败，请检查您的系统登录密码是否正确'); // 验证密码错误提示。

/* 联系方式不符合格式的用户。*/
$result = $userTest->checkBeforeBatchUpdateTest($users2, $verifyPassword);
r($result) && p('result')                && e(0);                                // 检查未通过，返回 false。
r($result) && p('errors:realname[0]')    && e('` `');                             // 姓名无错误提示。
r($result) && p('errors:visions[0][]')   && e('` `');                             // 界面类型无错误提示。
r($result) && p('errors:email[0]')       && e('『邮箱』应当为合法的EMAIL。');    // 邮箱格式错误提示。
r($result) && p('errors:phone[0]')       && e('『电话』应当为合法的电话号码。'); // 电话格式错误提示。
r($result) && p('errors:mobile[0]')      && e('『手机』应当为合法的手机号码。'); // 手机格式错误提示。
r($result) && p('errors:verifyPassword') && e('` `');                             // 验证密码无错误提示。

/* 符合所有检查项的用户。*/
$result = $userTest->checkBeforeBatchUpdateTest($users3, $verifyPassword);
r($result) && p('result')                && e(1);    // 检查通过，返回 true。
r($result) && p('errors:realname[0]')    && e('` `'); // 姓名无错误提示。
r($result) && p('errors:visions[0][]')   && e('` `'); // 界面类型无错误提示。
r($result) && p('errors:email[0]')       && e('` `'); // 邮箱无错误提示。
r($result) && p('errors:phone[0]')       && e('` `'); // 电话无错误提示。
r($result) && p('errors:mobile[0]')      && e('` `'); // 手机无错误提示。
r($result) && p('errors:verifyPassword') && e('` `'); // 验证密码无错误提示。
