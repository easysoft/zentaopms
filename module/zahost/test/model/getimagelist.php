#!/usr/bin/env php
<?php

/**

title=测试 zahostModel::getImageList();
timeout=0
cid=19747

- 执行zahost模块的getImageListTest方法，参数是1 第Ubuntu 20.04条的name属性 @Ubuntu 20.04
- 执行zahost模块的getImageListTest方法，参数是999  @0
- 执行zahost模块的getImageListTest方法  @0
- 执行zahost模块的getImageListTest方法，参数是1 第Windows 10条的status属性 @notDownloaded
- 执行zahost模块的getImageListTest方法，参数是1 第Windows 10条的downloadMisc属性 @title='下载镜像' class='btn image-download image-download-2 '
- 执行zahost模块的getImageListTest方法，参数是1, 'name_asc'  @3
- 执行zahost模块的getImageListTest方法，参数是2 第Debian 11条的name属性 @Debian 11

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zahost.unittest.class.php';

$host = zenData('host');
$host->id->range('1-3');
$host->type->range('zahost');
$host->name->range('宿主机1,宿主机2,宿主机3');
$host->extranet->range('10.0.1.222,10.0.1.223,10.0.1.224');
$host->zap->range('55001,55002,55003');
$host->status->range('online');
$host->deleted->range('0');
$host->gen(3);

zenData('image')->gen(0);

$image = zenData('image');
$image->id->range('1-5');
$image->host->range('1{3},2{2}');
$image->name->range('Ubuntu 20.04,Windows 10,CentOS 7,Debian 11,Alpine Linux');
$image->status->range('completed,notDownloaded,inprogress,created,canceled');
$image->from->range('system{4},user');
$image->md5->range('abc123{5}');
$image->address->range('http://example.com/ubuntu.qcow2,http://example.com/windows.qcow2,http://example.com/centos.qcow2,http://example.com/debian.qcow2,http://example.com/alpine.qcow2');
$image->gen(5);

su('admin');

$zahost = new zahostTest();

r($zahost->getImageListTest(1)) && p('Ubuntu 20.04:name') && e('Ubuntu 20.04');
r($zahost->getImageListTest(999)) && p() && e('0');
r($zahost->getImageListTest(0)) && p() && e('0');
r($zahost->getImageListTest(1)) && p('Windows 10:status') && e('notDownloaded');
r($zahost->getImageListTest(1)) && p('Windows 10:downloadMisc') && e("title='下载镜像' class='btn image-download image-download-2 '");
r(count($zahost->getImageListTest(1, 'name_asc'))) && p() && e(3);
r($zahost->getImageListTest(2)) && p('Debian 11:name') && e('Debian 11');