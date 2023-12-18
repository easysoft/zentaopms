#!/usr/bin/env php
<?php
/**
title=测试 userModel->getWeakUsers();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$table = zdTable('user');
$table->account->range('1-12')->prefix('user');
$table->password->range('123456,' . md5(123456) . ',user3,' . md5('user4') . ',86893032,' . md5(86893032) . ',13888888888,' . md5(13888888888) . ',`2017-01-01`,' . md5('2017-01-01') . ',Admin123@,123456');
$table->phone->range('86893032{12}');
$table->mobile->range('13888888888{12}');
$table->birthday->range('`2017-01-01`{12}');
$table->deleted->range('0{11},1');
$table->gen(12);

su('admin');

global $app;
unset($config->safe->weak); // 防止数据库中的配置影响测试结果。
$app->loadConfig('admin');  // 加载系统内置的弱密码配置。

$userTest = new userTest();
$users    = $userTest->getWeakUsersTest();

r(count($users))    && p() && e(10); // 检测出 10 个弱密码用户。
r(isset($user[10])) && p() && e(0);  // user11 密码为强密码，未检测出。
r(isset($user[11])) && p() && e(0);  // user12 密码为弱密码但是账号已删除，未检测出。

r($users[0]) && p('account,weakReason') && e('user1,weak');      // user1 密码为弱口令，弱密码原因为常用弱口令。
r($users[1]) && p('account,weakReason') && e('user2,weak');      // user2 密码为加密后的弱口令，弱密码原因为常用弱口令。
r($users[2]) && p('account,weakReason') && e('user3,account');   // user3 密码为账号，弱密码原因为与账号相同。
r($users[3]) && p('account,weakReason') && e('user4,account');   // user4 密码为加密后的账号，弱密码原因为与账号相同。
r($users[4]) && p('account,weakReason') && e('user5,phone');     // user5 密码为电话号码，弱密码原因为与电话号码相同
r($users[5]) && p('account,weakReason') && e('user6,phone');     // user6 密码为加密后的电话号码，弱密码原因为与电话号码相同
r($users[6]) && p('account,weakReason') && e('user7,mobile');    // user7 密码为手机号，弱密码原因为与手机号相同。
r($users[7]) && p('account,weakReason') && e('user8,mobile');    // user8 密码为加密后的手机号，弱密码原因为与手机号相同。
r($users[8]) && p('account,weakReason') && e('user9,birthday');  // user9 密码为生日，弱密码原因为与生日相同。
r($users[9]) && p('account,weakReason') && e('user10,birthday'); // user10 密码为加密后的生日，弱密码原因为与生日相同。
