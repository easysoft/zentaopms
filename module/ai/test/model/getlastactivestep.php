#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getLastActiveStep();
timeout=0
cid=15036

- 步骤1：null参数 @basicinfo
- 步骤2：active状态 @preview
- 步骤3：仅配置 targetForm、未配置入口规则 @basicinfo
- 步骤4：基础信息完整 @setinputfields
- 步骤5：有source @setinputform
- 步骤6：有purpose @setprompt
- 步骤7：表单显示位置无需source @setinputform

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 4. 创建测试用的prompt对象
$promptNull = null;

$promptActive = new stdClass();
$promptActive->status = 'active';
$promptActive->name = 'Active prompt';
$promptActive->module = 'story';
$promptActive->actionPurpose = 'story.change';
$promptActive->displayPosition = 'detail';

$promptWithTargetForm = new stdClass();
$promptWithTargetForm->status = 'draft';
$promptWithTargetForm->targetForm = 'product.create';

$promptWithBasicInfo = new stdClass();
$promptWithBasicInfo->status = 'draft';
$promptWithBasicInfo->name = 'Basic prompt';
$promptWithBasicInfo->module = 'story';
$promptWithBasicInfo->actionPurpose = 'story.change';
$promptWithBasicInfo->displayPosition = 'detail';

$promptWithSource = new stdClass();
$promptWithSource->status = 'draft';
$promptWithSource->name = 'Source prompt';
$promptWithSource->module = 'story';
$promptWithSource->actionPurpose = 'story.change';
$promptWithSource->displayPosition = 'detail';
$promptWithSource->source = 'database';

$promptWithFormPosition = new stdClass();
$promptWithFormPosition->status = 'draft';
$promptWithFormPosition->name = 'Form prompt';
$promptWithFormPosition->module = 'story';
$promptWithFormPosition->actionPurpose = 'story.create';
$promptWithFormPosition->displayPosition = 'form';

$promptWithPurpose = new stdClass();
$promptWithPurpose->status = 'draft';
$promptWithPurpose->name = 'Purpose prompt';
$promptWithPurpose->module = 'story';
$promptWithPurpose->actionPurpose = 'story.change';
$promptWithPurpose->displayPosition = 'detail';
$promptWithPurpose->source = 'database';
$promptWithPurpose->purpose = 'Generate product ideas';

// 5. 强制要求：必须包含至少5个测试步骤
r($aiTest->getLastActiveStepTest($promptNull))            && p() && e('basicinfo');     // 步骤1：null参数
r($aiTest->getLastActiveStepTest($promptActive))          && p() && e('preview');       // 步骤2：active状态
r($aiTest->getLastActiveStepTest($promptWithTargetForm))  && p() && e('basicinfo');     // 步骤3：仅配置 targetForm、未配置入口规则
r($aiTest->getLastActiveStepTest($promptWithBasicInfo))   && p() && e('setinputfields'); // 步骤4：基础信息完整
r($aiTest->getLastActiveStepTest($promptWithSource))      && p() && e('setinputform');   // 步骤5：有source
r($aiTest->getLastActiveStepTest($promptWithPurpose))     && p() && e('setprompt');      // 步骤6：有purpose
r($aiTest->getLastActiveStepTest($promptWithFormPosition)) && p() && e('setinputform');   // 步骤7：表单显示位置无需source
