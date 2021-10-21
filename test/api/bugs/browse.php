#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试API 获取bug列表
cid=1
pid=1

通过正确的productID获取bug列表的状态码 >> 200
通过错误的productID获取bug列表的状态码 >> 200
通过正确的productID获取bug列表的数量 >> 10
通过错误的productID获取bug列表的数量 >> 0

*/

global $token;
$header = array('token' => $token->token);

$existProductBugs    = $rest->get('/products/1/bugs?limit=10&id_desc', $header);
$notExistProductBugs = $rest->get('/products/10/bugs?limit=10&id_desc', $header);

r($existProductBugs->status_code) && p() && e('200'); // 通过正确的productID获取bug列表的状态码
r($notExistProductBugs->status_code) && p() && e('200'); //通过错误的productID获取bug列表的状态码

 
$existbugs = $existProductBugs->body->bugs;
$emptybugs = $notExistProductBugs->body->bugs;
r(count($existbugs)) && p() && e('10'); // 通过正确的productID获取bug列表的数量
r(count($emptybugs)) && p() && e('0'); // 通过错误的productID获取bug列表的数量 

$firstBug = $existProductBugs->body->bugs[0];
r($firstBug) && p('title') && ('bug80'); // 获取第一个bug的标题