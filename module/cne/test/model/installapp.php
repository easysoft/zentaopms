#!/usr/bin/env php
<?php

/**

title=测试 cneModel::installApp();
timeout=0
cid=0

- 执行cneTest模块的installAppTest方法，参数是$validParams 属性code @200
- 执行cneTest模块的installAppTest方法，参数是$emptyChannelParams 第data条的channel属性 @stable
- 执行cneTest模块的installAppTest方法，参数是$invalidParams 属性code @200
- 执行cneTest模块的installAppTest方法，参数是$incompleteParams 属性code @200
- 执行cneTest模块的installAppTest方法 第data条的name属性 @test-app

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

su('admin');

// 创建测试实例
$cneTest = new cneTest();

// 步骤1：测试正常的完整参数安装应用
$validParams = new stdclass();
$validParams->cluster = '';
$validParams->name = 'test-app';
$validParams->chart = 'zentao';
$validParams->namespace = 'test-namespace';
$validParams->channel = 'stable';
r($cneTest->installAppTest($validParams)) && p('code') && e('200');

// 步骤2：测试空channel参数使用默认channel
$emptyChannelParams = new stdclass();
$emptyChannelParams->cluster = '';
$emptyChannelParams->name = 'test-app-2';
$emptyChannelParams->chart = 'gitlab';
$emptyChannelParams->namespace = 'test-namespace-2';
$emptyChannelParams->channel = '';
r($cneTest->installAppTest($emptyChannelParams)) && p('data:channel') && e('stable');

// 步骤3：测试无效参数的处理情况
$invalidParams = new stdclass();
$invalidParams->cluster = '';
$invalidParams->name = 'invalid-app';
$invalidParams->chart = 'invalid-chart';
$invalidParams->namespace = 'invalid-namespace';
$invalidParams->channel = 'invalid-channel';
r($cneTest->installAppTest($invalidParams)) && p('code') && e('200');

// 步骤4：测试缺少必要参数的处理
$incompleteParams = new stdclass();
$incompleteParams->cluster = '';
$incompleteParams->name = 'incomplete-app';
r($cneTest->installAppTest($incompleteParams)) && p('code') && e('200');

// 步骤5：测试使用null参数的默认处理
r($cneTest->installAppTest()) && p('data:name') && e('test-app');