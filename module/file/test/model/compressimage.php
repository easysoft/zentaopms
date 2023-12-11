#!/usr/bin/env php
<?php
/**

title=测试 fileModel->compressImage();
cid=0

- 测试上传 png 文件
 - 属性extension @png
 - 属性title @file.png
 - 属性size @2038
- 测试上传 jpg 文件
 - 属性extension @jpg
 - 属性title @file.jpg
 - 属性size @1888573
- 测试上传 wri 文件
 - 属性extension @wri
 - 属性title @这是一个文件名称6.wri
 - 属性size @38624
- 测试上传 pdf 文件
 - 属性extension @pdf
 - 属性title @这是一个文件名称7.pdf
 - 属性size @25964
- 测试上传 ppt 文件
 - 属性extension @ppt
 - 属性title @这是一个文件名称8.ppt
 - 属性size @37248

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

$pngFile = new stdclass();
$pngFile->extension = 'png';
$pngFile->pathname  = '202205/06094008030126kf.png';
$pngFile->title     = 'file.png';
$pngFile->size      = '2038';
$pngFile->tmpname   = '/tmp/php4dtXN0';

$jpgFile = new stdclass();
$jpgFile->extension = 'jpg';
$jpgFile->pathname  = '202205/0609531806661fhm.jpg';
$jpgFile->title     = 'file.jpg';
$jpgFile->size      = '1888573';
$jpgFile->tmpname   = '/tmp/phpt5IxrF';

$wriFile = new stdclass();
$wriFile->extension = 'wri';
$wriFile->pathname  = '202006/0414225006610006.wri';
$wriFile->title     = '这是一个文件名称6.wri';
$wriFile->size      = '38624';
$wriFile->tmpname   = '/tmp/php34948';

$pdfFile = new stdclass();
$pdfFile->extension = 'pdf';
$pdfFile->pathname  = '202007/0414225006610007.pdf';
$pdfFile->title     = '这是一个文件名称7.pdf';
$pdfFile->size      = '25964';
$pdfFile->tmpname   = '/tmp/php4h2k3';

$pptFile = new stdclass();
$pptFile->extension = 'ppt';
$pptFile->pathname  = '202008/0414225006610008.ppt';
$pptFile->title     = '这是一个文件名称8.ppt';
$pptFile->size      = '37248';
$pptFile->tmpname   = '/tmp/php7bhfki';

$file = new fileTest();

r($file->compressImageTest((array)$pngFile)) && p('extension,title,size') && e('png,file.png,2038');               // 测试上传 png 文件
r($file->compressImageTest((array)$jpgFile)) && p('extension,title,size') && e('jpg,file.jpg,1888573');            // 测试上传 jpg 文件
r($file->compressImageTest((array)$wriFile)) && p('extension,title,size') && e('wri,这是一个文件名称6.wri,38624'); // 测试上传 wri 文件
r($file->compressImageTest((array)$pdfFile)) && p('extension,title,size') && e('pdf,这是一个文件名称7.pdf,25964'); // 测试上传 pdf 文件
r($file->compressImageTest((array)$pptFile)) && p('extension,title,size') && e('ppt,这是一个文件名称8.ppt,37248'); // 测试上传 ppt 文件
