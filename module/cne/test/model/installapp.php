#!/usr/bin/env php
<?php

/**

title=测试 cneModel::installApp();
timeout=0
cid=0

- 执行cneTest模块的installAppTest方法，参数是$validParams  @object
- 执行cneTest模块的installAppTest方法，参数是$emptyChannelParams  @object
- 执行cneTest模块的installAppTest方法，参数是$invalidParams  @object
- 执行cneTest模块的installAppTest方法，参数是$incompleteParams  @object
- 执行cneTest模块的installAppTest方法  @object

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

global $tester, $config;
$config->CNE->api->host   = 'http://devops.corp.cc:32380';
$config->CNE->api->token  = 'R09p3H5mU1JCg60NGPX94RVbGq31JVkF';
$config->CNE->app->domain = 'devops.corp.cc';
$config->CNE->api->channel = 'stable';

$cneTest = new cneTest();

// 步骤1：使用有效的完整参数测试安装应用
$validParams = new stdclass();
$validParams->cluster = '';
$validParams->name = 'test-app';
$validParams->chart = 'zentao';
$validParams->namespace = 'test-namespace';
$validParams->channel = 'stable';
r($cneTest->installAppTest($validParams)) && p() && e('object');

// 步骤2：使用空channel参数测试（应该使用默认channel）
$emptyChannelParams = new stdclass();
$emptyChannelParams->cluster = '';
$emptyChannelParams->name = 'test-app-2';
$emptyChannelParams->chart = 'gitlab';
$emptyChannelParams->namespace = 'test-namespace-2';
$emptyChannelParams->channel = '';
r($cneTest->installAppTest($emptyChannelParams)) && p() && e('object');

// 步骤3：使用无效参数测试
$invalidParams = new stdclass();
$invalidParams->cluster = '';
$invalidParams->name = 'invalid-app';
$invalidParams->chart = 'invalid-chart';
$invalidParams->namespace = 'invalid-namespace';
$invalidParams->channel = 'invalid-channel';
r($cneTest->installAppTest($invalidParams)) && p() && e('object');

// 步骤4：使用缺少必要参数的对象测试
$incompleteParams = new stdclass();
$incompleteParams->cluster = '';
$incompleteParams->name = 'incomplete-app';
r($cneTest->installAppTest($incompleteParams)) && p() && e('object');

// 步骤5：测试使用默认参数（传入null）
r($cneTest->installAppTest()) && p() && e('object');