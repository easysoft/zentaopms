#!/usr/bin/env php
<?php

/**

title=测试 miscModel::checkOneClickPackage();
timeout=0
cid=17209

- 执行miscTest模块的checkOneClickPackageTest方法  @0
- 执行miscTest模块的checkOneClickPackageTest方法  @0
- 执行miscTest模块的checkOneClickPackageTest方法  @0
- 执行miscTest模块的checkOneClickPackageTest方法  @0
- 执行miscTest模块的checkOneClickPackageTest方法  @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$userTable = zenData('user');
$userTable->loadYaml('user_checkoneclickpackage', false, 2)->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$miscTest = new miscModelTest();

// 5. 备份原始配置
global $app;
$originalWebRoot = $app->config->webRoot;
$originalBasePath = $app->getBasePath();

// 测试步骤1：非一键安装包路径的情况
$app->config->webRoot = '/custom/';
r($miscTest->checkOneClickPackageTest()) && p() && e('0');

// 测试步骤2：一键安装包路径但webRoot不匹配
$app->config->webRoot = '/other/';
r($miscTest->checkOneClickPackageTest()) && p() && e('0');

// 测试步骤3：一键安装包路径且webRoot匹配zentao
$app->config->webRoot = '/zentao/';
r($miscTest->checkOneClickPackageTest()) && p() && e('0');

// 测试步骤4：一键安装包路径且webRoot匹配biz
$app->config->webRoot = '/biz/';
r($miscTest->checkOneClickPackageTest()) && p() && e('0');

// 测试步骤5：一键安装包路径且webRoot匹配max
$app->config->webRoot = '/max/';
r($miscTest->checkOneClickPackageTest()) && p() && e('0');

// 恢复原始配置
$app->config->webRoot = $originalWebRoot;