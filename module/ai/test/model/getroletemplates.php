#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getRoleTemplates();
timeout=0
cid=15048

- 步骤1：获取所有未删除角色模板，期望8个 @8
- 步骤2：验证第一个角色模板名称第1条的name属性 @系统分析师
- 步骤3：验证角色定位字段第1条的role属性 @你是一位经验丰富的系统分析师
- 步骤4：验证角色特征字段第1条的characterization属性 @具备丰富的业务分析经验，擅长需求挖掘和系统设计
- 步骤5：验证删除状态过滤第1条的deleted属性 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$table = zenData('ai_promptrole');
$table->id->range('1-10');
$table->name->range('系统分析师,项目经理,开发工程师,测试工程师,产品经理,技术专家,AI助手,客服代表,[],特殊角色');
$table->desc->range('专注于系统需求分析和架构设计,负责项目整体规划和管理,专注于代码开发和技术实现,负责软件质量保证和测试,负责产品规划和需求管理,提供技术咨询和解决方案,智能化辅助工具,客户服务支持,[],特殊角色描述');
$table->model->range('1,2,3,1,2,3,1,2,0,1');
$table->role->range('你是一位经验丰富的系统分析师,你是一位专业的项目经理,你是一位技术精湛的开发工程师,你是一位细致的测试工程师,你是一位有远见的产品经理,你是一位资深的技术专家,你是一位智能的AI助手,你是一位友善的客服代表,[],你是一位特殊角色');
$table->characterization->range('具备丰富的业务分析经验，擅长需求挖掘和系统设计,具有优秀的沟通协调能力，能够统筹项目进度,精通多种编程语言，代码质量高,具备敏锐的bug发现能力，测试覆盖全面,深刻理解用户需求，产品思维敏捷,技术功底深厚，解决方案创新,学习能力强，能够快速适应各种场景,服务意识强，耐心细致,[],特殊角色特征');
$table->deleted->range('0{8},1{2}');
$table->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$aiTest = new aiModelTest();

// 5. 测试步骤（必须包含至少5个测试步骤）
r(count($aiTest->getRoleTemplatesTest())) && p() && e('8'); // 步骤1：获取所有未删除角色模板，期望8个
r($aiTest->getRoleTemplatesTest()) && p('1:name') && e('系统分析师'); // 步骤2：验证第一个角色模板名称
r($aiTest->getRoleTemplatesTest()) && p('1:role') && e('你是一位经验丰富的系统分析师'); // 步骤3：验证角色定位字段
r($aiTest->getRoleTemplatesTest()) && p('1:characterization') && e('具备丰富的业务分析经验，擅长需求挖掘和系统设计'); // 步骤4：验证角色特征字段
r($aiTest->getRoleTemplatesTest()) && p('1:deleted') && e('0'); // 步骤5：验证删除状态过滤