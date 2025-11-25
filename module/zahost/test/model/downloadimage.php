#!/usr/bin/env php
<?php

/**

title=测试 zahostModel::downloadImage();
timeout=0
cid=19743

- 步骤1：下载第1个镜像，返回错误信息 @创建下载镜像任务失败
- 步骤2：下载不存在的镜像 @0
- 步骤3：下载第2个镜像，返回错误信息 @创建下载镜像任务失败
- 步骤4：下载第5个镜像，返回错误信息 @创建下载镜像任务失败
- 步骤5：下载第3个镜像，返回错误信息 @创建下载镜像任务失败

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zahost.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$host = zenData('host');
$host->id->range('1-5');
$host->name->range('testhost{5}');
$host->extranet->range('127.0.0.1,192.168.1.100,10.0.0.1,172.16.0.1,localhost');
$host->zap->range('8080,8081,8082,8083,8084');
$host->tokenSN->range('token{5}');
$host->type->range('zahost{5}');
$host->status->range('online{3},offline{2}');
$host->deleted->range('0{5}');
$host->gen(5);

$image = zenData('image');
$image->id->range('1-10');
$image->name->range('ubuntu,centos,debian,alpine,nginx,mysql,redis,php,python,java');
$image->host->range('1-5:1');
$image->md5->range('md5hash{10}');
$image->address->range('https://pkg.qucheng.com/zenagent/image/ubuntu.qcow2,https://pkg.qucheng.com/zenagent/image/centos.qcow2,https://pkg.qucheng.com/zenagent/image/debian.qcow2,https://pkg.qucheng.com/zenagent/image/alpine.qcow2,https://pkg.qucheng.com/zenagent/image/nginx.qcow2,https://pkg.qucheng.com/zenagent/image/mysql.qcow2,https://pkg.qucheng.com/zenagent/image/redis.qcow2,https://pkg.qucheng.com/zenagent/image/php.qcow2,https://pkg.qucheng.com/zenagent/image/python.qcow2,https://pkg.qucheng.com/zenagent/image/java.qcow2');
$image->status->range('notDownloaded{3},inprogress{2},completed{3},failed{2}');
$image->from->range('system{8},user{2}');
$image->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$zahostTest = new zahostTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($zahostTest->downloadImageTest(1)) && p('0') && e('创建下载镜像任务失败'); // 步骤1：下载第1个镜像，返回错误信息
r($zahostTest->downloadImageTest(999)) && p() && e('0'); // 步骤2：下载不存在的镜像
r($zahostTest->downloadImageTest(2)) && p('0') && e('创建下载镜像任务失败'); // 步骤3：下载第2个镜像，返回错误信息
r($zahostTest->downloadImageTest(5)) && p('0') && e('创建下载镜像任务失败'); // 步骤4：下载第5个镜像，返回错误信息
r($zahostTest->downloadImageTest(3)) && p('0') && e('创建下载镜像任务失败'); // 步骤5：下载第3个镜像，返回错误信息