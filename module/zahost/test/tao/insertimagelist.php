#!/usr/bin/env php
<?php

/**

title=测试 zahostTao::insertImageList();
timeout=0
cid=19758

- 执行zahost模块的insertImageListTest方法，参数是$imageList, $hostID, $downloadedImageList  @0
- 执行zahost模块的insertImageListTest方法，参数是$imageList, $hostID, $downloadedImageList 第0条的name属性 @centos7
- 执行zahost模块的insertImageListTest方法，参数是$imageList, $hostID, $downloadedImageList  @3
- 执行zahost模块的insertImageListTest方法，参数是$emptyImageList, $hostID, $downloadedImageList  @0
- 执行zahost模块的insertImageListTest方法，参数是$singleImageList, $invalidHostID, $downloadedImageList 第0条的host属性 @999

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('host')->gen(0);
$host = zenData('host');
$host->id->range('1,2');
$host->type->range('zahost');
$host->name->range('宿主机1,宿主机2');
$host->gen(2);

su('admin');

$image1 = new stdClass();
$image1->name     = 'ubuntu20.04';
$image1->address  = 'https://pkg.qucheng.com/zenagent/image/ubuntu20.04.qcow2';
$image1->memory   = 2;
$image1->disk     = 20;
$image1->fileSize = 5.82;
$image1->os       = 'Ubuntu 20.04';
$image1->desc     = '基于Ubuntu20.04桌面版的镜像';

$image2 = new stdClass();
$image2->name     = 'win10';
$image2->address  = 'https://pkg.qucheng.com/zenagent/image/win10.qcow2';
$image2->memory   = 4;
$image2->disk     = 40;
$image2->fileSize = 9.55;
$image2->os       = 'Windows 10';
$image2->desc     = '基于Windows10桌面版的镜像';

$image3 = new stdClass();
$image3->name     = 'centos7';
$image3->address  = 'https://pkg.qucheng.com/zenagent/image/centos7.qcow2';
$image3->memory   = 1;
$image3->disk     = 10;
$image3->fileSize = 3.5;
$image3->os       = 'CentOS 7';
$image3->desc     = '基于CentOS7的镜像';

$imageList = array($image1, $image2, $image3);
$hostID = 1;

$zahost = new zahostTaoTest();

// 测试步骤1：所有镜像都已下载，不插入任何镜像
zenData('image')->gen(0);
$downloadedImageList = array('ubuntu20.04' => 'ubuntu20.04', 'win10' => 'win10', 'centos7' => 'centos7');
r(count($zahost->insertImageListTest($imageList, $hostID, $downloadedImageList))) && p() && e('0');

// 测试步骤2：部分镜像已下载，插入未下载镜像
zenData('image')->gen(0);
$downloadedImageList = array('ubuntu20.04' => 'ubuntu20.04', 'win10' => 'win10');
r($zahost->insertImageListTest($imageList, $hostID, $downloadedImageList)) && p('0:name') && e('centos7');

// 测试步骤3：空的已下载镜像列表，插入所有镜像
zenData('image')->gen(0);
$downloadedImageList = array();
r(count($zahost->insertImageListTest($imageList, $hostID, $downloadedImageList))) && p() && e('3');

// 测试步骤4：空的镜像列表，不插入任何镜像
zenData('image')->gen(0);
$emptyImageList = array();
$downloadedImageList = array();
r(count($zahost->insertImageListTest($emptyImageList, $hostID, $downloadedImageList))) && p() && e('0');

// 测试步骤5：测试无效主机ID，验证业务处理
zenData('image')->gen(0);
$invalidHostID = 999;
$downloadedImageList = array();
$singleImageList = array($image1);
r($zahost->insertImageListTest($singleImageList, $invalidHostID, $downloadedImageList)) && p('0:host') && e('999');