#!/usr/bin/env php
<?php

/**

title=测试 adminModel::checkInternet();
timeout=0
cid=0

- 默认配置为存在的本地文件 @1
- 显式传入存在的本地文件 @1
- 显式传入不存在的本地文件 @0
- 默认配置为不存在的本地文件 @0
- 超时时间为0且目标为本地文件 @1
- 非法URL格式 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$existingFile = 'file://' . realpath(__FILE__);
$missingFile  = 'file://' . dirname(__FILE__) . '/checkinternet.not-exists';
$invalidURL   = 'http://:';

global $tester;
$tester->loadModel('admin');

$originApiSite = $tester->config->admin->apiSite;
$tester->config->admin->apiSite = $existingFile;

r($tester->admin->checkInternet())                     && p() && e('1'); // 默认配置为存在的本地文件
r($tester->admin->checkInternet($existingFile))        && p() && e('1'); // 显式传入存在的本地文件
r($tester->admin->checkInternet($missingFile))         && p() && e('0'); // 显式传入不存在的本地文件
$tester->config->admin->apiSite = $missingFile;
r($tester->admin->checkInternet())                     && p() && e('0'); // 默认配置为不存在的本地文件
r($tester->admin->checkInternet($existingFile, 0))     && p() && e('1'); // 超时时间为0且目标为本地文件
r($tester->admin->checkInternet($invalidURL))          && p() && e('0'); // 非法URL格式

$tester->config->admin->apiSite = $originApiSite;
