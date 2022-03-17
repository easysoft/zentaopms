#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel::getContactListByIDTest();
cid=1
pid=1

*/

$user = new userTest();

r($user->getContactListByIDTest(1))     && p() && e('0'); //
r($user->getContactListByIDTest(2))     && p() && e('0'); //
r($user->getContactListByIDTest(1000))  && p() && e('0'); //
r($user->getContactListByIDTest(false)) && p() && e('0'); //
r($user->getContactListByIDTest(null))  && p() && e('0'); //
