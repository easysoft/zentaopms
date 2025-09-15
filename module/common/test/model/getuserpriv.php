#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(5);

/**

title=测试 commonModel::getUserPriv();
timeout=0
cid=0

- 执行$result1 @0
- 执行$result2 @1
- 执行$result3 @1
- 执行$result4 @1
- 执行$result5 @0

*/

global $app;

// 1. 测试未登录用户权限检查
$originalUser = isset($app->user) ? $app->user : null;
unset($app->user);
$result1 = commonModel::getUserPriv('user', 'browse');

// 2. 测试超级管理员权限检查
$app->user = new stdClass();
$app->user->account = 'admin';
$app->user->admin = 'super';
$app->user->rights = array('rights' => array(), 'acls' => array());
$result2 = commonModel::getUserPriv('user', 'browse');

// 3. 测试访问开放方法权限检查
$app->user = new stdClass();
$app->user->account = 'test';
$app->user->admin = 'no';
$app->user->rights = array('rights' => array(), 'acls' => array());
$app->config->openMethods[] = 'user.browse';
$result3 = commonModel::getUserPriv('user', 'browse');

// 4. 测试普通用户有权限访问
$app->user = new stdClass();
$app->user->account = 'user1';
$app->user->admin = 'no';
$app->user->rights = array(
    'rights' => array('user' => array('browse' => 1)),
    'acls' => array()
);
$result4 = commonModel::getUserPriv('user', 'browse');

// 5. 测试普通用户无权限访问
$app->user = new stdClass();
$app->user->account = 'user2';
$app->user->admin = 'no';
$app->user->rights = array(
    'rights' => array(),
    'acls' => array()
);
$result5 = commonModel::getUserPriv('user', 'delete');

// 恢复原始用户状态
if($originalUser) $app->user = $originalUser;

r($result1) && p() && e('0');
r($result2) && p() && e('1');
r($result3) && p() && e('1');
r($result4) && p() && e('1');
r($result5) && p() && e('0');