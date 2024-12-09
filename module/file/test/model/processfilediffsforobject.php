#!/usr/bin/env php
<?php

/**

title=测试 fileModel->processFileDiffsForObject();
cid=0

- 传入空对象。属性count @added:;delete:;rename:;
- 没有删除文件字段。 @added:;delete:;rename:;
- 有删除文件字段。 @added:;delete:文件标题1,文件标题2;rename:;

- 有重命名文件字段。 @added:;delete:文件标题3;rename:文件标题3,11,文件标题4,22;

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';
su('admin');

$file = zenData('file');
$file->pathname->range('202305/0414225006610005,202305/0414225006610006,202305/0414225006610007,202305/0414225006610008,202305/0414225006610009,202305/0414225006610010,202305/0414225006610011');
$file->gen(5);

$file = new fileTest();

$debug = $config->debug;
$config->debug = 0;

r($file->processFileDiffsForObjectTest('story', new stdclass(), new stdclass())) && p('count') && e('added:;delete:;rename:;'); //传入空对象。
