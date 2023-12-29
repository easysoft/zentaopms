#!/usr/bin/env php
<?php

/**

title=测试 zahostModel->queryDownloadImageStatus();
timeout=0
cid=1

- 查询 test 镜像状态
 - 属性status @creating
 - 属性path @~~
- 查询 Ubuntu20.04 镜像状态
 - 属性status @completed
 - 属性path @/home/devops/zagent/download/Ubuntu20.04.qcow2
- 查询 win10 镜像状态
 - 属性status @completed
 - 属性path @/home/devops/zagent/download/win10.qcow2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zahost.class.php';
su('admin');

$host = zdTable('host');
$host->id->range('1');
$host->type->range('zahost');
$host->name->range('宿主机1');
$host->extranet->range('10.0.1.222');
$host->zap->range('55001');
$host->gen(1);

zdTable('image')->gen(0);

$image = zdTable('image');
$image->config('image');
$image->name->range('Ubuntu20.04,win10,test');
$image->gen(3);

$imageID = array(1, 2, 3);

$zahost = new zahostTest();
r($zahost->queryDownloadImageStatusTest($imageID[2])) && p('status,path') && e('creating,~~');                                              //查询 test 镜像状态
r($zahost->queryDownloadImageStatusTest($imageID[0])) && p('status,path') && e('completed,/home/devops/zagent/download/Ubuntu20.04.qcow2'); //查询 Ubuntu20.04 镜像状态
r($zahost->queryDownloadImageStatusTest($imageID[1])) && p('status,path') && e('completed,/home/devops/zagent/download/win10.qcow2');       //查询 win10 镜像状态