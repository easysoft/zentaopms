#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/compile.class.php';

zdTable('compile')->gen(1);
zdTable('job')->gen(1);
su('admin');

/**

title=测试 compileModel->getByID();
cid=1
pid=1

检查当id存在的时候是否能拿到数据 >> 构建1
检查当id不存在的时候返回的结果 >> 0

*/

$compile = new compileTest();

r($compile->getByIDTest('1')) && p('name') && e('构建1'); //检查当id存在的时候是否能拿到数据
r($compile->getByIDTest('2')) && p('name') && e('0');     //检查当id不存在的时候返回的结果
