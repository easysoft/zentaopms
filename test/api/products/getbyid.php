#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试获取产品详情;
cid=1
pid=1

调用成功，返回200 >> 200
期望code为code1 >> code1
ID为1000的产品不存在，返回401 >> 404

*/
global $token;
$header = array('Token' => $token->token);

$pass = $rest->get('/products/1', $header);
r($pass->status_code) && p()       && e('200'); //调用成功，返回200
r($pass->body)        && p('code') && e('code1'); //期望code为code1

$fail = $rest->get('/products/1000', $header);
r($fail->status_code) && p() && e('404'); //ID为1000的产品不存在，返回401