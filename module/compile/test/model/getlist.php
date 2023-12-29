#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/compile.class.php';

zdTable('compile')->gen(1);
zdTable('job')->config('job')->gen(1);
su('admin');

/**

title=测试 compileModel->getList();
cid=1
pid=1

检查是否能获取到数据 >> 构建1
检查获取不存在的数据会返回什么 >> 0

*/

$compile = new compileTest();

r($compile->getListTest(1, 1)) && p('1:name') && e('构建1'); //检查是否能获取到数据
r($compile->getListTest(3, 1)) && p('')       && e('0');     //检查获取不存在的数据会返回什么
