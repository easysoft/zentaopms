#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 zanodeModel->linkAgentService().
cid=1

- 测试连接宿主机属性code @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('host')->config('host')->gen(2);
zdTable('image')->config('image')->gen(1);
zdTable('user')->gen(5);
su('admin');

$data = new stdclass();
$data->hostType      = '';
$data->parent        = 1;
$data->name          = 'zanodeTest';
$data->extranet      = '';
$data->image         = 1;
$data->cpuCores      = 1;
$data->memory        = 1;
$data->diskSize      = 1;
$data->osName        = 'Ubuntu 20.04';
$data->osNamePre     = 'linux';
$data->osNamePhysics = 'centOS65';
$data->desc          = '';
$data->type          = 'node';
$data->createdBy     = 'admin';
$data->status        = 'running';

global $tester;
$zanode = $tester->loadModel('zanode');
r($zanode->linkAgentService($data)) && p('code') && e('success'); //测试连接宿主机
