<?php
declare(strict_types=1);
helper::import(dirname(__FILE__) . 'config/form.php');

global $lang, $app;
$config->task = new stdclass();

$config->task->create   = new stdclass();
$config->task->edit     = new stdclass();
$config->task->start    = new stdclass();
$config->task->finish   = new stdclass();
$config->task->activate = new stdclass();

$config->task->create->requiredFields   = 'execution,name,type';
$config->task->edit->requiredFields     = $config->task->create->requiredFields;
$config->task->finish->requiredFields   = 'realStarted,finishedDate,currentConsumed';
$config->task->activate->requiredFields = 'left';

/* Default value. */
$config->task->default  = new stdclass();
$config->task->default->pri = 3;

$config->task->unfinishedStatus = array('wait', 'doing', 'pause');

$config->task->editor = new stdclass();
$config->task->editor->create   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->task->editor->edit     = array('id' => 'desc,comment', 'tools' => 'simpleTools');
$config->task->editor->view     = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');
$config->task->editor->assignto = array('id' => 'comment', 'tools' => 'simpleTools');
$config->task->editor->start    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->task->editor->restart  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->task->editor->finish   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->task->editor->close    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->task->editor->activate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->task->editor->cancel   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->task->editor->pause    = array('id' => 'comment', 'tools' => 'simpleTools');

$config->task->removeFields = 'objectTypeList,productList,executionList,gitlabID,gitlabProjectID,product';
$config->task->exportFields = '
    id, execution, module, story, fromBug,
    name, desc,
    type, pri,estStarted, realStarted, deadline, status,estimate, consumed, left,
    mailto, progress, mode,
    openedBy, openedDate, assignedTo, assignedDate,
    finishedBy, finishedDate, canceledBy, canceledDate,
    closedBy, closedDate, closedReason,
    lastEditedBy, lastEditedDate, activatedDate, files
    ';

$config->task->customCreateFields      = 'story,estStarted,deadline,mailto,pri,estimate';
$config->task->customBatchCreateFields = 'module,story,assignedTo,estimate,estStarted,deadline,desc,pri';
$config->task->customBatchEditFields   = 'module,assignedTo,status,pri,estimate,record,left,estStarted,deadline,finishedBy,canceledBy,closedBy,closedReason';
$config->task->defaultLoadCount        = 50;

$config->task->custom = new stdclass();
$config->task->custom->createFields      = $config->task->customCreateFields;
$config->task->custom->batchCreateFields = 'module,story,assignedTo,estimate,estStarted,deadline,desc,pri';
$config->task->custom->batchEditFields   = 'module,assignedTo,status,pri,estimate,record,left';

$config->task->excludeCheckFileds = ',pri,estStartedDitto,deadlineDitto,parent,regions,lanes,vision,region,';

$config->task->create->template = new stdclass();
$config->task->create->template->module     = 0;
$config->task->create->template->mode       = '';
$config->task->create->template->assignedTo = '';
$config->task->create->template->name       = '';
$config->task->create->template->story      = 0;
$config->task->create->template->type       = '';
$config->task->create->template->pri        = 3;
$config->task->create->template->estimate   = '';
$config->task->create->template->desc       = '';
$config->task->create->template->estStarted = null;
$config->task->create->template->deadline   = null;
$config->task->create->template->mailto     = '';
$config->task->create->template->color      = '';

$config->task->modeOptions = array();
$config->task->modeOptions[] = array('text' => $lang->task->modeList['linear'], 'value' => 'linear');
$config->task->modeOptions[] = array('text' => $lang->task->modeList['multi'], 'value' => 'multi');

$config->task->afterOptions = array();
$config->task->afterOptions[] = array('text' => $lang->task->afterChoices['continueAdding'], 'value' => 'continueAdding');
$config->task->afterOptions[] = array('text' => $lang->task->afterChoices['toTaskList'], 'value' => 'toTaskList', 'checked' => true);
$config->task->afterOptions[] = array('text' => $lang->task->afterChoices['toStoryList'], 'value' => 'toStoryList');
