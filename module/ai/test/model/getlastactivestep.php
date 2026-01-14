#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getLastActiveStep();
timeout=0
cid=15036

- 步骤1：null参数 @assignrole
- 步骤2：active状态 @finalize
- 步骤3：有targetForm @settargetform
- 步骤4：有purpose @setpurpose
- 步骤5：有source @selectdatasource

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

$promptWithTargetForm = new stdClass();
$promptWithTargetForm->status = 'draft';
$promptWithTargetForm->targetForm = 'product.create';

$promptWithPurpose = new stdClass();
$promptWithPurpose->status = 'draft';
$promptWithPurpose->purpose = 'Generate product ideas';

$promptWithSource = new stdClass();
$promptWithSource->status = 'draft';
$promptWithSource->source = 'database';

// 5. 强制要求：必须包含至少5个测试步骤
r($aiTest->getLastActiveStepTest($promptNull)) && p() && e('assignrole'); // 步骤1：null参数
r($aiTest->getLastActiveStepTest($promptActive)) && p() && e('finalize'); // 步骤2：active状态
r($aiTest->getLastActiveStepTest($promptWithTargetForm)) && p() && e('settargetform'); // 步骤3：有targetForm
r($aiTest->getLastActiveStepTest($promptWithPurpose)) && p() && e('setpurpose'); // 步骤4：有purpose
r($aiTest->getLastActiveStepTest($promptWithSource)) && p() && e('selectdatasource'); // 步骤5：有source