#!/usr/bin/env php
<?php

/**

title=测试 zahostTao->insertImageList();
timeout=0
cid=1

- 测试镜像已经插入到 image 表中, 不会再次插入 @0
- 测试 image2 镜像已经插入到 image 表中, image1镜像会被插入第0条的name属性 @image1
- 测试image1 和 image2 镜像都没有插入, image2 镜像会被插入第1条的name属性 @image2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zahost.class.php';
su('admin');

$host = zdTable('host');
$host->type->range('zahost');
$host->name->range('宿主机1');
$host->gen(1);

zdTable('image')->gen(0);

$image1 = new stdClass();
$image1->name     = 'image1';
$image1->address  = 'https://pkg.qucheng.com/zenagent/image/ubuntu20.04.qcow2';
$image1->memory   = 2;
$image1->disk     = 20;
$image1->fileSize = 5.82;
$image1->os       = 'Ubuntu 20.04';
$image1->desc     = '基于Ubuntu20.04桌面版的镜像';

$image2 = new stdClass();
$image2->name     = 'image2';
$image2->address  = 'https://pkg.qucheng.com/zenagent/image/win10.qcow2';
$image2->memory   = 4;
$image2->disk     = 40;
$image2->fileSize = 9.55;
$image2->os       = 'Windows 10';
$image2->desc     = '基于Windows10桌面版的镜像';

$imageList = array();
$imageList[] = $image1;
$imageList[] = $image2;

$downloadedImageList = array();
$downloadedImageList['image1'] = 'image1';
$downloadedImageList['image2'] = 'image2';

$hostID = 1;

$zahost = new zahostTest();
r($zahost->insertImageListTest($imageList, $hostID, $downloadedImageList)) && p('') && e('0'); //测试镜像已经插入到 image 表中, 不会再次插入

zdTable('image')->gen(0);
unset($downloadedImageList['image1']);
r($zahost->insertImageListTest($imageList, $hostID, $downloadedImageList)) && p('0:name') && e('image1'); //测试 image2 镜像已经插入到 image 表中, image1镜像会被插入

zdTable('image')->gen(0);
unset($downloadedImageList['image2']);
r($zahost->insertImageListTest($imageList, $hostID, $downloadedImageList)) && p('1:name') && e('image2'); //测试image1 和 image2 镜像都没有插入, image2 镜像会被插入