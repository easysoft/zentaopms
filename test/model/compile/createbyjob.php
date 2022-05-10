#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/compile.class.php';
su('admin');

/**

title=测试 compileModel->createByJob();
cid=1
pid=1

*/

$compile = new compileTest();

r($compile->createByJobTest('1', '123')) && p('0:name') && e('这是一个Job1'); //检查是否可以拿到通过id为2的job数据创建的compile。
system("../../ztest init");
