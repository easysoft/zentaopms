#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/compile.class.php';
su('admin');

/**

title=测试 compileModel->exec();
cid=1
pid=1

*/

$compile = new compileTest();

r($compile->execTest()) && p() && e();