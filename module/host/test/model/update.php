#!/usr/bin/env php
<?php
/**

title=测试 hostModel->update();
timeout=0
cid=16762

- 测试对象没有ID时候能否成功更新主机 @0
- 测试对象有ID时能否成功更新主机 @1
- 测试更新主机时必填项的校验。
 - 第name条的0属性 @『名称』不能为空。
 - 第intranet条的0属性 @『内网IP』不能为空。
 - 第extranet条的0属性 @『外网IP』不能为空。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('module')->loadYaml('module')->gen(100)->fixPath();
zenData('host')->loadYaml('host')->gen(30);
zenData('lang')->gen(0);
su('admin');

global $tester;
$tester->loadModel('host');

$host = new stdclass();
$host->name = '主机222';
r($tester->host->update($host)) && p() && e('0'); // 测试对象没有ID时候能否成功更新主机

$host->id = 22;
r($tester->host->update($host)) && p() && e('1'); // 测试对象有ID时能否成功更新主机

$host->name = '';
$host->intranet = '';
$host->extranet = '';
$tester->host->update($host);
r(dao::getError()) && p('name:0;intranet:0;extranet:0') && e('『名称』不能为空。,『内网IP』不能为空。,『外网IP』不能为空。'); // 测试更新主机时必填项的校验。
