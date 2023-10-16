#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';
zdTable('user')->gen(10);
su('admin');

/**

title=测试 userModel::getById();
cid=1
pid=1

通过id获取存在的用户 >> admin
使用account字段获取存在的用户 >> admin
通过默认字段获取存在的用户 >> admin

*/
$user = new userTest();

r($user->getByIDTest(1, 'id'))               && p('account') && e('admin'); // 通过id获取存在的用户
r($user->getByIDTest('admin', 'account'))    && p('account') && e('admin'); // 使用account字段获取存在的用户
r($user->getByIDTest('admin'))               && p('account') && e('admin'); // 通过默认字段获取存在的用户
r($user->getByIDTest(1))                     && p('account') && e('0');     // 通过默认字段获取不存在的用户
r($user->getByIDTest(100000, 'id'))          && p('account') && e('0');     // 通过id字段获取不存在的用户
r($user->getByIDTest('error', 'account'))    && p('account') && e('0');     // 通过默认字段获取不存在的用户
