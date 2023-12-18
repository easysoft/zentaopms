#!/usr/bin/env php
<?php

/**

title=测试 mailModel->isError();
timeout=0
cid=0

- 没有错误信息，检查结果 @0
- 有错误信息，检查结果 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$mailModel = $tester->loadModel('mail');
r($mailModel->isError()) && p() && e('0'); //没有错误信息，检查结果

$mailModel->errors[] = 'file_open';
r($mailModel->isError()) && p() && e('1'); //有错误信息，检查结果