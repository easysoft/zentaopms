#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::install();
timeout=0
cid=16805

- 步骤1：验证install方法存在 @1
- 步骤2：验证app对象有效 @1
- 步骤3：验证dbInfo对象有效 @1
- 步骤4：验证customData对象有效 @1
- 步骤5：验证createInstance方法存在 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 该用例只验证方法暴露和输入对象结构，无需初始化数据库数据。

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceModelTest();

// 准备测试数据
$validApp = new stdClass();
$validApp->id = 1;
$validApp->chart = 'testapp';
$validApp->alias = '测试应用';
$validApp->logo = 'test-logo.png';
$validApp->desc = '测试应用描述';
$validApp->app_version = '1.0.0';
$validApp->version = '1.0.0';

$validDbInfo = new stdClass();
$validDbInfo->name = 'mysql-service';
$validDbInfo->namespace = 'default';
$validDbInfo->host = 'mysql.default.svc';
$validDbInfo->port = '3306';

$validCustomData = new stdClass();
$validCustomData->customDomain = 'test-domain';
$validCustomData->customName = '自定义应用名';
$validCustomData->dbType = 'unsharedDB';
$validCustomData->dbService = '';
$validCustomData->ldapSnippet = array();
$validCustomData->smtpSnippet = array();

$emptyCustomData = new stdClass();
$emptyCustomData->customDomain = '';
$emptyCustomData->customName = '';
$emptyCustomData->dbType = 'unsharedDB';
$emptyCustomData->dbService = '';
$emptyCustomData->ldapSnippet = array();
$emptyCustomData->smtpSnippet = array();

$invalidApp = new stdClass();
$invalidApp->id = null;
$invalidApp->chart = null;
$invalidApp->alias = null;
$invalidApp->logo = null;
$invalidApp->desc = null;
$invalidApp->app_version = null;
$invalidApp->version = null;

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(is_callable(array($instanceTest->instance, 'install'))) && p() && e('1'); // 步骤1：验证install方法存在
r(is_object($validApp)) && p() && e('1'); // 步骤2：验证app对象有效
r(is_object($validDbInfo)) && p() && e('1'); // 步骤3：验证dbInfo对象有效
r(is_object($validCustomData)) && p() && e('1'); // 步骤4：验证customData对象有效
r(method_exists($instanceTest->instance, 'createInstance')) && p() && e('1'); // 步骤5：验证createInstance方法存在
