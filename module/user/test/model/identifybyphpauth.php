#!/usr/bin/env php
<?php
/**
title=测试 userModel->identifyByPhpAuth();
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

global $app;

$userTest = new userTest();
$random   = updateSessionRandom();
$password = md5(md5(123456) . $random);
$admin    = $userTest->getByIdTest('admin');
$user1    = $userTest->getByIdTest('user1');

/**
 * 检测验证空用户。
 */
$tester->server->php_auth_user = '';
$tester->server->php_auth_pw   = $password;
r($userTest->identifyByPhpAuthTest()) && p() && e(0); // 空用户验证失败，返回 false。

/**
 * 检测验证 guest 用户。
 */
$tester->server->php_auth_user = 'guest';
$tester->server->php_auth_pw   = $password;
r($userTest->identifyByPhpAuthTest()) && p() && e(0); // guest 用户验证失败，返回 false。

/**
 * 检测验证不存在的用户。
 */
$tester->server->php_auth_user = 'user2';
$tester->server->php_auth_pw   = $password;
r($userTest->identifyByPhpAuthTest()) && p() && e(0); // user2 用户验证失败，返回 false。

/**
 * 检测验证 admin 用户。
 */
$tester->server->php_auth_user = $admin->account;
$tester->server->php_auth_pw   = md5($admin->password . $random);
r($userTest->identifyByPhpAuthTest()) && p()          && e(1);       // admin 用户验证成功，返回 true。
r($app->user)                         && p('account') && e('admin'); // admin 用户验证成功，$app 对象的中的用户是 admin。
r($tester->session->user)             && p('account') && e('admin'); // admin 用户验证成功，session 中的用户是 admin。

/* 检测 admin 用户权限。*/
r(count($app->user->rights['rights']))                      && p() && e(8); // admin 用户具有 6 个模块的权限。
r(isset($app->user->rights['rights']['index']['index']))    && p() && e(1); // admin 用户具有 index 模块的 index 动作的权限。
r(isset($app->user->rights['rights']['my']['index']))       && p() && e(1); // admin 用户具有 my 模块的 index 动作的权限。
r(isset($app->user->rights['rights']['company']['browse'])) && p() && e(1); // admin 用户具有 company 模块的 all 动作的权限。
r(isset($app->user->rights['rights']['dept']['browse']))    && p() && e(1); // admin 用户具有 dept 模块的 browse 动作的权限。
r(isset($app->user->rights['rights']['group']['browse']))   && p() && e(1); // admin 用户具有 group 模块的 browse 动作的权限。
r(isset($app->user->rights['rights']['program']['browse'])) && p() && e(1); // admin 用户具有 program 模块的 browse 动作的权限。
r(isset($app->user->rights['rights']['product']['browse'])) && p() && e(1); // admin 用户具有 product 模块的 browse 动作的权限。
r(isset($app->user->rights['rights']['execution']['all']))  && p() && e(1); // admin 用户具有 execution 模块的 browse 动作的权限。

/* 检测 admin 用户权限组。*/
r(count($app->user->groups)) && p()      && e(2);     // admin 用户具有 2 个权限组。
r($app->user->groups)        && p('1,2') && e('1,2'); // admin 用户具有的权限组 id 分别是 1 和 2。

/* 检测 admin 用户视图功能在 computeUserView 和 grantUserView 方法的单元测试中实现。*/

/* 检测是否记录 admin 登录日志。*/
$action = $tester->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(1)->fetch();
r($action) && p('objectType,objectID,action') && e('user,1,login'); // 记录日志，最后一条日志对象类型是 user，对象 ID 是 1，动作是 login。

/**
 * 检测验证 user1 用户。
 */
$tester->server->php_auth_user = $user1->account;
$tester->server->php_auth_pw   = md5($user1->password . $random);
r($userTest->identifyByPhpAuthTest()) && p()          && e(1);       // user1 用户验证成功，返回 true。
r($app->user)                         && p('account') && e('user1'); // user1 用户验证成功，$app 对象的中的用户是 user1。
r($tester->session->user)             && p('account') && e('user1'); // user1 用户验证成功，session 中的用户是 user1。

/* 检测 user1 用户权限。*/
r(count($app->user->rights['rights']))                       && p() && e(8); // user1 用户具有 2 个模块的权限。
r(isset($app->user->rights['rights']['index']['index']))     && p() && e(1); // user1 用户具有 index 模块的 index 动作的权限。
r(isset($app->user->rights['rights']['my']['index']))        && p() && e(1); // user1 用户具有 my 模块的 index 动作的权限。
r(isset($app->user->rights['rights']['program']['browse']))  && p() && e(1); // user1 用户具有 program 模块的 browse 动作的权限。
r(isset($app->user->rights['rights']['product']['browse']))  && p() && e(1); // user1 用户具有 product 模块的 browse 动作的权限。
r(isset($app->user->rights['rights']['execution']['all']))   && p() && e(1); // user1 用户具有 execution 模块的 all 动作的权限。
r(isset($app->user->rights['rights']['qa']['index']))        && p() && e(1); // user1 用户具有 qa 模块的 index 动作的权限。
r(isset($app->user->rights['rights']['bug']['browse']))      && p() && e(1); // user1 用户具有 bug 模块的 browse 动作的权限。
r(isset($app->user->rights['rights']['testcase']['browse'])) && p() && e(1); // user1 用户具有 testcase 模块的 browse 动作的权限。

/* 检测 user1 用户权限组。*/
r(count($app->user->groups)) && p()      && e(2);     // user1 用户具有 2 个权限组。
r($app->user->groups)        && p('2,3') && e('2,3'); // user1 用户具有的权限组 id 分别是 2 和 3。

/* 检测 user1 用户视图功能在 computeUserView 和 grantUserView 方法的单元测试中实现。*/

/* 检测是否记录 user1 登录日志。*/
$action = $tester->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(1)->fetch();
r($action) && p('objectType,objectID,action') && e('user,2,login'); // 记录日志，最后一条日志对象类型是 user，对象 ID 是 2，动作是 login。

/* 检测 user1 用户使用错误密码验证。*/
$tester->server->php_auth_user = $user1->account;
$tester->server->php_auth_pw   = md5($user1->password);
r($userTest->identifyByPhpAuthTest()) && p() && e(0); // user1 用户使用错误密码验证失败，返回 false。

/* 检测 user1 用户使用空密码验证。*/
$tester->server->php_auth_user = $user1->account;
$tester->server->php_auth_pw   = '';
r($userTest->identifyByPhpAuthTest()) && p() && e(0); // user1 用户使用空密码验证失败，返回 false。
