#!/usr/bin/env php
<?php
/**
title=测试 userModel->checkPassword();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->gen(1);
zdTable('config')->config('config')->gen(3);

su('admin');

global $config;

$userTest = new userTest();

$user1 = (object)array('password1' => '');
$user2 = (object)array('password1' => '123456', 'password2' => '12345',  'passwordStrength' => 0, 'passwordLength' => 6);
$user3 = (object)array('password1' => '12345',  'password2' => '12345',  'passwordStrength' => 0, 'passwordLength' => 5);
$user4 = (object)array('password1' => '123456', 'password2' => '123456', 'passwordStrength' => 0, 'passwordLength' => 6);
$user5 = (object)array('password1' => md5('123456'), 'password2' => md5('123456'), 'passwordStrength' => 0, 'passwordLength' => 6);

/* 密码为空。*/
$result = $userTest->checkPasswordTest($user1);
r($result) && p('result') && e(0);                              // 密码为空，返回 false。
r($result) && p('errors:password1') && e('『密码』不能为空。'); // 密码为空，给出错误提示。

/* 密码为空，允许密码为空。*/
$result = $userTest->checkPasswordTest($user1, true);
r($result) && p('result') && e(1);              // 密码为空，允许密码为空，返回 true。
r($result) && p('errors:password1') && e('~~'); // 密码为空，允许密码为空，不给出错误提示。

/* 密码不为空，两次密码不同，未设置密码安全属性。*/
unset($config->safe->mode);
$result = $userTest->checkPasswordTest($user2);
r($result) && p('result') && e(0);                              // 密码不为空，两次密码不同，未设置密码安全属性，返回 false。
r($result) && p('errors:password1') && e('两次密码应该相同。'); // 密码不为空，两次密码不同，未设置密码安全属性，给出错误提示。

/* 密码不为空，两次密码相同，未设置密码安全属性。*/
unset($config->safe->mode);
$result = $userTest->checkPasswordTest($user3);
r($result) && p('result') && e(0);                             // 密码不为空，两次密码相同，未设置密码安全属性，返回 false。
r($result) && p('errors:password1') && e('密码须6位及以上。'); // 密码不为空，两次密码相同，未设置密码安全属性，给出错误提示。

/* 密码不为空，两次密码相同，密码安全属性设为小于不检查的自定义数字。*/
$config->safe->mode = -1;
$result = $userTest->checkPasswordTest($user3);
r($result) && p('result') && e(0);                             // 密码不为空，两次密码相同，密码安全属性设为小于不检查的自定义数字，返回 false。
r($result) && p('errors:password1') && e('密码须6位及以上。'); // 密码不为空，两次密码相同，密码安全属性设为小于不检查的自定义数字，给出错误提示。

/* 密码不为空，两次密码相同，密码安全属性设为不检查。*/
$config->safe->mode = 0;
$result = $userTest->checkPasswordTest($user3);
r($result) && p('result') && e(0);                             // 密码不为空，两次密码相同，密码安全属性设为不检查，返回 false。
r($result) && p('errors:password1') && e('密码须6位及以上。'); // 密码不为空，两次密码相同，密码安全属性设为不检查，给出错误提示。

/* 密码不为空，两次密码相同，密码安全属性设为中。*/
$config->safe->mode = 1;
$result = $userTest->checkPasswordTest($user3);
r($result) && p('result') && e(0);                                                       // 密码不为空，两次密码相同，密码安全属性设为中，返回 false。
r($result) && p('errors:password1') && e('密码必须6位及以上，且包含大小写字母、数字。'); // 密码不为空，两次密码相同，密码安全属性设为中，给出错误提示。

/* 密码不为空，两次密码相同，密码安全属性设为强。*/
$config->safe->mode = 2;
$result = $userTest->checkPasswordTest($user3);
r($result) && p('result') && e(0);                                                                  // 密码不为空，两次密码相同，密码安全属性设为强，返回 false。
r($result) && p('errors:password1') && e('密码必须10位及以上，且包含大小写字母、数字、特殊符号。'); // 密码不为空，两次密码相同，密码安全属性设为强，给出错误提示。

