#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试API 用户获取token;
cid=1
pid=1

使用正确用户名和密码获取token >> `[A-Za-z0-9]+`
使用正确用户名和密码获取token >> 登录失败，请检查您的用户名或密码是否填写正确。

*/

$pass = $rest->post('/tokens', array('account' => 'admin', 'password' => '123qwe!@#'));
$fail = $rest->post('/tokens', array('account' => 'admin', 'password' => '123'));

r($pass) && c(201) && p('token') && e('`[A-Za-z0-9]+`'); // 使用正确用户名和密码获取token
r($fail) && c(400) && p('error') && e('登录失败，请检查您的用户名或密码是否填写正确。'); // 使用正确用户名和密码获取token