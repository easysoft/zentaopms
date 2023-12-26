#!/usr/bin/env php
<?php
/**

title=测试 hostModel->isClickable();
timeout=0
cid=1

- 测试ID为1的主机操作按钮权限。 @0
- 测试ID为1的主机操作按钮权限。 @1
- 测试ID为1的主机操作按钮权限。 @1
- 测试ID为1的主机操作按钮权限。 @1
- 测试ID为1的主机操作按钮权限。 @1
- 测试ID为2的主机操作按钮权限。 @1
- 测试ID为2的主机操作按钮权限。 @0
- 测试ID为2的主机操作按钮权限。 @1
- 测试ID为2的主机操作按钮权限。 @1
- 测试ID为2的主机操作按钮权限。 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('host')->config('host')->gen(30);
su('admin');

global $tester;
$tester->loadModel('host');

$host = $tester->host->fetchByID(1);
r($tester->host->isClickable($host, 'online'))  && p() && e(0); // 测试ID为1的主机操作按钮权限。
r($tester->host->isClickable($host, 'offline')) && p() && e(1); // 测试ID为1的主机操作按钮权限。
r($tester->host->isClickable($host, 'delete'))  && p() && e(1); // 测试ID为1的主机操作按钮权限。
r($tester->host->isClickable($host, 'edit'))    && p() && e(1); // 测试ID为1的主机操作按钮权限。
r($tester->host->isClickable($host, 'create'))  && p() && e(1); // 测试ID为1的主机操作按钮权限。

$host = $tester->host->fetchByID(2);
r($tester->host->isClickable($host, 'online'))  && p() && e(1); // 测试ID为2的主机操作按钮权限。
r($tester->host->isClickable($host, 'offline')) && p() && e(0); // 测试ID为2的主机操作按钮权限。
r($tester->host->isClickable($host, 'delete'))  && p() && e(1); // 测试ID为2的主机操作按钮权限。
r($tester->host->isClickable($host, 'edit'))    && p() && e(1); // 测试ID为2的主机操作按钮权限。
r($tester->host->isClickable($host, 'create'))  && p() && e(1); // 测试ID为2的主机操作按钮权限。
