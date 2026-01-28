#!/usr/bin/env php
<?php

/**

title=测试 zahostModel::cancelDownload();
timeout=0
cid=19740

- 状态为inprogress的镜像可以取消下载 @1
- 不存在的镜像ID @0
- 无效的镜像ID 0 @0
- 负数镜像ID @0
- 状态为created的镜像可以取消下载 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备宿主机测试数据
$host = zenData('host');
$host->id->range('1-3');
$host->type->range('zahost');
$host->name->range('zahost-001,zahost-002,zahost-003');
$host->status->range('online');
$host->extranet->range('192.168.1.10,192.168.1.11,192.168.1.12');
$host->memory->range('16,32,64');
$host->cpuCores->range('8,16,32');
$host->tokenSN->range('token123456,token234567,token345678');
$host->zap->range('8080,8081,8082');
$host->deleted->range('0');
$host->gen(3);

// 准备镜像测试数据
$image = zenData('image');
$image->id->range('1-5');
$image->host->range('1,1,2,2,3');
$image->name->range('ubuntu-20.04,centos-7,debian-11,alpine-3.16,fedora-36');
$image->address->range('https://repo.zentao.net/ubuntu-20.04.qcow2,https://repo.zentao.net/centos-7.qcow2,https://repo.zentao.net/debian-11.qcow2,https://repo.zentao.net/alpine-3.16.qcow2,https://repo.zentao.net/fedora-36.qcow2');
$image->status->range('inprogress,created,notDownloaded,canceled,completed');
$image->from->range('zentao{4},user');
$image->md5->range('a1b2c3d4e5f6,b2c3d4e5f6a1,c3d4e5f6a1b2,d4e5f6a1b2c3,e5f6a1b2c3d4');
$image->gen(5);

su('admin');

$zahostTest = new zahostModelTest();

r($zahostTest->cancelDownloadTest(1)) && p() && e('1');     // 状态为inprogress的镜像可以取消下载
r($zahostTest->cancelDownloadTest(999)) && p() && e('0'); // 不存在的镜像ID
r($zahostTest->cancelDownloadTest(0)) && p() && e('0');   // 无效的镜像ID 0
r($zahostTest->cancelDownloadTest(-1)) && p() && e('0');  // 负数镜像ID
r($zahostTest->cancelDownloadTest(2)) && p() && e('1');    // 状态为created的镜像可以取消下载