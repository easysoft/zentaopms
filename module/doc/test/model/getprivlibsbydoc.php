#!/usr/bin/env php
<?php

/**

title=测试 docModel->getPrivLibsByDoc();
cid=1

- 获取有权限访问的文档库
 - 属性11 @11
 - 属性12 @12
 - 属性13 @13
 - 属性6 @6
 - 属性7 @7
 - 属性8 @8
 - 属性16 @16
 - 属性20 @20
 - 属性17 @17
 - 属性18 @18
 - 属性25 @25
 - 属性26 @26

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('doc')->config('doc')->gen(50);
zdTable('user')->gen(5);
su('admin');

$docTester = new docTest();
r($docTester->getPrivLibsByDocTest()) && p('11,12,13,6,7,8,16,20,17,18,25,26') && e('11,12,13,6,7,8,16,20,17,18,25,26'); // 获取有权限访问的文档库
