#!/usr/bin/env php
<?php

/**

title=测试 aiModel::useLanguageModel();
timeout=0
cid=0

- 步骤1：测试有效启用模型 @1
- 步骤2：测试禁用模型回退到默认模型 @1
- 步骤3：测试不存在模型使用默认模型 @1
- 步骤4：测试空值使用默认模型 @1
- 步骤5：测试无可用模型情况 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. 手动插入测试数据避免zendata JSON问题
global $tester;
$tester->dao->delete()->from('zt_ai_model')->exec();
$tester->dao->insert('zt_ai_model')
    ->data([
        'id' => 1,
        'type' => 'chat',
        'vendor' => 'openai',
        'credentials' => '{"key":"test-key-1"}',
        'name' => 'GPT-4-Enabled',
        'desc' => 'Test enabled model',
        'createdBy' => 'admin',
        'createdDate' => '2024-01-01 00:00:00',
        'enabled' => 1,
        'deleted' => 0
    ])
    ->exec();
$tester->dao->insert('zt_ai_model')
    ->data([
        'id' => 2,
        'type' => 'chat',
        'vendor' => 'openai',
        'credentials' => '{"key":"test-key-2"}',
        'name' => 'GPT-4-Default',
        'desc' => 'Test default model',
        'createdBy' => 'admin',
        'createdDate' => '2024-01-01 01:00:00',
        'enabled' => 1,
        'deleted' => 0
    ])
    ->exec();
$tester->dao->insert('zt_ai_model')
    ->data([
        'id' => 5,
        'type' => 'completion',
        'vendor' => 'azure',
        'credentials' => '{"key":"test-key-5"}',
        'name' => 'Disabled-Model',
        'desc' => 'Test disabled model',
        'createdBy' => 'admin',
        'createdDate' => '2024-01-01 02:00:00',
        'enabled' => 0,
        'deleted' => 0
    ])
    ->exec();

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->useLanguageModelTest(1)) && p() && e('1'); // 步骤1：测试有效启用模型
r($aiTest->useLanguageModelTest(5)) && p() && e('1'); // 步骤2：测试禁用模型回退到默认模型
r($aiTest->useLanguageModelTest(999)) && p() && e('1'); // 步骤3：测试不存在模型使用默认模型
r($aiTest->useLanguageModelTest('')) && p() && e('1'); // 步骤4：测试空值使用默认模型

// 禁用所有模型来测试失败情况
$tester->dao->update('zt_ai_model')->set('enabled')->eq(0)->exec();
r($aiTest->useLanguageModelTest(null)) && p() && e('0'); // 步骤5：测试无可用模型情况