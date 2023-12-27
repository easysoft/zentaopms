#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/compile.class.php';

zdTable('compile')->gen(10);
zdTable('job')->gen(1);
su('admin');

/**

title=测试 compileModel->createByJob();
cid=1
pid=1

检查是否可以拿到通过id为1的job数据创建的compile >> 这是一个Job1
检查是否可以拿到通过不存在的job数据创建的compile >> 0

*/

$compile = new compileTest();
r($compile->createByJobTest(1, '123')) && p('name') && e('这是一个Job1'); //检查是否可以拿到通过id为1的job数据创建的compile
r($compile->createByJobTest(3, '123')) && p('name') && e('0');            //检查是否可以拿到通过不存在的job数据创建的compile
