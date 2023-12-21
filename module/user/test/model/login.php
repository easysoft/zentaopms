#!/usr/bin/env php
<?php
/**
title=测试 userModel->login();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->gen(2);
zdTable('company')->gen(1);
zdTable('group')->gen(3);

$userGroupTable = zdTable('usergroup');
$userGroupTable->account->range('admin{2},user1{2}');
$userGroupTable->group->range('1,2{2},3');
$userGroupTable->gen(4);

$groupPrivTable = zdTable('grouppriv');
$groupPrivTable->group->range('1-3{3}');
$groupPrivTable->module->range('company,dept,group,program,product,execution,qa,bug,testcase');
$groupPrivTable->method->range('browse{5},all,index,browse{2}');
$groupPrivTable->gen(9);

su('admin');

$userTest = new userTest();

/* session 中记录登录失败次数和用户锁定时间以供检测清除用户锁定状态功能使用。*/
$tester->session->set('loginFails', 5);
$tester->session->set('admin.loginLocked', '2023-01-10 14:34:12');
$tester->session->set('user1.loginLocked', '2023-01-10 14:34:12');

/* 保持登录状态的 cookie 置空以供检测保持登录状态功能使用。*/
$tester->cookie->set('keepLogin', 'off');
$tester->cookie->set('za', '');
$tester->cookie->set('zp', '');

$user1 = (object)array('account' => '');
$user2 = $userTest->getByIdTest('admin');
$user3 = $userTest->getByIdTest('user1');

r($userTest->loginTest($user1)) && p() && e(0); // 用户名为空，返回 false。

/**
 * 检测 admin 用户登录后的信息。
 */
$result = $userTest->loginTest($user2);                          // 记录日志，不勾选保持登录状态。
r($result)                && p('account,admin') && e('admin,1'); // 登录成功，返回的用户是 admin，是超级管理员。
r($app->user)             && p('account,admin') && e('admin,1'); // 登录成功，$app 对象的中的用户是 admin，是超级管理员。
r($tester->session->user) && p('account,admin') && e('admin,1'); // 登录成功，session 中的用户是 admin，是超级管理员。

/* 检测 admin 用户锁定状态。*/
$user2 = $userTest->getByIdTest($user2->account);
r($user2)                                  && p('account,fails,locked') && e('admin,0,~~'); // 数据库中记录的 admin 用户登录失败次数为 0，锁定时间为空。
r($tester->session->loginFails)            && p()                       && e(0);            // session 中未记录登录失败次数。
r($tester->session->{'admin.loginLocked'}) && p()                       && e(0);            // session 中未记录 admin 用户锁定时间。

/* 检测 admin 用户权限。*/
r(count($result->rights['rights']))                      && p() && e(8); // admin 用户具有 6 个模块的权限。
r(isset($result->rights['rights']['index']['index']))    && p() && e(1); // admin 用户具有 index 模块的 index 动作的权限。
r(isset($result->rights['rights']['my']['index']))       && p() && e(1); // admin 用户具有 my 模块的 index 动作的权限。
r(isset($result->rights['rights']['company']['browse'])) && p() && e(1); // admin 用户具有 company 模块的 all 动作的权限。
r(isset($result->rights['rights']['dept']['browse']))    && p() && e(1); // admin 用户具有 dept 模块的 browse 动作的权限。
r(isset($result->rights['rights']['group']['browse']))   && p() && e(1); // admin 用户具有 group 模块的 browse 动作的权限。
r(isset($result->rights['rights']['program']['browse'])) && p() && e(1); // admin 用户具有 program 模块的 browse 动作的权限。
r(isset($result->rights['rights']['product']['browse'])) && p() && e(1); // admin 用户具有 product 模块的 browse 动作的权限。
r(isset($result->rights['rights']['execution']['all']))  && p() && e(1); // admin 用户具有 execution 模块的 browse 动作的权限。

/* 检测 admin 用户权限组。*/
r(count($result->groups)) && p()      && e(2);     // admin 用户具有 2 个权限组。
r($result->groups)        && p('1,2') && e('1,2'); // admin 用户具有的权限组 id 分别是 1 和 2。

/* 检测 admin 用户视图功能在 computeUserView 和 grantUserView 方法的单元测试中实现。*/

