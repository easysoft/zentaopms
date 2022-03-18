#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->updateProgramView();
cid=1
pid=1

*/

$user = new userTest();

$programIdList = array('1', '2');
a($user->grantUserViewTest('user10'));
a($user->updateProgramViewTest('', array('user10')));
die;

r()      && p()  && e('');      //
system("./ztest init");
