#!/usr/bin/env php
<?php

/**

title=测试 fileModel->replaceFile();
cid=0

- 测试 id = 0 的记录。 @0
- 测试替换文件。
 - 属性id @1
 - 属性pathname @202305/0414225006610005
 - 属性size @2893
- 测试不存在记录。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

$file = zdTable('file');
$file->pathname->range('202305/0414225006610005,202305/0414225006610006,202305/0414225006610007,202305/0414225006610008,202305/0414225006610009,202305/0414225006610010,202305/0414225006610011');
$file->gen(5);

$file = new fileTest();

$files  = array('name' => 'file3.ppt', 'size' => '2893', 'tmp_name' => '/tmp/phpu2el');
$labels = array('file3.ppt');

r($file->replaceFileTest(0, $files, $labels)) && p()                   && e('0');                              // 测试 id = 0 的记录。
r($file->replaceFileTest(1, $files, $labels)) && p('id,pathname,size') && e('1,202305/0414225006610005,2893'); // 测试替换文件。
r($file->replaceFileTest(8, $files, $labels)) && p(0)                  && e('0');                              // 测试不存在记录。
