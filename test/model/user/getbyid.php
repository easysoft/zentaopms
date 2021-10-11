#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 userModel::getById();
cid=1
pid=1

通过id获取存在的用户 >> account1
使用account字段获取存在的用户 >> account1
通过默认字段获取存在的用户 >> account1

*/
$user = $tester->loadModel('user');

r($user->getById(1, 'id'))               && p('account') && e('account1'); // 通过id获取存在的用户
r($user->getByID('account1', 'account')) && p('account') && e('account1'); // 使用account字段获取存在的用户
r($user->getByID('account1'))            && p('account') && e('account1'); // 通过默认字段获取存在的用户

/*
r($user->getByID(1))                     && p('account') && e('');         // 通过默认字段获取不存在的用户
r($user->getByID(100000, 'id'))          && p('account') && e('');         // 通过id字段获取不存在的用户
r($user->getByID('error', 'account'))    && p('account') && e('');         // 通过默认字段获取不存在的用户
 */
