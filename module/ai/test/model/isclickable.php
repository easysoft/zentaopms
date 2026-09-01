#!/usr/bin/env php
<?php

/**

title=测试 aiModel::isClickable();
timeout=0
cid=15055

- 步骤1：正常情况，模型启用动作，对象disabled状态 @1
- 步骤2：边界值，模型启用动作，对象已enabled状态 @0
- 步骤3：异常输入，空对象和空动作 @0
- 步骤4：权限验证，助手发布动作，对象disabled状态 @1
- 步骤5：业务规则，助手撤回动作，对象enabled状态 @1
- 步骤6：助手发布动作，对象已enabled状态 @0
- 步骤7：助手撤回动作，对象已disabled状态 @0
- 步骤8：助手编辑动作，对象已enabled状态 @0
- 步骤9：助手编辑动作，对象disabled状态 @1
- 步骤10：未知动作，返回默认true @1
- 步骤11：已发布但配置不完整的智能体允许下架 @1
- 步骤12：未发布定时智能体可点击设计 timerbasicinfo @1
- 步骤13：已发布定时智能体不可点击设计 timerbasicinfo @0
- 步骤14：未发布普通智能体可点击设计 promptbasicinfo @1
- 步骤15：已发布普通智能体不可点击设计 promptbasicinfo @0
- 步骤16：未发布定时智能体仍可按 promptbasicinfo 判断可点 @1
- 步骤17：未发布定时智能体不可点击调试 promptaudit @0
- 步骤18：已发布定时智能体不可点击调试 promptaudit @0
- 步骤19：未发布普通智能体可点击调试 promptaudit @1
- 步骤20：已发布普通智能体不可点击调试 promptaudit @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app;
$app->rawMethod = 'models';
// 2. 用户登录（选择合适角色）
$zd_company = zenData('company');
$zd_company->id->range('1');
$zd_company->name->range('禅道软件');
$zd_company->admins->range('admin');
$zd_company->guest->range('0');
$zd_company->gen(1);
$dbh->exec("UPDATE zt_company SET admins = ',admin,' WHERE id = 1");
global $app;
$app->company = $dbh->query('SELECT * FROM zt_company WHERE id = 1')->fetch(PDO::FETCH_OBJ);

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('admin');
$zd_user->realname->range('admin');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 4. 准备测试对象数据
$enabledObject = new stdClass();
$enabledObject->enabled = '1';

$disabledObject = new stdClass();
$disabledObject->enabled = '0';

$emptyObject = null;

// 5. 强制要求：必须包含至少5个测试步骤
r($aiTest->isClickableTest($disabledObject, 'modelenable'))       && p() && e('1'); // 步骤1：正常情况，模型启用动作，对象disabled状态
r($aiTest->isClickableTest($enabledObject, 'modelenable'))        && p() && e('0'); // 步骤2：边界值，模型启用动作，对象已enabled状态
r($aiTest->isClickableTest($emptyObject, ''))                     && p() && e('0'); // 步骤3：异常输入，空对象和空动作
r($aiTest->isClickableTest($disabledObject, 'assistantpublish'))  && p() && e('1'); // 步骤4：权限验证，助手发布动作，对象disabled状态
r($aiTest->isClickableTest($enabledObject, 'assistantwithdraw'))  && p() && e('1'); // 步骤5：业务规则，助手撤回动作，对象enabled状态
r($aiTest->isClickableTest($enabledObject, 'assistantpublish'))   && p() && e('0'); // 步骤6：助手发布动作，对象已enabled状态
r($aiTest->isClickableTest($disabledObject, 'assistantwithdraw')) && p() && e('0'); // 步骤7：助手撤回动作，对象已disabled状态
r($aiTest->isClickableTest($enabledObject, 'assistantedit'))      && p() && e('0'); // 步骤8：助手编辑动作，对象已enabled状态
r($aiTest->isClickableTest($disabledObject, 'assistantedit'))     && p() && e('1'); // 步骤9：助手编辑动作，对象disabled状态
r($aiTest->isClickableTest($enabledObject, 'unknownaction'))      && p() && e('1'); // 步骤10：未知动作，返回默认true

$app->rawMethod = 'prompts';
$publishedIncompletePrompt = new stdClass();
$publishedIncompletePrompt->status          = 'active';
$publishedIncompletePrompt->type            = '';
$publishedIncompletePrompt->displayPosition = 'detail';
$publishedIncompletePrompt->name            = 'prompt';
$publishedIncompletePrompt->module          = 'story';
$publishedIncompletePrompt->purpose         = '';
$publishedIncompletePrompt->actionPurpose   = 'empty.empty';

r($aiTest->isClickableTest($publishedIncompletePrompt, 'promptunpublish')) && p() && e('1'); // 步骤11：已发布但配置不完整的智能体允许下架

$draftTimerPrompt = new stdClass();
$draftTimerPrompt->status = 'draft';
$draftTimerPrompt->type   = 'timer';
$draftTimerPrompt->name   = 'timer';
$draftTimerPrompt->module = 'project';
$draftTimerPrompt->purpose = 'purpose';
$draftTimerPrompt->operation = 'notify';
$draftTimerPrompt->cycleType = 'day';

$publishedTimerPrompt = clone $draftTimerPrompt;
$publishedTimerPrompt->status = 'active';

$draftNormalPrompt = new stdClass();
$draftNormalPrompt->status          = 'draft';
$draftNormalPrompt->type            = '';
$draftNormalPrompt->name            = 'prompt';
$draftNormalPrompt->module          = 'story';
$draftNormalPrompt->purpose         = 'purpose';
$draftNormalPrompt->actionPurpose   = 'story.edit';
$draftNormalPrompt->displayPosition = 'detail';

$publishedNormalPrompt = clone $draftNormalPrompt;
$publishedNormalPrompt->status = 'active';

r($aiTest->isClickableTest($draftTimerPrompt, 'timerbasicinfo'))      && p() && e('1'); // 步骤12：未发布定时智能体可点击设计 timerbasicinfo
r($aiTest->isClickableTest($publishedTimerPrompt, 'timerbasicinfo'))  && p() && e('0'); // 步骤13：已发布定时智能体不可点击设计 timerbasicinfo
r($aiTest->isClickableTest($draftNormalPrompt, 'promptbasicinfo'))    && p() && e('1'); // 步骤14：未发布普通智能体可点击设计 promptbasicinfo
r($aiTest->isClickableTest($publishedNormalPrompt, 'promptbasicinfo')) && p() && e('0'); // 步骤15：已发布普通智能体不可点击设计 promptbasicinfo
r($aiTest->isClickableTest($draftTimerPrompt, 'promptbasicinfo'))     && p() && e('1'); // 步骤16：未发布定时智能体仍可按 promptbasicinfo 判断可点
r($aiTest->isClickableTest($draftTimerPrompt, 'promptaudit'))         && p() && e('0'); // 步骤17：未发布定时智能体不可点击调试 promptaudit
r($aiTest->isClickableTest($publishedTimerPrompt, 'promptaudit'))     && p() && e('0'); // 步骤18：已发布定时智能体不可点击调试 promptaudit
r($aiTest->isClickableTest($draftNormalPrompt, 'promptaudit'))        && p() && e('1'); // 步骤19：未发布普通智能体可点击调试 promptaudit
r($aiTest->isClickableTest($publishedNormalPrompt, 'promptaudit'))    && p() && e('0'); // 步骤20：已发布普通智能体不可点击调试 promptaudit
