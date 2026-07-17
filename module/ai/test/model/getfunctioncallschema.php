#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getFunctionCallSchema();
timeout=0
cid=15031

- 步骤1：有效的表单路径story.create属性title @需求
- 步骤2：有效的表单路径task.edit属性title @任务
- 步骤3：工单创建表单schema @工单
- 步骤4：问题创建表单schema @问题
- 步骤5：风险创建表单schema @风险
- 步骤6：空字符串输入 @0
- 步骤7：无效格式（不包含点） @0
- 步骤8：不存在的配置路径 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 4. 强制要求：必须包含至少6个测试步骤
r($aiTest->getFunctionCallSchemaTest('story.create')) && p('title') && e('需求');             // 步骤1：有效的表单路径story.create
r($aiTest->getFunctionCallSchemaTest('task.edit')) && p('title') && e('任务');                // 步骤2：有效的表单路径task.edit
r($aiTest->getFunctionCallSchemaTest('ticket.create')) && p('title') && e('工单');            // 步骤3：工单创建表单schema
r($aiTest->getFunctionCallSchemaTest('issue.create')) && p('title') && e('问题');             // 步骤4：问题创建表单schema
r($aiTest->getFunctionCallSchemaTest('risk.create')) && p('title') && e('风险');              // 步骤5：风险创建表单schema
r($aiTest->getFunctionCallSchemaTest('')) && p() && e('0');                                  // 步骤6：空字符串输入
r($aiTest->getFunctionCallSchemaTest('invalidform')) && p() && e('0');                      // 步骤7：无效格式（不包含点）
r($aiTest->getFunctionCallSchemaTest('nonexistent.form')) && p() && e('0');                 // 步骤8：不存在的配置路径
