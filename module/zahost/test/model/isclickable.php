#!/usr/bin/env php
<?php

/**

title=测试 zahostModel->isClickable();
timeout=0
cid=1

- 测试是否可以删除 @1
- 测试wait状态下是否可以浏览镜像 @0
- 测试wait状态下是否可以取消下载镜像 @0
- 测试wait状态下是否可以下载镜像 @1
- 测试created状态下是否可以浏览镜像 @1
- 测试created状态下是否可以取消下载镜像 @1
- 测试created状态下是否可以下载镜像 @0
- 测试notDownloaded状态下是否可以浏览镜像 @1
- 测试notDownloaded状态下是否可以取消下载镜像 @0
- 测试notDownloaded状态下是否可以下载镜像 @1
- 测试inprogress状态下是否可以浏览镜像 @1
- 测试inprogress状态下是否可以取消下载镜像 @1
- 测试inprogress状态下是否可以下载镜像 @0
- 测试completed状态下是否可以浏览镜像 @1
- 测试completed状态下是否可以取消下载镜像 @0
- 测试completed状态下是否可以下载镜像 @0
- 测试from=user时是否可以下载镜像 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zahost.class.php';
su('admin');

$action = array('delete', 'browseImage', 'cancelDownload', 'downloadImage');

$object = new stdclass();
$object->canDelete = true;
$object->from      = '';

$zahost = new zahostTest();
r($zahost->isClickable($object, $action[0])) && p() && e('1');  //测试是否可以删除

$object->status = 'wait';
r($zahost->isClickable($object, $action[1])) && p() && e('0');  //测试wait状态下是否可以浏览镜像
r($zahost->isClickable($object, $action[2])) && p() && e('0');  //测试wait状态下是否可以取消下载镜像
r($zahost->isClickable($object, $action[3])) && p() && e('1');  //测试wait状态下是否可以下载镜像

$object->status = 'created';
r($zahost->isClickable($object, $action[1])) && p() && e('1');  //测试created状态下是否可以浏览镜像
r($zahost->isClickable($object, $action[2])) && p() && e('1');  //测试created状态下是否可以取消下载镜像
r($zahost->isClickable($object, $action[3])) && p() && e('0');  //测试created状态下是否可以下载镜像

$object->status = 'notDownloaded';
r($zahost->isClickable($object, $action[1])) && p() && e('1');  //测试notDownloaded状态下是否可以浏览镜像
r($zahost->isClickable($object, $action[2])) && p() && e('0');  //测试notDownloaded状态下是否可以取消下载镜像
r($zahost->isClickable($object, $action[3])) && p() && e('1');  //测试notDownloaded状态下是否可以下载镜像

$object->status = 'inprogress';
r($zahost->isClickable($object, $action[1])) && p() && e('1');  //测试inprogress状态下是否可以浏览镜像
r($zahost->isClickable($object, $action[2])) && p() && e('1');  //测试inprogress状态下是否可以取消下载镜像
r($zahost->isClickable($object, $action[3])) && p() && e('0');  //测试inprogress状态下是否可以下载镜像

$object->status = 'completed';
r($zahost->isClickable($object, $action[1])) && p() && e('1');  //测试completed状态下是否可以浏览镜像
r($zahost->isClickable($object, $action[2])) && p() && e('0');  //测试completed状态下是否可以取消下载镜像
r($zahost->isClickable($object, $action[3])) && p() && e('0');  //测试completed状态下是否可以下载镜像

$object->from = 'user';
r($zahost->isClickable($object, $action[3])) && p() && e('0');  //测试from=user时是否可以下载镜像