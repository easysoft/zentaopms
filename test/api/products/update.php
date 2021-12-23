#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试修改产品信息;
cid=1
pid=1

调用成功，返回200 >> 200

*/
global $token;
$header = array('Token' => $token->token);

$pass = $rest->put('/products/1', array('name' => '测试正常产品1'), $header);
r($pass->status_code) && p() && e('200'); //调用成功，返回200