#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 zanodeModel->create().
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('host')->gen(0);
zdTable('user')->gen(5);
su('admin');

$zanode = new zanodeTest();

$postData = new stdclass();
$postData->hostType      = '';
$postData->parent        = 3;
$postData->name          = 'zanode1';
$postData->extranet      = '';
$postData->image         = 1;
$postData->cpuCores      = 1;
$postData->memory        = 2;
$postData->diskSize      = 20;
$postData->osName        = 'Ubuntu 20.04';
$postData->osNamePre     = 'linux';
$postData->osNamePhysics = 'centOS65';
$postData->desc          = '这是执行节点描述';
$postData->type          = 'node';
$postData->status        = 'running';
r($zanode->createTest($postData)) && p('name,type,status') && e('zanode1,node,running'); //测试创建一个执行节点
