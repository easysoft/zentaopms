#!/usr/bin/env php
<?php
/**
title=测试 userModel::getById();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$table = zdTable('user');
$table->last->range(time());
$table->gen(1);

su('admin');

$userTest = new userTest();

r($userTest->getByIDTest(0))                  && p('account') && e(0);       // $userID 参数为 0 ，通过默认字段获取，用户不存在。
r($userTest->getByIDTest(1))                  && p('account') && e(0);       // $userID 参数为 1 ，通过默认字段获取，用户不存在。
r($userTest->getByIDTest(0, 'id'))            && p('account') && e(0);       // $userID 参数为 0 ，通过 id 字段获取，用户不存在。
r($userTest->getByIDTest(1, 'id'))            && p('account') && e('admin'); // $userID 参数为 1 ，通过 id 字段获取，用户存在。
r($userTest->getByIDTest(2, 'id'))            && p('account') && e(0);       // $userID 参数为 2 ，通过 id 字段获取，用户不存在。
r($userTest->getByIDTest(''))                 && p('account') && e(0);       // $userID 参数为空字符串 ，通过默认字段获取，用户不存在。
r($userTest->getByIDTest('admin'))            && p('account') && e('admin'); // $userID 参数为 admin ，通过默认字段获取，用户存在。
r($userTest->getByIDTest('admin', 'account')) && p('account') && e('admin'); // $userID 参数为 admin ，通过 account 字段获取，用户存在。
r($userTest->getByIDTest('user1', 'account')) && p('account') && e(0);       // $userID 参数为 user1 ，通过 account 字段获取，用户不存在。

$user = $userTest->getByIDTest('admin', 'account');
r(substr($user->last, 0, 16) == substr(helper::now(), 0, 16)) && p() && e(1); // $userID 参数为 admin ，通过 account 字段获取，用户存在。

global $app;
$app->user = new stdclass();
$app->user->account = 'guest';

r($userTest->getByIDTest(0)) && p('account') && e('guest'); // $userID 参数为 0，通过默认字段获取，用户不存在。当前用户未登录，返回游客信息。
