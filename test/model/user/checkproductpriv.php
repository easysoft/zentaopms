#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
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
$product->id       = 1;
$product->name     = '测试产品';
$product->PO       = 'test2';
$product->openedBy = 'pm1';

$stakeholders['user10'] = 'user10';
$whiteList['user60']    = 'user60';

r($user->checkProductPrivTest('', 'admin'))       && p() && e('1'); //传入admin，判断admin用户是否有权限
r($user->checkProductPrivTest($product, 'test3')) && p() && e('0'); //传入产品、用户名，判断test3用户是否对此产品有权限
r($user->checkProductPrivTest($product, 'test2')) && p() && e('1'); //传入产品、用户名，判断test2用户是否对此产品有权限
r($user->checkProductPrivTest($product, 'user10', array(), array(), $stakeholders)) && p() && e('1'); //传入产品、用户名、干系人、白名单，判断user10用户是否对此产品有权限
r($user->checkProductPrivTest($product, 'user60', array(), array(), $stakeholders, array(), $whiteList)) && p() && e('0'); //传入产品、用户名、干系人、白名单，判断user60用户是否对此产品有权限
