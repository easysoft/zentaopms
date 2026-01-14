#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::fullDomain();
timeout=0
cid=16795

- 步骤1：正常域名拼接 @test.dops.corp.cc
- 步骤2：包含数字的域名 @app123.dops.corp.cc
- 步骤3：包含连字符的域名 @my-app.dops.corp.cc
- 步骤4：空字符串处理 @.dops.corp.cc
- 步骤5：单字符域名 @a.dops.corp.cc

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$configData = zenData('config');
$configData->owner->range('system');
$configData->module->range('common');
$configData->section->range('domain');
$configData->key->range('expiredDomain,customDomain');
$configData->value->range('`{"test":"dops.corp.cc"}`,dops.corp.cc');
$configData->gen(2);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($instanceTest->fullDomainTest('test')) && p('') && e('test.dops.corp.cc'); // 步骤1：正常域名拼接
r($instanceTest->fullDomainTest('app123')) && p('') && e('app123.dops.corp.cc'); // 步骤2：包含数字的域名
r($instanceTest->fullDomainTest('my-app')) && p('') && e('my-app.dops.corp.cc'); // 步骤3：包含连字符的域名
r($instanceTest->fullDomainTest('')) && p('') && e('.dops.corp.cc'); // 步骤4：空字符串处理
r($instanceTest->fullDomainTest('a')) && p('') && e('a.dops.corp.cc'); // 步骤5：单字符域名