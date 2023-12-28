#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 zanodeModel->updateImageStatus().
cid=1

- 测试修改镜像的状态和路径
 - 属性status @failed
 - 属性path @/home/devops/zagent/download/ubuntu20.04.qcow2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('image')->gen(1);
zdTable('user')->gen(5);
su('admin');

$postData = new stdclass();
$postData->status = 'failed';
$postData->path   = '/home/devops/zagent/download/ubuntu20.04.qcow2';

$zanode = new zanodeTest();
r($zanode->updateImageStatusTest(1, $postData)) && p('status,path') && e('failed,/home/devops/zagent/download/ubuntu20.04.qcow2'); //测试修改镜像的状态和路径
