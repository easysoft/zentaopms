#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

su('admin');

/**

title=测试 userModel::getById();
cid=1
pid=1

通过id获取存在的用户 >> admin
使用account字段获取存在的用户 >> admin
通过默认字段获取存在的用户 >> admin

*/
$user = $tester->loadModel('user');

r($user->getById(1, 'id'))               && p('account') && e('admin'); // 通过id获取存在的用户
r($user->getByID('admin', 'account'))    && p('account') && e('admin'); // 使用account字段获取存在的用户
r($user->getByID('admin'))               && p('account') && e('admin'); // 通过默认字段获取存在的用户
r($user->getByID(1))                     && p('account') && e('');      // 通过默认字段获取不存在的用户
r($user->getByID(100000, 'id'))          && p('account') && e('');      // 通过id字段获取不存在的用户
r($user->getByID('error', 'account'))    && p('account') && e('');      // 通过默认字段获取不存在的用户