/* 检测是否记录 admin 登录日志。*/
$action = $tester->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(1)->fetch();
r($action) && p('objectType,objectID,action') && e('user,1,login'); // 记录日志，最后一条日志对象类型是 user，对象 ID 是 1，动作是 login。

/* 检测是否保持 admin 登录状态。*/
r($tester->cookie->keepLogin) && p() && e('off'); // 未勾选保持登录状态，cookie 中保持登录状态的值是 off。
r($tester->cookie->za)        && p() && e('0');   // 未勾选保持登录状态，cookie 中 za 的值为空。
r($tester->cookie->zp)        && p() && e('0');   // 未勾选保持登录状态，cookie 中 zp 的值为空。

/**
 * 检测 user1 用户登录后的信息。
 */
$result = $userTest->loginTest($user3, false, true);         // 不记录日志，勾选保持登录状态。
r($result)           && p('account,admin') && e('user1,~~'); // 登录成功，返回的用户是 user1，不是超级管理员。
r($app->user)        && p('account,admin') && e('user1,~~'); // 登录成功，$app 对象的中的用户是 user1，不是超级管理员。
r($_SESSION['user']) && p('account,admin') && e('user1,~~'); // 登录成功，session 中的用户是 user1，不是超级管理员。

/* 检测 user1 用户锁定状态。*/
$user3 = $userTest->getByIdTest($user3->account);
r($user3)                                  && p('account,fails,locked') && e('user1,0,~~'); // 数据中记录的 user1 用户登录失败次数为 0，锁定时间为空。
r($tester->session->loginFails)            && p()                       && e(0);            // session 中未记录登录失败次数。
r($tester->session->{'user1.loginLocked'}) && p()                       && e(0);            // session 中未记录 user1 用户锁定时间。

/* 检测 user1 用户权限。*/
r(count($result->rights['rights']))                       && p() && e(8); // user1 用户具有 2 个模块的权限。
r(isset($result->rights['rights']['index']['index']))     && p() && e(1); // user1 用户具有 index 模块的 index 动作的权限。
r(isset($result->rights['rights']['my']['index']))        && p() && e(1); // user1 用户具有 my 模块的 index 动作的权限。
r(isset($result->rights['rights']['program']['browse']))  && p() && e(1); // user1 用户具有 program 模块的 browse 动作的权限。
r(isset($result->rights['rights']['product']['browse']))  && p() && e(1); // user1 用户具有 product 模块的 browse 动作的权限。
r(isset($result->rights['rights']['execution']['all']))   && p() && e(1); // user1 用户具有 execution 模块的 all 动作的权限。
r(isset($result->rights['rights']['qa']['index']))        && p() && e(1); // user1 用户具有 qa 模块的 index 动作的权限。
r(isset($result->rights['rights']['bug']['browse']))      && p() && e(1); // user1 用户具有 bug 模块的 browse 动作的权限。
r(isset($result->rights['rights']['testcase']['browse'])) && p() && e(1); // user1 用户具有 testcase 模块的 browse 动作的权限。

/* 检测 user1 用户权限组。*/
r(count($result->groups)) && p()      && e(2);     // user1 用户具有 2 个权限组。
r($result->groups)        && p('2,3') && e('2,3'); // user1 用户具有的权限组 id 分别是 2 和 3。

/* 检测 user1 用户视图功能在 computeUserView 和 grantUserView 方法的单元测试中实现。*/

/* 检测是否记录 user1 登录日志。*/
$action = $tester->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(1)->fetch();
r($action) && p('objectType,objectID,action') && e('user,1,login'); // 未记录日志，最后一条日志是 admin 用户的登录日志。

/* 检测是否保持 user1 登录状态。*/
$zp = sha1($user3->account . $user3->password . $_SERVER['REQUEST_TIME']);
r($tester->cookie->keepLogin) && p() && e('on');    // 勾选保持登录状态，cookie 中保持登录状态的值是 on。
r($tester->cookie->za)        && p() && e('user1'); // 勾选保持登录状态，cookie 中 za 的值是 user1。
r($tester->cookie->zp == $zp) && p() && e(1);       // 勾选保持登录状态，cookie 中 zp 的值是 user1 的密码加密后的值。
