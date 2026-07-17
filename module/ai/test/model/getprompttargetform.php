#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getPromptTargetForm();
timeout=0
cid=0

- 表单位置优先使用 actionPurpose @story.change
- 非表单位置使用 targetForm @story.create
- 表单位置 actionPurpose 为空时回退 targetForm @story.create
- targetForm 为空时返回空字符串 @
- displayPosition 为空时使用 targetForm @bug.edit

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

$formPrompt = new stdClass();
$formPrompt->displayPosition = 'form';
$formPrompt->actionPurpose = 'story.change';
$formPrompt->targetForm = 'story.create';

$pagePrompt = new stdClass();
$pagePrompt->displayPosition = 'page';
$pagePrompt->actionPurpose = 'story.change';
$pagePrompt->targetForm = 'story.create';

$emptyPurposePrompt = new stdClass();
$emptyPurposePrompt->displayPosition = 'form';
$emptyPurposePrompt->actionPurpose = '';
$emptyPurposePrompt->targetForm = 'story.create';

$emptyPrompt = new stdClass();

$defaultPrompt = new stdClass();
$defaultPrompt->targetForm = 'bug.edit';

r($aiTest->getPromptTargetFormTest($formPrompt))         && p() && e('story.change');
r($aiTest->getPromptTargetFormTest($pagePrompt))         && p() && e('story.create');
r($aiTest->getPromptTargetFormTest($emptyPurposePrompt)) && p() && e('story.create');
r($aiTest->getPromptTargetFormTest($emptyPrompt) === '') && p() && e('1');
r($aiTest->getPromptTargetFormTest($defaultPrompt))      && p() && e('bug.edit');
