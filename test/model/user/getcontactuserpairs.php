#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=userModel->getContactUserPairsTest();
cid=1
pid=1

*/
$user = new userTest();
$accountList = array('admin', 'test2');

r($user->getContactUserPairsTest($accountList)) && p('') && e(''); //
