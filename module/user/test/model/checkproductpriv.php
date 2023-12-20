#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('product')->gen(10);
zdTable('user')->gen(200);
su('admin');

/**

title=测试 userModel->checkProductPriv();
cid=1
pid=1

传入admin，判断admin用户是否有权限 >> 1
传入产品、用户名，判断test3用户是否对此产品有权限 >> 0
传入产品、用户名，判断test2用户是否对此产品有权限 >> 1
传入产品、用户名、干系人、白名单，判断user10用户是否对此产品有权限 >> 1
传入产品、用户名、干系人、白名单，判断user60用户是否对此产品有权限 >> 0

*/

$user = new userTest();
$product = new stdclass();
$product->id        = 1;
$product->name      = '测试产品';
$product->PO        = 'test2';
$product->PM        = '';
$product->QD        = '';
$product->RD        = '';
$product->createdBy = 'pm1';
$product->acl       = 'private';

$stakeholders['user10'] = 'user10';
$whiteList['user60']    = 'user60';
$admins['test6']        = 'test6';

$user->objectModel->app->company->admins = ',admin,';

r($user->checkProductPrivTest(new stdclass(), 'admin'))       && p() && e('1'); //传入admin，判断admin用户是否有权限
r($user->checkProductPrivTest($product, 'test3')) && p() && e('0'); //传入产品、用户名，判断test3用户是否对此产品有权限
r($user->checkProductPrivTest($product, 'test2')) && p() && e('1'); //传入产品、用户名，判断test2用户是否对此产品有权限
r($user->checkProductPrivTest($product, 'user10', array(), $stakeholders)) && p() && e('1'); //传入产品、用户名、干系人、白名单，判断user10用户是否对此产品有权限
r($user->checkProductPrivTest($product, 'user60', array(), $stakeholders, $whiteList)) && p() && e('1'); //传入产品、用户名、干系人、白名单，判断user60用户是否对此产品有权限
r($user->checkProductPrivTest($product, 'test6',  array(), $stakeholders, $whiteList, $admins)) && p() && e('1'); //传入产品、用户名、干系人、白名单, admins，判断test6用户是否对此产品有权限

$product->acl = 'open';
r($user->checkProductPrivTest($product, 'test8')) && p() && e('1'); //传入公开产品，判断test6用户是否对此产品有权限
