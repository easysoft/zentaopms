#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试API 删除bug
cid=1
pid=1

使用正确的bugID删除bug >> {"message":"success"}
使用错误的bugID删除bug >> {"message":"success"}
查询bug是否被成功删除 >> 10,1

*/

global $token;
$header = array('token' => $token->token);

$deleteExistBug    = $rest->delete('/bugs/10'  , $header);
$deleteNotExistBug = $rest->delete('/bugs/201', $header);

r($deleteExistBug)    && c(200) && p() && e('{"message":"success"}'); // 使用正确的bugID删除bug
r($deleteNotExistBug) && c(200) && p() && e('{"message":"success"}'); // 使用错误的bugID删除bug

$existBug = $rest->get('/bugs/10', $header);
$existBug->body = array($existBug->body);

r($existBug) && c(200) && p('id,deleted') && e('10,1'); // 查询bug是否被成功删除