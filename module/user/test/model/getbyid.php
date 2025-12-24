#!/usr/bin/env php
<?php

/**

title=测试 userModel::getById();
cid=19604

- $userID 参数为 0 ，通过默认字段获取，用户不存在。属性account @0
- $userID 参数为 1 ，通过默认字段获取，用户不存在。属性account @0
- $userID 参数为 0 ，通过 id 字段获取，用户不存在。属性account @0
- $userID 参数为 1 ，通过 id 字段获取，用户存在。属性account @admin
- $userID 参数为 2 ，通过 id 字段获取，用户不存在。属性account @0
- $userID 参数为空字符串 ，通过默认字段获取，用户不存在。属性account @0
- $userID 参数为 admin ，通过默认字段获取，用户存在。属性account @admin
- $userID 参数为 admin ，通过 account 字段获取，用户存在。属性account @admin
- $userID 参数为 user1 ，通过 account 字段获取，用户不存在。属性account @0
- $userID 参数为 admin ，通过 account 字段获取，用户存在。 @1
- $userID 参数为 0，通过默认字段获取，用户不存在。当前用户未登录，返回游客信息。属性account @guest

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

$now = time();

$table = zenData('user');
$table->last->range($now);
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
// last字段现在是datetime类型，直接比较datetime字符串
$expectedLast = date(DT_DATETIME1, $now);
r(substr($user->last, 0, 19) == substr($expectedLast, 0, 19)) && p() && e(1); // $userID 参数为 admin ，通过 account 字段获取，用户存在。

global $app;
$app->user = new stdclass();
$app->user->account = 'guest';

r($userTest->getByIDTest(0)) && p('account') && e('guest'); // $userID 参数为 0，通过默认字段获取，用户不存在。当前用户未登录，返回游客信息。
