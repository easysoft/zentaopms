#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/compile.class.php';
su('admin');

/**

title=测试 compileModel->getByID();
cid=1
pid=1

检查当id存在的时候是否能拿到数据 >> 这是一个Job1
检查当id不存在的时候返回的结果 >> 0

*/

$compile = new compileTest();

r($compile->getByIDTest('1')) && p('name') && e('这是一个Job1'); //检查当id存在的时候是否能拿到数据
r($compile->getByIDTest('2')) && p('name') && e('0');            //检查当id不存在的时候返回的结果