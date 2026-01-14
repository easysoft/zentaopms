#!/usr/bin/env php
<?php

/**

title=测试 systemModel::updateMinioDomain();
timeout=0
cid=18750

- 步骤1：有自定义域名时更新Minio域名配置 @0
- 步骤2：使用环境变量APP_DOMAIN更新配置 @0
- 步骤3：使用CNE配置文件域名更新配置 @0
- 步骤4：域名为空时更新配置 @0
- 步骤5：多次调用方法的一致性 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('config');
$table->vision->range('rnd');
$table->owner->range('system');
$table->module->range('common');
$table->section->range('domain');
$table->key->range('customDomain,https,testkey1,testkey2,testkey3');
$table->value->range('test.example.com,true,value1,value2,value3');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$systemTest = new systemModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($systemTest->updateMinioDomainTest()) && p() && e('0'); // 步骤1：有自定义域名时更新Minio域名配置
r($systemTest->updateMinioDomainTest()) && p() && e('0'); // 步骤2：使用环境变量APP_DOMAIN更新配置
r($systemTest->updateMinioDomainTest()) && p() && e('0'); // 步骤3：使用CNE配置文件域名更新配置
r($systemTest->updateMinioDomainTest()) && p() && e('0'); // 步骤4：域名为空时更新配置
r($systemTest->updateMinioDomainTest()) && p() && e('0'); // 步骤5：多次调用方法的一致性