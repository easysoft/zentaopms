#!/usr/bin/env php
<?php

/**

title=测试 fileModel->replaceImgURL();
cid=0

- 不传入任何数据。 @0
- 不传入 editorList 参数。属性step @<img src="{1.png}" /> <a href="https://www.baidu.com">baidu</a> https://www.zentao.net
- 字段中不存在 editorList 参数的数据。属性step @<img src="{1.png}" /> <a href="https://www.baidu.com">baidu</a> https://www.zentao.net
- 正常传入参数。属性step @<img src="/file-read-1.png" /> <a href="https://www.baidu.com">baidu</a> <a href="https://www.zentao.net" target="_blank">https://www.zentao.net</a>
- 正常传入参数，但是 requestType = GET。属性step @<img src="/replaceimgurl.php?m=file&f=read&t=png&fileID=1" /> <a href="https://www.baidu.com">baidu</a> <a href="https://www.zentao.net" target="_blank">https://www.zentao.net</a>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$fileModel = $tester->loadModel('file');
$fileModel->config->requestType = 'PATH_INFO';
$fileModel->config->webRoot     = '/';
$fileModel->app->methodName     = 'view';

$file = new stdclass();
$file->step = '<img src="{1.png}" /> <a href="https://www.baidu.com">baidu</a> https://www.zentao.net';

r(get_object_vars($fileModel->replaceImgURL(new stdclass(), '', ''))) && p() && e('0'); //不传入任何数据。

r($fileModel->replaceImgURL($file, ''))        && p('step') && e('<img src="{1.png}" /> <a href="https://www.baidu.com">baidu</a> https://www.zentao.net'); //不传入 editorList 参数。
r($fileModel->replaceImgURL($file, 'content')) && p('step') && e('<img src="{1.png}" /> <a href="https://www.baidu.com">baidu</a> https://www.zentao.net'); //字段中不存在 editorList 参数的数据。
r($fileModel->replaceImgURL($file, 'step'))    && p('step') && e('<img src="/file-read-1.png" /> <a href="https://www.baidu.com">baidu</a> <a href="https://www.zentao.net" target="_blank">https://www.zentao.net</a>'); //正常传入参数。

$fileModel->config->requestType = 'GET';
$file->step = '<img src="{1.png}" /> <a href="https://www.baidu.com">baidu</a> https://www.zentao.net';
r($fileModel->replaceImgURL($file, 'step')) && p('step') && e('<img src="/replaceimgurl.php?m=file&f=read&t=png&fileID=1" /> <a href="https://www.baidu.com">baidu</a> <a href="https://www.zentao.net" target="_blank">https://www.zentao.net</a>'); //正常传入参数，但是 requestType = GET。
