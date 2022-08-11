#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->getPairs();
cid=1
pid=1

获取admin的邮箱 >> 833482@qq.com
从accounts中获取到的用户邮箱数量 >> 2

*/

$accounts = array('tesrasd1asd#@!#$', 'ASD123中文', 'user10', 'ccsdqq@!', 'admin');
$user     = new userTest();
$emails   = $user->getRealNameAndEmailsTest($accounts);

r($emails['admin'])  && p('email') && e('833482@qq.com'); //获取admin的邮箱
r(count($emails))    && p()        && e('2');             //从accounts中获取到的用户邮箱数量