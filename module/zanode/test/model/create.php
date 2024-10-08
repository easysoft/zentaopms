#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodeModel->create().
timeout=0
cid=1

- 测试创建一个执行节点
 - 属性name @zanode1
 - 属性type @node
 - 属性status @running

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanode.unittest.class.php';

zenData('host')->gen(0);
zenData('user')->gen(5);
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