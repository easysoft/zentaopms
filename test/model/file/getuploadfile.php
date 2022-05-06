#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/file.class.php';
su('admin');

/**

title=测试 fileModel->getUploadFile();
cid=1
pid=1

测试获取上传image1时的文件信息 >> png,img1,3021,/tmp/phpsjisk
测试获取上传image2时的文件信息 >> png,img2,3281,/tmp/phpsjisk
测试获取上传image3时的文件信息 >> png,img3,938,/tmp/phpsjisk
测试获取上传image4时的文件信息 >> png,img4,4821,/tmp/phpsjisk
测试获取上传image5时的文件信息 >> png,img5,9870,/tmp/phpsjisk

*/

$image1 = new stdclass();
$image1->name   = 'img1.png';
$image1->chunk  = 0;
$image1->chunks = 1;
$image1->label  = 'img1';
$image1->uuid   = 'o_peahuifhuieafj';
$image1->size   = '3021';

$image2 = new stdclass();
$image2->name   = 'img2.png';
$image2->chunk  = 0;
$image2->chunks = 1;
$image2->label  = 'img2';
$image2->uuid   = 'o_fnjakehfjkahek';
$image2->size   = '3281';

$image3 = new stdclass();
$image3->name   = 'img3.png';
$image3->chunk  = 0;
$image3->chunks = 1;
$image3->label  = 'img3';
$image3->uuid   = 'o_fjaieojfioeiei';
$image3->size   = '938';

$image4 = new stdclass();
$image4->name   = 'img4.png';
$image4->chunk  = 0;
$image4->chunks = 1;
$image4->label  = 'img4';
$image4->uuid   = 'o_aehufhuihfiuae';
$image4->size   = '4821';

$image5 = new stdclass();
$image5->name   = 'img5.png';
$image5->chunk  = 0;
$image5->chunks = 1;
$image5->label  = 'img5';
$image5->uuid   = 'o_hfauehuihfiuae';
$image5->size   = '9870';

$files = array('tmp_name' => '/tmp/phpsjisk', 'name' => 'filename');

$file = new fileTest();

r($file->getUploadFileTest($image1, $files)) && p('extension,title,size,tmpname') && e('png,img1,3021,/tmp/phpsjisk'); // 测试获取上传image1时的文件信息
r($file->getUploadFileTest($image2, $files)) && p('extension,title,size,tmpname') && e('png,img2,3281,/tmp/phpsjisk'); // 测试获取上传image2时的文件信息
r($file->getUploadFileTest($image3, $files)) && p('extension,title,size,tmpname') && e('png,img3,938,/tmp/phpsjisk');  // 测试获取上传image3时的文件信息
r($file->getUploadFileTest($image4, $files)) && p('extension,title,size,tmpname') && e('png,img4,4821,/tmp/phpsjisk'); // 测试获取上传image4时的文件信息
r($file->getUploadFileTest($image5, $files)) && p('extension,title,size,tmpname') && e('png,img5,9870,/tmp/phpsjisk'); // 测试获取上传image5时的文件信息