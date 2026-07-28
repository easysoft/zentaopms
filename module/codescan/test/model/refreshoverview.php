#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
include dirname(__FILE__) . '/common.php';

su('admin');

/**

title=测试 codescanModel->refreshOverview();
timeout=0
cid=0

- 使用默认 GitFox 地址刷新概况 @1
- 使用空端口刷新概况 @1
- 使用 HTTPS 地址刷新概况 @1
- 使用 127.0.0.1 地址刷新概况 @1
- 使用 443 端口刷新概况 @1

*/

$test = new codescanModelTest();
initCodescanGitFox($test);

global $config;

$config->devops->gitfoxURL = 'http://localhost';
$config->devops->gitfoxPort = 3000;
$test->instance->config->devops->gitfoxURL = 'http://localhost';
$test->instance->config->devops->gitfoxPort = 3000;
r($test->refreshOverviewTest()) && p() && e('1');

$config->devops->gitfoxURL = 'http://localhost';
$config->devops->gitfoxPort = 0;
$test->instance->config->devops->gitfoxURL = 'http://localhost';
$test->instance->config->devops->gitfoxPort = 0;
r($test->refreshOverviewTest()) && p() && e('1');

$config->devops->gitfoxURL = 'https://127.0.0.1';
$config->devops->gitfoxPort = 8443;
$test->instance->config->devops->gitfoxURL = 'https://127.0.0.1';
$test->instance->config->devops->gitfoxPort = 8443;
r($test->refreshOverviewTest()) && p() && e('1');

$config->devops->gitfoxURL = 'http://127.0.0.1';
$config->devops->gitfoxPort = 3001;
$test->instance->config->devops->gitfoxURL = 'http://127.0.0.1';
$test->instance->config->devops->gitfoxPort = 3001;
r($test->refreshOverviewTest()) && p() && e('1');

$config->devops->gitfoxURL = 'https://localhost';
$config->devops->gitfoxPort = 443;
$test->instance->config->devops->gitfoxURL = 'https://localhost';
$test->instance->config->devops->gitfoxPort = 443;
r($test->refreshOverviewTest()) && p() && e('1');
