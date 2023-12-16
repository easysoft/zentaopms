#!/usr/bin/env php
<?php

/**

title=测试 mailModel->getError();
cid=0

- 获取错误信息属性mail @用户邮箱不存在。
- 获取读取后的错误信息。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$mailModel = $tester->loadModel('mail');
$mailModel->errors = array('mail' => '用户邮箱不存在。');

r($mailModel->getError()) && p('mail') && e('用户邮箱不存在。'); //获取错误信息
r($mailModel->getError()) && p()       && e('0');              //获取读取后的错误信息。
