#!use/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试获取用户列表;
cid=1
pid=1

获取用户列表，判断ID为1和2的真实姓名 >> admin,测试1

*/

global $token;
$list = $rest->get('/users?page=1&limit=2&order=id_asc', array('token' => $token->token));

r($list->body->users) && p('realname') && e('admin,测试1'); //获取用户列表，判断ID为1和2的真实姓名