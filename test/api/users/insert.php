#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试获取根据ID获取用户真实姓名;
cid=1
pid=1

插入一个新用户,获取邮箱 >> sgm0422@163.com

*/
global $token;
$insert = array(
    'account'  => 'sgm',
    'password' => 'qaz19950422',
    'realname' => '孙广明',
    'email'    => 'sgm0422@163.com',
);
$user = $rest->post('/users', $insert, array('token' => $token->token));

r($user) && c(201) && p('email') && e('sgm0422@163.com'); // 插入一个新用户,获取邮箱