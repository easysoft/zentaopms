#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试获取根据ID获取用户真实姓名;
cid=1
pid=1

获取ID为2的真实姓名 >> 测试1

*/
global $token;
$user = $rest->get('/users/2', array('token' => $token->token));

r($user) && c(200) && p('realname') && e('测试1'); // 获取ID为2的真实姓名