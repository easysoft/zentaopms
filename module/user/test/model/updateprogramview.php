#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/user.class.php';
su('admin');

/**

title=测试 userModel->updateProgramView();
cid=1
pid=1



*/

$user = new userTest();

$programIdList = array('1', '2');

//r()      && p()  && e('');      //
