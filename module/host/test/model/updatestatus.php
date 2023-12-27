#!/usr/bin/env php
<?php
/**

title=测试 hostModel->updatestatus();
timeout=0
cid=1

- 测试对象没有ID时候能否成功上架主机 @0
- 测试对象有ID时能否成功上架主机 @1
- 执行host模块的fetchByID方法，参数是'22'
 - 属性id @22
 - 属性status @online
- 测试对象有ID时能否成功上架主机 @1
- 执行host模块的fetchByID方法，参数是'22'
 - 属性id @22
 - 属性status @offline

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('module')->config('module')->gen(100)->fixPath();
zdTable('host')->config('host')->gen(30);
zdTable('lang')->gen(0);
su('admin');

global $tester;
$tester->loadModel('host');

$host = new stdclass();
$host->status = 'online';
r($tester->host->updatestatus($host)) && p() && e('0'); // 测试对象没有ID时候能否成功上架主机

$host->id     = 22;
$host->reason = '上架原因';
r($tester->host->updatestatus($host)) && p() && e('1'); // 测试对象有ID时能否成功上架主机
r($tester->host->fetchByID('22')) && p('id,status') && e('22,online');

$host->status = 'offline';
$host->reason = '下架原因';
r($tester->host->updatestatus($host)) && p() && e('1'); // 测试对象有ID时能否成功上架主机
r($tester->host->fetchByID('22')) && p('id,status') && e('22,offline');