/* 密码不为空，两次密码相同，密码安全属性设为大于强的自定义数字。*/
$config->safe->mode = 3;
$result = $userTest->checkPasswordTest($user3);
r($result) && p('result') && e(0);                                      // 密码不为空，两次密码相同，密码安全属性设为大于强的自定义数字，返回 false。
r($result) && p('errors:password1') && e('您的密码强度小于系统设定。'); // 密码不为空，两次密码相同，密码安全属性设为大于强的自定义数字，密码强度小于系统设定，给出错误提示。

$config->safe->mode = 0;

/* 密码不为空，两次密码相同，未设置修改弱口令密码属性。*/
unset($config->safe->changeWeak);
$result = $userTest->checkPasswordTest($user4);
r($result) && p('result') && e(1);              // 密码不为空，两次密码相同，未设置修改弱口令密码属性，返回 true。
r($result) && p('errors:password1') && e('~~'); // 密码不为空，两次密码相同，未设置修改弱口令密码属性，不给出错误提示。

/* 密码不为空，两次密码相同，修改弱口令密码属性设置为不强制。*/
$config->safe->changeWeak = 0;
$result = $userTest->checkPasswordTest($user4);
r($result) && p('result') && e(1);              // 密码不为空，两次密码相同，修改弱口令密码属性设为不强制，返回 true。
r($result) && p('errors:password1') && e('~~'); // 密码不为空，两次密码相同，修改弱口令密码属性设为不强制，不给出错误提示。

/* 密码不为空，两次密码相同，密码为明文，修改弱口令密码属性设为必须修改，使用自定义常用弱口令检查。*/
$config->safe->changeWeak = 1;
$result = $userTest->checkPasswordTest($user4);
r($result) && p('result') && e(0);                                                                                                                                  // 密码不为空，两次密码相同，密码为明文，修改弱口令密码属性设为必须修改，使用自定义常用弱口令检查，返回 false。
r($result) && p('errors:password1', '|') && e('密码不能使用【123456,password,12345,12345678,qwerty,123456789,1234,1234567,abc123,111111,123123】这些常用弱口令。'); // 密码不为空，两次密码相同，密码为明文，修改弱口令密码属性设为必须修改，使用自定义常用弱口令检查，给出错误提示。

/* 密码不为空，两次密码相同，密码为 md5 加密，修改弱口令密码属性设为必须修改，使用自定义常用弱口令检查。*/
$result = $userTest->checkPasswordTest($user5);
r($result) && p('result') && e(0);                                                                                                                                  // 密码不为空，两次密码相同，密码为 md5 加密，修改弱口令密码属性设为必须修改，使用自定义常用弱口令检查，返回 false。
r($result) && p('errors:password1', '|') && e('密码不能使用【123456,password,12345,12345678,qwerty,123456789,1234,1234567,abc123,111111,123123】这些常用弱口令。'); // 密码不为空，两次密码相同，密码为 md5 加密，修改弱口令密码属性设为必须修改，使用自定义常用弱口令检查，给出错误提示。

/* 密码不为空，两次密码相同，密码为 md5 加密，修改弱口令密码属性设为必须修改，使用系统内置常用弱口令检查。*/
unset($config->safe->weak); // 删除自定义常用弱口令。
$result = $userTest->checkPasswordTest($user5);
r($result) && p('result') && e(0);                                                                                                                                  // 密码不为空，两次密码相同，密码为 md5 加密，修改弱口令密码属性设为必须修改，使用系统内置常用弱口令检查，返回 false。
r($result) && p('errors:password1', '|') && e('密码不能使用【123456,password,12345,12345678,qwerty,123456789,1234,1234567,abc123,111111,123123】这些常用弱口令。'); // 密码不为空，两次密码相同，密码为 md5 加密，修改弱口令密码属性设为必须修改，使用系统内置常用弱口令检查，给出错误提示。
