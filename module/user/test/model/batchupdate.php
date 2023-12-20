#!/usr/bin/env php
<?php
/**
title=测试 userModel->batchUpdate();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->gen(2);
zdTable('action')->gen(0);
zdTable('history')->gen(0);

su('admin');

global $app;

$userTest = new userTest();

$random = updateSessionRandom();
$verify = md5($app->user->password . $random);

$users1 = array
(
    2 => (object)array('account' => 'user1', 'realname' => 'user11', 'visions' => 'rnd', 'gender' => 'gender')
);

$users2 = array
(
    2 => (object)array('account' => 'user1', 'realname' => 'user11', 'visions' => 'rnd')
);

$users3 = array
(
    1 => (object)array('account' => 'admin', 'realname' => 'newAdmin', 'visions' => 'rnd', 'role' => 'newRole')
);

/**
 * 测试传入空数组的情况。
 */
$result = $userTest->batchUpdateTest(array(), $verify);
r($result) && p('result') && e(0); // 传入空数组，返回 false。

/**
 * 测试更新用户失败情况。
 */
$result = $userTest->batchUpdateTest($users1, $verify);
r($result) && p('result')        && e(0);                                        // 更新用户失败，返回 false。
r($result) && p('errors:gender') && e('『性别』不符合格式，应当为:『/f|m/』。'); // 更新用户失败，提示错误信息。

/**
 * 测试更新用户成功的情况。
 */
$result = $userTest->batchUpdateTest($users2, $verify);
r($result) && p('result') && e(1); // 更新用户成功，返回 true。

$action = $tester->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(1)->fetch();
r($action) && p('objectType,objectID,action') && e('user,2,edited'); // 创建日志成功，最后一条记录的对象类型是 user，对象 id 是 2，动作是 edited。

$histories = $tester->dao->select('*')->from(TABLE_HISTORY)->where('action')->eq($action->id)->fetchAll();
r(count($histories)) && p()                       && e(2);                         // 创建历史记录成功，最后一条日志的历史记录是 2 条。
r($histories)        && p('0:field,old,new')      && e('realname,用户1,user11');   // 创建历史记录成功，第 1 条历史记录的字段是 account，旧值是 user1，新值是 user11。
r($histories)        && p('1:field|old|new', '|') && e('visions|rnd,lite,or|rnd'); // 创建历史记录成功，第 2 条历史记录的字段是 visions，旧值是 rnd,lite,or，新值是 rnd。

/**
 * 测试更新当前登录用户。
 */
r($app->user) && p('account,realname,role') && e('admin,admin,qa'); // 当前登录用户的用户名是 admin，真实姓名是 admin，角色是 qa。

$result = $userTest->batchUpdateTest($users3, $verify);
r($result)    && p('result')                && e(1);                        // 更新当前登录用户成功，返回 true。
r($app->user) && p('account,realname,role') && e('admin,newAdmin,newRole'); // 当前登录用户的用户名是 admin，真实姓名是 newAdmin，角色是 newRole。

/**
 * 检测事务提交功能。
 */
$user = $userTest->getByIdTest('user1');
r($user) && p('id,realname,visions') && e('2,user11,rnd'); // 事务提交成功，能查询到修改的用户。
