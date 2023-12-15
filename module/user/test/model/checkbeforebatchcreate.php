#!/usr/bin/env php
<?php
/**
title=测试 userModel->checkBeforeBatchCreate();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->config('user')->gen(1);
zdTable('config')->gen(0);  // config 表置空，防止安全设置影响测试结果。

su('admin');

global $app, $config, $tester;

/* 用于测试用户名合法性、必填项为空、联系方式不符合格式等异常数据。*/
$users1 = array
(
    (object)array('account' => '!@#$%', 'realname' => '',      'visions' => '',         'password' => '',          'email' => '',       'phone' => '',       'mobile' => ''),
    (object)array('account' => 'guest', 'realname' => 'guest', 'visions' => 'rnd',      'password' => '12345',     'email' => '',       'phone' => '',       'mobile' => ''),
    (object)array('account' => 'admin', 'realname' => 'admin', 'visions' => 'lite',     'password' => '123456',    'email' => '',       'phone' => '',       'mobile' => ''),
    (object)array('account' => 'user1', 'realname' => 'user1', 'visions' => 'rnd,lite', 'password' => 'Admin123',  'email' => '',       'phone' => '',       'mobile' => ''),
    (object)array('account' => 'user1', 'realname' => 'user1', 'visions' => 'rnd,lite', 'password' => '@Admin123', 'email' => '@a.com', 'phone' => '868930', 'mobile' => '1388888888')
);

/* 用于测试密码强度的数据。*/
$users2 = array
(
    (object)array('account' => 'user1', 'realname' => 'user1', 'visions' => 'rnd', 'password' => '123456',     'email' => '', 'phone' => '', 'mobile' => ''),
    (object)array('account' => 'user2', 'realname' => 'user2', 'visions' => 'rnd', 'password' => 'Admin123',   'email' => '', 'phone' => '', 'mobile' => ''),
    (object)array('account' => 'user3', 'realname' => 'user3', 'visions' => 'rnd', 'password' => '@Admin123@', 'email' => '', 'phone' => '', 'mobile' => '')
);

/* 用于测试系统内置常用弱口令和自定义常用弱口令的数据。*/
$users3 = array
(
    (object)array('account' => 'user1', 'realname' => 'user1', 'visions' => 'rnd', 'password' => '123456',     'email' => '', 'phone' => '', 'mobile' => ''),
    (object)array('account' => 'user2', 'realname' => 'user2', 'visions' => 'rnd', 'password' => '1234567890', 'email' => '', 'phone' => '', 'mobile' => '')
);

/* 用户测试符合各项要求的数据。*/
$users4 = array
(
    (object)array('account' => 'user1', 'realname' => 'user1', 'visions' => 'rnd', 'password' => '@Admin123@', 'email' => 'a@a.com', 'phone' => '86893032', 'mobile' => '13888888888'),
);

$random         = updateSessionRandom();
$verifyPassword = md5(md5('123456') . $random);

$userTest = new userTest();

