#!/usr/bin/env php
<?php

/**

title=测试 docModel->getPrivLibsByDoc();
cid=16123

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
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('doc')->loadYaml('doc')->gen(50);
zenData('user')->gen(5);
su('admin');

$docTester = new docModelTest();
r($docTester->getPrivLibsByDocTest()) && p('11,12,13,6,7,8,16,20,17,18,25,26') && e('11,12,13,6,7,8,16,20,17,18,25,26'); // 获取有权限访问的文档库
