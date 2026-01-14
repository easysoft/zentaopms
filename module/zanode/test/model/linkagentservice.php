#!/usr/bin/env php
<?php

/**

title=测试 zanodeModel::linkAgentService();
timeout=0
cid=19841

- 步骤1：正常情况下Agent服务不可用属性image @没有发现Agent服务
- 步骤2：不存在的镜像ID @0
- 步骤3：不存在的宿主机ID @0
- 步骤4：空数据对象 @0
- 步骤5：网络错误情况属性image @没有发现Agent服务

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// zendata数据准备
$host = zenData('host');
$host->id->range('1-10');
$host->name->range('host{1-10}');
$host->type->range('host');
$host->extranet->range('192.168.1.{101-110}');
$host->zap->range('8848');
$host->tokenSN->range('token{1-10}');
$host->deleted->range('0');
$host->gen(10);

$image = zenData('image');
$image->id->range('1-5');
$image->name->range('ubuntu{1-5}');
$image->osName->range('ubuntu20.04,ubuntu18.04,centos7,centos8,debian');
$image->path->range('/path/to/image{1-5}');
$image->status->range('completed');
$image->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$zanodeTest = new zanodeModelTest();

// 测试数据准备
$validData = new stdClass();
$validData->image = 1;
$validData->parent = 1;
$validData->name = 'test-vm';
$validData->cpuCores = 2;
$validData->diskSize = 20;
$validData->memory = 4;

$invalidImageData = new stdClass();
$invalidImageData->image = 999;
$invalidImageData->parent = 1;
$invalidImageData->name = 'test-vm';
$invalidImageData->cpuCores = 2;
$invalidImageData->diskSize = 20;
$invalidImageData->memory = 4;

$invalidHostData = new stdClass();
$invalidHostData->image = 1;
$invalidHostData->parent = 999;
$invalidHostData->name = 'test-vm';
$invalidHostData->cpuCores = 2;
$invalidHostData->diskSize = 20;
$invalidHostData->memory = 4;

$emptyData = new stdClass();
$emptyData->image = null;
$emptyData->parent = null;
$emptyData->name = '';
$emptyData->cpuCores = 0;
$emptyData->diskSize = 0;
$emptyData->memory = 0;

$networkErrorData = new stdClass();
$networkErrorData->image = 1;
$networkErrorData->parent = 1;
$networkErrorData->name = 'test-vm-network-error';
$networkErrorData->cpuCores = 2;
$networkErrorData->diskSize = 20;
$networkErrorData->memory = 4;

r($zanodeTest->linkAgentServiceTest($validData)) && p('image') && e('没有发现Agent服务'); // 步骤1：正常情况下Agent服务不可用
r($zanodeTest->linkAgentServiceTest($invalidImageData)) && p('') && e('0'); // 步骤2：不存在的镜像ID
r($zanodeTest->linkAgentServiceTest($invalidHostData)) && p('') && e('0'); // 步骤3：不存在的宿主机ID
r($zanodeTest->linkAgentServiceTest($emptyData)) && p('') && e('0'); // 步骤4：空数据对象
r($zanodeTest->linkAgentServiceTest($networkErrorData)) && p('image') && e('没有发现Agent服务'); // 步骤5：网络错误情况