$result = $userTest->checkBeforeBatchCreateTest($users1, '');
r($result) && p('result')                && e(0);                                                                                             // 检查未通过，返回 false。
r($result) && p('errors:account[0]')     && e('『用户名』只能是字母、数字或下划线的组合三位以上。');                                          // 用户名不符合规则错误提示。
r($result) && p('errors:account[1]')     && e('用户名已被系统预留');                                                                          // 用户名被系统预留错误提示。
r($result) && p('errors:account[2]')     && e('『用户名』已经有『admin』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 用户名已存在错误提示。
r($result) && p('errors:account[4]')     && e('『用户名』已经有『user1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 用户名已存在错误提示。
r($result) && p('errors:realname[0]')    && e('『姓名』不能为空。');                                                                          // 姓名为空错误提示。
r($result) && p('errors:visions[0][]')   && e('『界面类型』不能为空。');                                                                      // 界面类型为空错误提示。
r($result) && p('errors:password[0]')    && e('『密码』不能为空。');                                                                          // 密码为空错误提示。
r($result) && p('errors:password[1]')    && e('密码必须为六位及以上');                                                                        // 密码长度小于 6 位错误提示。
r($result) && p('errors:email[4]')       && e('『邮箱』应当为合法的EMAIL。');                                                                 // 邮箱格式错误提示。
r($result) && p('errors:phone[4]')       && e('『电话』应当为合法的电话号码。');                                                              // 电话格式错误提示。
r($result) && p('errors:mobile[4]')      && e('『手机』应当为合法的手机号码。');                                                              // 手机格式错误提示。
r($result) && p('errors:verifyPassword') && e('验证失败，请检查您的系统登录密码是否正确');                                                    // 验证密码错误提示。

/* 未设置密码安全属性。*/
$config->safe = new stdclass();
$result = $userTest->checkBeforeBatchCreateTest($users2, $verifyPassword);
r($result) && p('result')             && e(1);    // 未设置密码安全属性，返回 false。
r($result) && p('errors:password[0]') && e('~~'); // 未设置密码安全属性，密码只包含数字，无错误提示。
r($result) && p('errors:password[1]') && e('~~'); // 未设置密码安全属性，密码包含大小写字母和数字，无错误提示。
r($result) && p('errors:password[2]') && e('~~'); // 未设置密码安全属性，密码包含大小写字母、数字和特殊字符，无错误提示。

/* 密码安全属性设为小于不检查的自定义数字。*/
$config->safe->mode = -1;
$result = $userTest->checkBeforeBatchCreateTest($users2, $verifyPassword);
r($result) && p('errors:password[0]') && e('~~'); // 密码安全属性设为小于不检查的自定义数字，密码只包含数字，无错误提示。
r($result) && p('errors:password[1]') && e('~~'); // 密码安全属性设为小于不检查的自定义数字，密码包含大小写字母和数字，无错误提示。
r($result) && p('errors:password[2]') && e('~~'); // 密码安全属性设为小于不检查的自定义数字，密码包含大小写字母、数字和特殊字符，无错误提示。

/* 密码安全属性设为不检查。*/
$config->safe->mode = 0;
$result = $userTest->checkBeforeBatchCreateTest($users2, $verifyPassword);
r($result) && p('errors:password[0]') && e('~~'); // 密码安全属性设为不检查，密码只包含数字，无错误提示。
r($result) && p('errors:password[1]') && e('~~'); // 密码安全属性设为不检查，密码包含大小写字母和数字，无错误提示。
r($result) && p('errors:password[2]') && e('~~'); // 密码安全属性设为不检查，密码包含大小写字母、数字和特殊字符，无错误提示。

/* 密码安全属性设为中。*/
$config->safe->mode = 1;
$result = $userTest->checkBeforeBatchCreateTest($users2, $verifyPassword);
r($result) && p('errors:password[0]') && e('密码强度小于系统设定。'); // 密码安全属性设为中，密码只包含数字，密码强度小于系统设定，有错误提示。
r($result) && p('errors:password[1]') && e('~~');                     // 密码安全属性设为中，密码包含大小写字母和数字，密码强度符合系统设定，无错误提示。
r($result) && p('errors:password[2]') && e('~~');                     // 密码安全属性设为中，密码包含大小写字母、数字和特殊字符，密码强度符合系统设定，无错误提示。

/* 密码安全属性设为强。*/
$config->safe->mode = 2;
$result = $userTest->checkBeforeBatchCreateTest($users2, $verifyPassword);
r($result) && p('errors:password[0]') && e('密码强度小于系统设定。'); // 密码安全属性设为强，密码只包含数字，密码强度小于系统设定，有错误提示。
r($result) && p('errors:password[1]') && e('密码强度小于系统设定。'); // 密码安全属性设为强，密码包含大小写字母和数字，密码强度小于系统设定，有错误提示。
r($result) && p('errors:password[2]') && e('~~');                     // 密码安全属性设为强，密码包含大小写字母、数字和特殊字符，密码强度符合系统设定，无错误提示。

/* 密码安全属性设为大于强的自定义数字。*/
$config->safe->mode = 3;
$result = $userTest->checkBeforeBatchCreateTest($users2, $verifyPassword);
r($result) && p('errors:password[0]') && e('密码强度小于系统设定。'); // 密码安全属性设为大于强的自定义数字，密码只包含数字，密码强度小于系统设定，有错误提示。
r($result) && p('errors:password[1]') && e('密码强度小于系统设定。'); // 密码安全属性设为大于强的自定义数字，密码包含大小写字母和数字，密码强度小于系统设定，有错误提示。
r($result) && p('errors:password[2]') && e('密码强度小于系统设定。'); // 密码安全属性设为大于强的自定义数字，密码包含大小写字母、数字和特殊字符，密码强度小于系统设定，有错误提示。

$config->safe->mode = 0; // 密码安全属性设为不检查，防止影响后续测试。

/* 未设置修改弱口令密码属性。*/
unset($config->safe->changeWeak);
$result = $userTest->checkBeforeBatchCreateTest($users3, $verifyPassword);
r($result) && p('result')             && e(1);    // 未设置修改弱口令密码属性，返回 false。
r($result) && p('errors:password[0]') && e('~~'); // 未设置修改弱口令密码属性，无错误提示。
r($result) && p('errors:password[1]') && e('~~'); // 未设置修改弱口令密码属性，无错误提示。

/* 修改弱口令密码属性设为不强制。*/
$config->safe->changeWeak = 0;
$result = $userTest->checkBeforeBatchCreateTest($users3, $verifyPassword);
r($result) && p('result')             && e(1);    // 修改弱口令密码属性设为不强制，返回 false。
r($result) && p('errors:password[0]') && e('~~'); // 修改弱口令密码属性设为不强制，无错误提示。
r($result) && p('errors:password[1]') && e('~~'); // 修改弱口令密码属性设为不强制，无错误提示。

/* 修改弱口令密码属性设为必须修改，使用系统内置常用弱口令检查。*/
$config->safe->changeWeak = 1;
$result = $userTest->checkBeforeBatchCreateTest($users3, $verifyPassword);
r($result) && p('result')                  && e(0);                                                                                                                   // 修改弱口令密码属性设为必须修改，使用系统内置常用弱口令检查，返回 false。
r($result) && p('errors:password[0]', '|') && e('密码不能使用【123456,password,12345,12345678,qwerty,123456789,1234,1234567,abc123,111111,123123】这些常用弱口令。'); // 修改弱口令密码属性设为必须修改，使用系统内置常用弱口令检查，有错误提示。
r($result) && p('errors:password[1]')      && e('~~');                                                                                                                // 修改弱口令密码属性设为必须修改，使用系统内置常用弱口令检查，无错误提示。

/* 修改弱口令密码属性设为必须修改，使用自定义常用弱口令检查。*/
$config->safe->changeWeak = 1;
zdTable('config')->config('config')->gen(1); // 生成自定义常用弱口令。
$tester->loadConfigFromDB();                 // 加载自定义常用弱口令。
$result = $userTest->checkBeforeBatchCreateTest($users3, $verifyPassword);
r($result) && p('result')                  && e(0);                                                                                                                       // 修改弱口令密码属性设为必须修改，使用自定义常用弱口令检查，返回 false。
r($result) && p('errors:password[0]')      && e('~~');                                                                                                                    // 修改弱口令密码属性设为必须修改，使用自定义常用弱口令检查，无错误提示。
r($result) && p('errors:password[1]', '|') && e('密码不能使用【password,12345,12345678,qwerty,123456789,1234,1234567,abc123,111111,123123,1234567890】这些常用弱口令。'); // 修改弱口令密码属性设为必须修改，使用自定义常用弱口令检查，有错误提示。

/* 密码安全属性设为强，修改弱口令密码属性设为必须修改，符合所有检查项的用户。*/
$config->safe->mode       = 2;
$config->safe->changeWeak = 1;
$result = $userTest->checkBeforeBatchCreateTest($users4, $verifyPassword);
r($result) && p('result')                && e(1);    // 检查通过，返回 true。
r($result) && p('errors:account[0]')     && e('~~'); // 用户名无错误提示。
r($result) && p('errors:realname[0]')    && e('~~'); // 姓名无错误提示。
r($result) && p('errors:visions[0][]')   && e('~~'); // 界面类型无错误提示。
r($result) && p('errors:password[0]')    && e('~~'); // 密码无错误提示。
r($result) && p('errors:email[0]')       && e('~~'); // 邮箱无错误提示。
r($result) && p('errors:phone[0]')       && e('~~'); // 电话无错误提示。
r($result) && p('errors:mobile[0]')      && e('~~'); // 手机无错误提示。
r($result) && p('errors:verifyPassword') && e('~~'); // 验证密码无错误提示。
