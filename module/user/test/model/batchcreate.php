#!/usr/bin/env php
<?php
/**
title=测试 userModel->batchCreate();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->gen(1);
zdTable('company')->gen(1);
zdTable('usergroup')->gen(0);
zdTable('userview')->gen(0);
zdTable('action')->gen(0);

su('admin');

global $app;

$userTest = new userTest();

$random = updateSessionRandom();
$verify = md5($app->user->password . $random);

$users1 = array
(
    (object)array('account' => '', 'realname' => 'user1', 'visions' => 'rnd', 'password' => 'Admin123', 'type' => 'inside',  'new' => 0, 'newCompany' => 'newCompany', 'group' => array(1, 2)),
);

$users2 = array
(
    (object)array('account' => 'user1', 'realname' => 'user1', 'visions' => 'rnd', 'password' => 'Admin123', 'type' => 'outside', 'new' => 1, 'newCompany' => 'newCompany', 'group' => array(1, 2), 'gender' => 'gender'),
);

$users3 = array
(
    (object)array('account' => 'user1', 'realname' => 'user1', 'visions' => 'rnd', 'password' => 'Admin123', 'type' => 'outside', 'new' => 1, 'newCompany' => 'newCompany', 'group' => array(1, 2)),
);

/**
 * 测试传入空数组的情况。
 */
$result = $userTest->batchCreateTest(array(), $verify);
r($result) && p('result') && e(0); // 传入空数组，返回 false。

/**
 * 测试用户名为空的情况。
 */
$result = $userTest->batchCreateTest($users1, $verify);
r($result) && p('result') && e(0); // 用户名为空，返回 false。

/**
 * 测试创建外部公司成功，创建用户失败，数据回滚的情况。
 */
$companies = $tester->dao->select('*')->from(TABLE_COMPANY)->fetchAll();
r(count($companies)) && p()       && e(1); // 查看公司表只有 1 条记录。
r($companies)        && p('0:id') && e(1); // 公司表第 1 条记录的 id 是 1。

$result = $userTest->batchCreateTest($users2, $verify);
r($result) && p('result')        && e(0);                                        // 创建用户失败，返回 false。
r($result) && p('errors:gender') && e('『性别』不符合格式，应当为:『/f|m/』。'); // 创建用户失败，提示错误信息。

$companies = $tester->dao->select('*')->from(TABLE_COMPANY)->fetchAll();
r(count($companies)) && p()       && e(1); // 事务回滚成功，查看公司表只有 1 条记录。
r($companies)        && p('0:id') && e(1); // 公司表第 1 条记录的 id 是 1。

/**
 * 测试创建外部公司成功，创建用户成功的情况。
 */
$groups = $tester->dao->select('*')->from(TABLE_USERGROUP)->fetchAll();
r(count($groups)) && p() && e(0); // 查看用户权限组表没有记录。

$views = $tester->dao->select('*')->from(TABLE_USERVIEW)->fetchAll();
r(count($views)) && p()            && e(1);       // 查看用户视图表只有 1 条记录。
r($views)        && p('0:account') && e('admin'); // 用户视图表第 1 条记录的用户名是 admin。

$result = $userTest->batchCreateTest($users3, $verify);
r($result) && p('result') && e(2); // 创建用户成功，返回新用户 id。

$companies = $tester->dao->select('*')->from(TABLE_COMPANY)->fetchAll();
r(count($companies)) && p()            && e(2);              // 查看公司表有 2 条记录。
r($companies)        && p('0:id')      && e(1);              // 公司表第 1 条记录的 id 是 1。
r($companies)        && p('1:id,name') && e('3,newCompany'); // 公司表第 2 条记录的 id 是 3（上一条测试失败事务回滚，所以这里的 id 是 3 不是 2），名称是 newCompany。

$groups = $tester->dao->select('*')->from(TABLE_USERGROUP)->fetchAll();
r(count($groups)) && p()                  && e(2);         // 查看用户权限组表有 2 条记录。
r($groups)        && p('0:account,group') && e('user1,1'); // 第 1 条记录的用户名是 user1，权限组 id 是 1。
r($groups)        && p('1:account,group') && e('user1,2'); // 第 2 条记录的用户名是 user1，权限组 id 是 2。

$views = $tester->dao->select('*')->from(TABLE_USERVIEW)->fetchAll();
r(count($views)) && p()            && e(2);       // 查看用户视图表有 2 条记录。
r($views)        && p('0:account') && e('admin'); // 用户视图表第 1 条记录的用户名是 admin。
r($views)        && p('1:account') && e('user1'); // 用户视图表第 2 条记录的用户名是 user1。

$action = $tester->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(1)->fetch();
r($action) && p('objectType,objectID,action') && e('user,2,created'); // 查看日志表最后一条记录的对象类型是 user，对象 id 是 2，动作是 created。
