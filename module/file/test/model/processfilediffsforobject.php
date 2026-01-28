#!/usr/bin/env php
<?php

/**

title=测试 fileModel->processFileDiffsForObject();
timeout=0
cid=16520

- 传入空对象。属性count @added:;delete:;rename:;
- 没有删除文件字段。 @added:;delete:;rename:;
- 有删除文件字段。 @added:;delete:文件标题1,文件标题2;rename:;

- 有重命名文件字段。 @added:;delete:文件标题3;rename:文件标题3,11,文件标题4,22;

- 有重命名文件字段。 @added:;delete:文件标题1;rename:文件标题1,11;

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$file = zenData('file');
$file->pathname->range('202305/0414225006610005,202305/0414225006610006,202305/0414225006610007,202305/0414225006610008,202305/0414225006610009,202305/0414225006610010,202305/0414225006610011');
$file->gen(5);

$file = new fileModelTest();

$debug = $config->debug;
$config->debug = 0;

r($file->processFileDiffsForObjectTest('story', new stdclass(), new stdclass())) && p('count') && e('added:;delete:;rename:;'); //传入空对象。

$oldFiles = $file->objectModel->getByIdList('1,2,3,4');
$oldObject = new stdclass();
$oldObject->id = 1;
$oldObject->files = $oldFiles;

$newObject = new stdclass();
$newObject->id = 1;
r($file->processFileDiffsForObjectTest('story', $oldObject, $newObject)) && p() && e('added:;delete:;rename:;'); //没有删除文件字段。

$oldObject->files       = $oldFiles;
$newObject->deleteFiles = array(1, 2);
r($file->processFileDiffsForObjectTest('story', $oldObject, $newObject)) && p() && e('added:;delete:文件标题1,文件标题2;rename:;'); //有删除文件字段。

$oldObject->files       = $oldFiles;
$newObject->deleteFiles = array(3);
$newObject->renameFiles = array(3 => '11', 4 => '22');
r($file->processFileDiffsForObjectTest('story', $oldObject, $newObject)) && p() && e('added:;delete:文件标题3;rename:文件标题3,11,文件标题4,22;'); //有重命名文件字段。

$oldObject->files       = $oldFiles;
$newObject->deleteFiles = array(1);
$newObject->renameFiles = array(1 => '11');
r($file->processFileDiffsForObjectTest('story', $oldObject, $newObject)) && p() && e('added:;delete:文件标题1;rename:文件标题1,11;'); //有重命名文件字段。

$config->debug = $debug;