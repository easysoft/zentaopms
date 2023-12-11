#!/usr/bin/env php
<?php

/**

title=测试 fileModel->processImgURL();
cid=0

- 不传入任何数据。 @0
- 不传入 editorList 参数。属性step @<img src="/file-read-1.png" />
- 字段中不存在 editorList 参数的数据。属性step @<img src="/file-read-1.png" />
- 正常传入参数。属性step @<img src="{1.png}" />
- 正常传入参数，但是 requestType = GET。属性step @<img src="{1.png}" />
- 检查SESSION数据。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$fileModel = $tester->loadModel('file');
$fileModel->config->requestType = 'PATH_INFO';
$fileModel->config->webRoot     = '/';

$uid  = '1234';
$file = new stdclass();
$file->step = '<img src="/file-read-1.png" />';

r(get_object_vars($fileModel->processImgURL(new stdclass(), '', ''))) && p() && e('0'); //不传入任何数据。

r($fileModel->processImgURL($file, '',        '1234')) && p('step') && e('<img src="/file-read-1.png" />'); //不传入 editorList 参数。
r($fileModel->processImgURL($file, 'content', '1234')) && p('step') && e('<img src="/file-read-1.png" />'); //字段中不存在 editorList 参数的数据。
r($fileModel->processImgURL($file, 'step',    '1234')) && p('step') && e('<img src="{1.png}" />');          //正常传入参数。

$_SESSION['album'][$uid][] = 1;
$fileModel->config->requestType = 'GET';
$file->step = '<img src="' . helper::createLink('file', 'read', 'fileID=1', 'png') . '" />';
r($fileModel->processImgURL($file, 'step', '1234')) && p('step') && e('<img src="{1.png}" />'); //正常传入参数，但是 requestType = GET。

r($_SESSION['album']['used'][$uid][1] == 1) && p() && e(1); //检查SESSION数据。
