#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试获取根据ID获取用户真实姓名;
cid=1
pid=1

编辑ID为3的用户,获取真实姓名 >> 李铁柱

*/
global $token;
$user = $rest->put('/users/3', array('realname' => '李铁柱'), array('token' => $token->token));

r($user) && c(200) && p('realname') && e('李铁柱'); // 编辑ID为3的用户,获取真实姓名