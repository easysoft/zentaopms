#!/usr/bin/env php
<?php

/**

title=测试 fileModel->processFile4Object();
cid=0

- 传入空对象。属性count @5
- 没有删除文件字段。
 - 属性count @5
 - 第old条的files属性 @1,2,3,4
- 有删除文件字段。
 - 属性count @3
 - 第old条的files属性 @3,4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

$file = zdTable('file');
$file->pathname->range('202305/0414225006610005,202305/0414225006610006,202305/0414225006610007,202305/0414225006610008,202305/0414225006610009,202305/0414225006610010,202305/0414225006610011');
$file->gen(5);

$file = new fileTest();

$debug = $config->debug;
$config->debug = 0;

r($file->processFile4ObjectTest('story', new stdclass(), new stdclass())) && p('count') && e('5'); //传入空对象。

$oldObject = new stdclass();
$oldObject->id = 1;
$oldObject->files = $file->objectModel->getByIdList('1,2,3,4');

$newObject = new stdclass();
$newObject->id = 1;
r($file->processFile4ObjectTest('story', $oldObject, $newObject)) && p('count;old:files', ';') && e('5;1,2,3,4'); //没有删除文件字段。

$oldObject->files       = $file->objectModel->getByIdList('1,2,3,4');
$newObject->deleteFiles = array(1, 2);
r($file->processFile4ObjectTest('story', $oldObject, $newObject)) && p('count;old:files', ';') && e('3;3,4'); //有删除文件字段。

$config->debug = $debug;
