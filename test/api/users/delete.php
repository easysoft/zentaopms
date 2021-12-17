#!use/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=删除用户;
cid=1
pid=1

删除ID为10的用户，判断返回信息 >> message:success

*/

global $token;
$result = $rest->delete('/users/10', array('token' => $token->token));

r($result) && c(200) && p() && e("message:success") ; //删除ID为10的用户，判断返回信息