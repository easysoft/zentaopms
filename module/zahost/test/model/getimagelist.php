#!/usr/bin/env php
<?php

/**

title=测试 zahostModel->getImageList();
timeout=0
cid=1

- 测试获取主机 1 的镜像列表数量 @4
- 没有下载过镜像的主机获取的 Ubuntu 20.04 镜像第Ubuntu 20.04条的name属性 @Ubuntu 20.04
- 没有下载过镜像的主机获取的 Windows 10 镜像第Windows 10条的name属性 @Windows 10
- 测试获取主机 1 已经下载的镜像列表数量 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zahost.class.php';
su('admin');

zdTable('image')->config('image')->gen(2);

$hostID = 1;
$zahost = new zahostTest();
r(count($zahost->getImageListTest($hostID))) && p('') && e('4');                      //测试获取主机 1 的镜像列表数量
r($zahost->getImageListTest($hostID)) && p('Ubuntu 20.04:name') && e('Ubuntu 20.04'); //没有下载过镜像的主机获取的 Ubuntu 20.04 镜像
r($zahost->getImageListTest($hostID)) && p('Windows 10:name')   && e('Windows 10');   //没有下载过镜像的主机获取的 Windows 10 镜像

zdTable('image')->gen(0);

$image = zdTable('image');
$image->config('image');
$image->name->range('Ubuntu 20.04,Windows 10');
$image->gen(2);

r(count($zahost->getImageListTest($hostID))) && p('') && e('2'); //测试获取主机 1 已经下载的镜像列表数量