#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
$db->switchDB();
su('admin');

/**

title=测试 userModel->updateProgramView();
cid=1
pid=1



*/

$user = new userTest();

$programIdList = array('1', '2');

//r()      && p()  && e('');      //
$db->restoreDB();