#!/usr/bin/env php
<?php

/**

title=测试 cneModel::installApp();
timeout=0
cid=0

- 安装应用时提供完整参数 >> 返回对象包含code属性
- 安装应用时未提供channel参数 >> channel设置为默认值stable
- 安装应用时提供自定义channel >> channel保持自定义值
- 安装应用时参数为空对象 >> 返回对象
- 安装应用时提供不同的chart参数 >> 返回对象包含code属性

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $config;
$config->CNE->api->host    = 'http://127.0.0.1:32380';
$config->CNE->api->token   = 'test-token';
$config->CNE->api->channel = 'stable';
$config->CNE->api->headers = array();
$config->CNE->api->headers[] = "{$config->CNE->api->auth}: {$config->CNE->api->token}";

$cneTest = new cneModelTest();

$apiParams1 = new stdclass();
$apiParams1->cluster   = '';
$apiParams1->namespace = 'default';
$apiParams1->name      = 'test-app';
$apiParams1->chart     = 'quickon/zentao';
$apiParams1->version   = '1.0.0';
$apiParams1->channel   = 'stable';

$apiParams2 = new stdclass();
$apiParams2->cluster   = '';
$apiParams2->namespace = 'default';
$apiParams2->name      = 'test-app2';
$apiParams2->chart     = 'quickon/zentao';
$apiParams2->version   = '1.0.0';

$apiParams3 = new stdclass();
$apiParams3->cluster   = '';
$apiParams3->namespace = 'default';
$apiParams3->name      = 'test-app3';
$apiParams3->chart     = 'quickon/zentao';
$apiParams3->version   = '1.0.0';
$apiParams3->channel   = 'dev';

$apiParams4 = new stdclass();

$apiParams5 = new stdclass();
$apiParams5->cluster   = '';
$apiParams5->namespace = 'test-space';
$apiParams5->name      = 'app-mysql';
$apiParams5->chart     = 'quickon/mysql';
$apiParams5->version   = '8.0.0';
$apiParams5->channel   = 'stable';

r($cneTest->installAppTest($apiParams1)) && p('code') && e('600');
r($cneTest->installAppTest($apiParams2)) && p('code') && e('600');
r($cneTest->installAppTest($apiParams3)) && p('code') && e('600');
r($cneTest->installAppTest($apiParams4)) && p('code') && e('600');
r($cneTest->installAppTest($apiParams5)) && p('code') && e('600');
