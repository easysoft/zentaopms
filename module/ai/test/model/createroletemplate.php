#!/usr/bin/env php
<?php

/**

title=测试 aiModel::createRoleTemplate();
timeout=0
cid=15014

- 步骤1：正常创建 @1
- 步骤2：空角色描述 @2
- 步骤3：空特征描述 @3
- 步骤4：全空参数 @4
- 步骤5：再次正常创建 @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('ai_promptrole');
$table->role->range('请你扮演一名资深的产品经理。{3}, 你是一名经验丰富的开发工程师。{3}');
$table->characterization->range('负责产品战略、设计、开发等。{3}, 精通多种编程语言和框架。{3}');
$table->deleted->range('0');
$table->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($aiTest->createRoleTemplateTest('请你扮演一名资深的产品经理。', '负责产品战略、设计、开发、数据分析、用户体验、团队管理、沟通协调等方面。')) && p() && e('1'); // 步骤1：正常创建
r($aiTest->createRoleTemplateTest('', '测试特征描述')) && p() && e('2'); // 步骤2：空角色描述
r($aiTest->createRoleTemplateTest('测试角色描述', '')) && p() && e('3'); // 步骤3：空特征描述
r($aiTest->createRoleTemplateTest('', '')) && p() && e('4'); // 步骤4：全空参数
r($aiTest->createRoleTemplateTest('你是一名经验丰富的开发工程师。', '精通多种编程语言和框架、熟悉前后端技术和架构。')) && p() && e('5'); // 步骤5：再次正常创建