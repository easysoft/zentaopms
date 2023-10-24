#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/compile.class.php';
su('admin');

/**

title=测试 compileModel->createByJob();
cid=1
pid=1

检查是否可以拿到通过id为1的job数据创建的compile >> 这是一个Job1
检查是否可以拿到通过不存在的job数据创建的compile >> 0

*/

$compile = new compileTest();
r($compile->createByJobTest('1', '123')) && p('0:name') && e('这是一个Job1'); //检查是否可以拿到通过id为1的job数据创建的compile
r($compile->createByJobTest('3', '123')) && p('0:name') && e('0');            //检查是否可以拿到通过不存在的job数据创建的compile
