#!/usr/bin/env php
<?php

/**

title=测试 cneModel->updateConfig();
timeout=0
cid=1

- 所有内容都为空 @1
- 错误的版本号属性message @请求集群接口失败
- 正确的版本号 @1
- 设置强制重启 @1
- 设置不强制重启 @1
- 设置更新配置片段 @1
- 设置更新域名 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cne.class.php';

zdTable('space')->config('space')->gen(2);
zdTable('solution')->config('solution')->gen(1);
zdTable('instance')->config('instance')->gen(2, true, false);

$cneModel = new cneTest();
$version  = null;
$restart  = null;
$snippets = null;
$maps     = null;

r($cneModel->updateConfigTest($version, $restart, $snippets, $maps)) && p() && e('1'); // 所有内容都为空

$version = '2023';
r($cneModel->updateConfigTest($version, $restart, $snippets, $maps)) && p('message') && e('请求集群接口失败'); // 错误的版本号

$version = '2023.12.1201';
r($cneModel->updateConfigTest($version, $restart, $snippets, $maps)) && p() && e('1'); // 正确的版本号

$restart = true;
r($cneModel->updateConfigTest($version, $restart, $snippets, $maps)) && p() && e('1'); // 设置强制重启

$restart = false;
r($cneModel->updateConfigTest($version, $restart, $snippets, $maps)) && p() && e('1'); // 设置不强制重启

$snippets = array();
r($cneModel->updateConfigTest($version, $restart, $snippets, $maps)) && p() && e('1'); // 设置更新配置片段

$maps = new stdclass;
$maps->minio = new stdclass;
$maps->minio->ingress = new stdclass;
$maps->minio->ingress->enabled = true;
$maps->minio->ingress->host    = 's3.dops.corp.cc';
r($cneModel->updateConfigTest($version, $restart, $snippets, $maps)) && p() && e('1'); // 设置更新域名