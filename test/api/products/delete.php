#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试删除产品;
cid=1
pid=1

调用成功，返回200 >> 200
调用查找接口，成功返回200 >> 200
已删除的产品的deleted字段为1 >> 1

*/
global $token;
$header = array('Token' => $token->token);

$pass = $rest->delete('/products/190', $header);
r($pass->status_code) && p('') && e('200'); //调用成功，返回200

$findProduct = $rest->get('/products/190', $header);
r($findProduct->status_code) && p()          && e('200'); //调用查找接口，成功返回200
r($findProduct->body)        && p('deleted') && e('1');   //已删除的产品的deleted字段为1