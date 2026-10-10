#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::hasCancelPriv();
timeout=0
cid=0

- 超级管理员是否有取消执行权限 >> 有权限 @1
- 会话用户拥有pipeline.cancel权限 >> 有权限 @1
- 会话用户没有pipeline.cancel权限 >> 无权限 @0
- 会话用户有权限但当前用户被空间权限覆盖为无权限 >> 仍按会话用户判断 @1
- 会话用户无权限但当前用户被空间权限覆盖为有权限 >> 仍按会话用户判断 @0
- 判断权限后应用的当前用户被还原 >> 未被修改 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/* zendata 生成数据时会往标准输出打印生成过程，先丢弃掉，避免污染用例的输出比对。 */
ob_start();
zenData('user')->gen(5);
ob_end_clean();

su('admin');
global $app;
$tester = new pipelineModelTest();

/* 构造三种会话用户：管理员、有取消权限的普通用户、无取消权限的普通用户。 */
$adminUser = clone $app->user;
$adminUser->account = 'admin';
$adminUser->admin   = true;

$grantedUser = clone $app->user;
$grantedUser->account = 'user1';
$grantedUser->admin   = false;
$grantedUser->rights  = array('rights' => array('pipeline' => array('cancel' => true)), 'acls' => array());

$deniedUser = clone $app->user;
$deniedUser->account = 'user2';
$deniedUser->admin   = false;
$deniedUser->rights  = array('rights' => array('pipeline' => array('browse' => true)), 'acls' => array());

/* 模拟被空间权限重新赋值之后的当前用户，与会话用户不是同一个对象。 */
$spaceGrantedUser = clone $grantedUser;
$spaceDeniedUser  = clone $deniedUser;

r($tester->hasCancelPrivTest($adminUser, $adminUser) ? '1' : '0') && p() && e('1');
r($tester->hasCancelPrivTest($grantedUser, $grantedUser) ? '1' : '0') && p() && e('1');
r($tester->hasCancelPrivTest($deniedUser, $deniedUser) ? '1' : '0') && p() && e('0');
r($tester->hasCancelPrivTest($grantedUser, $spaceDeniedUser) ? '1' : '0') && p() && e('1');
r($tester->hasCancelPrivTest($deniedUser, $spaceGrantedUser) ? '1' : '0') && p() && e('0');

/* 模型内部会临时把app->user换成会话用户，调用结束后必须还原为原来的对象。 */
$app->session->set('user', $grantedUser);
$app->user = $spaceDeniedUser;
common::$userPrivs = array();

$tester->instance->hasCancelPriv();
r($app->user === $spaceDeniedUser ? '1' : '0') && p() && e('1');
