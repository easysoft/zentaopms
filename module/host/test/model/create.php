#!/usr/bin/env php
<?php
/**

title=测试 hostModel->create();
timeout=0
cid=1

- 测试正确的创建主机时的返回结果。 @1
- 测试正确的创建主机时有无报错信息。 @0
- 测试创建主机时必填项的校验。
 - 第name条的0属性 @『名称』不能为空。
 - 第intranet条的0属性 @『内网IP』不能为空。
 - 第extranet条的0属性 @『外网IP』不能为空。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('module')->config('module')->gen(100)->fixPath();
zdTable('host')->config('host')->gen(30);
zdTable('lang')->gen(0);
su('admin');

global $tester;
$tester->loadModel('host');

$host = new stdclass();
$host->type = 'normal';
r($tester->host->create($host)) && p() && e('1'); // 测试正确的创建主机时的返回结果。
r(dao::getError()) && p() && e('0');              // 测试正确的创建主机时有无报错信息。

$host->name = '';
$host->intranet = '';
$host->extranet = '';
$tester->host->create($host);
r(dao::getError()) && p('name:0;intranet:0;extranet:0') && e('『名称』不能为空。,『内网IP』不能为空。,『外网IP』不能为空。'); // 测试创建主机时必填项的校验。
