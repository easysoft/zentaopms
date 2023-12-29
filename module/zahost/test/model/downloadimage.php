#!/usr/bin/env php
<?php

/**

title=测试 zahostModel->downloadImage();
timeout=0
cid=1

- 下载 Ubuntu20.04 镜像成功属性status @created
- 下载 win10 镜像成功属性status @created
- 下载 test 镜像失败 @创建下载镜像任务失败

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zahost.class.php';
su('admin');

$host = zdTable('host');
$host->id->range('1-2');
$host->type->range('zahost');
$host->name->range('宿主机1,宿主机2');
$host->extranet->range('10.0.1.222,a');
$host->zap->range('55001,0');
$host->gen(2);

$image = zdTable('image');
$image->config('image');
$image->name->range('Ubuntu20.04,win10,test');
$image->gen(3);

$imageID = array(1, 2, 3);

$zahost = new zahostTest();
r($zahost->downloadImageTest($imageID[0])) && p('status') && e('created');               //下载 Ubuntu20.04 镜像成功
r($zahost->downloadImageTest($imageID[1])) && p('status') && e('created');               //下载 win10 镜像成功
r($zahost->downloadImageTest($imageID[2])) && p('0')      && e('创建下载镜像任务失败');  //下载 test 镜像失败