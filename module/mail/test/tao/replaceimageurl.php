#!/usr/bin/env php
<?php

/**

title=测试 mailModel->replaceImageURL();
cid=0

- 图片链接为 {1.png} @<img src="http://pms.zentao.net/file-read-1.png" />
- 图片链接为 data/upload @<img  src="http://pms.zentao.net/data/upload/1/1.png" />
- 图片链接为 /file-read-1.png @<img src="http://pms.zentao.net/file-read-1.png" />

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$mailModel = $tester->loadModel('mail');
$mailModel->app->user->account   = 'admin';
$mailModel->config->requestType  = 'PATH_INFO';
$mailModel->config->webRoot      = '/';
$mailModel->config->mail->domain = 'http://pms.zentao.net';

$body1 = '<img src="{1.png}" />';
$body2 = '<img src="data/upload/1/1.png" />';
$body3 = '<img src="/file-read-1.png" />';

r($mailModel->replaceImageURL($body1)) && p() && e('<img src="http://pms.zentao.net/file-read-1.png" />');     //图片链接为 {1.png}
r($mailModel->replaceImageURL($body2)) && p() && e('<img  src="http://pms.zentao.net/data/upload/1/1.png" />'); //图片链接为 data/upload
r($mailModel->replaceImageURL($body3)) && p() && e('<img src="http://pms.zentao.net/file-read-1.png" />');     //图片链接为 /file-read-1.png
