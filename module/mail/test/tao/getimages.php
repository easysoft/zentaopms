#!/usr/bin/env php
<?php

/**

title=测试 mailTao::getImages();
timeout=0
cid=17030

- 执行mailModel模块的getImages方法，参数是$body1  @1
- 执行mailModel模块的getImages方法，参数是$body2  @1
- 执行mailModel模块的getImages方法，参数是$body3  @0
- 执行mailModel模块的getImages方法，参数是$body4  @0
- 执行mailModel模块的getImages方法，参数是$body5  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$file = zenData('file');
$file->id->range('1-3');
$file->pathname->range('202301/test1.jpg,202301/test2.png,202301/test3.gif');
$file->title->range('图片1,图片2,图片3');
$file->extension->range('jpg,png,gif');
$file->size->range('1024,2048,3072');
$file->objectType->range('mail,mail,mail');
$file->objectID->range('1-3');
$file->addedBy->range('admin');
$file->addedDate->range('`2023-01-01 10:00:00`');
$file->gen(3);

su('admin');

global $tester;
$mailModel = $tester->loadModel('mail');

$body1 = '<img alt="test" src="{1.jpg}" />';
$body2 = '<img alt="test" src="/data/upload/test/image" />';
$body3 = '<img alt="test" src="index.php?m=file&f=read&fileID=1" />';
$body4 = '<img alt="test" src="/index.php?m=file&f=read&fileID=999" />';
$body5 = '<p>No images here</p>';

r(count($mailModel->getImages($body1))) && p() && e('1');
r(count($mailModel->getImages($body2))) && p() && e('1');
r(count($mailModel->getImages($body3))) && p() && e('0');
r(count($mailModel->getImages($body4))) && p() && e('0');
r(count($mailModel->getImages($body5))) && p() && e('0');