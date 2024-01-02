#!/usr/bin/env php
<?php

/**

title=测试 cneModel->sysDomain();
timeout=0
cid=1

- 没有域名配置 @0
- 配置里设置过的域名 @config.zcorp.cc
- 环境变量里设置过的域名 @env.zcorp.cc
- 数据库里设置过的域名 @db.zcorp.cc

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester, $config;
$cneModel = $tester->loadModel('cne');

zdTable('config')->gen(0);
putenv('APP_DOMAIN=');
$config->CNE->app->domain = '';

r($cneModel->sysDomain()) && p() && e('0'); // 没有域名配置

$config->CNE->app->domain = 'config.zcorp.cc';
r($cneModel->sysDomain()) && p() && e('config.zcorp.cc'); // 配置里设置过的域名

putenv('APP_DOMAIN=env.zcorp.cc');
r($cneModel->sysDomain()) && p() && e('env.zcorp.cc'); // 环境变量里设置过的域名

$config = zdTable('config');
$config->owner->range('system');
$config->module->range('common');
$config->section->range('domain');
$config->key->range('customDomain');
$config->value->range('db.zcorp.cc');
$config->gen(1);
r($cneModel->sysDomain()) && p() && e('db.zcorp.cc'); // 数据库里设置过的域名