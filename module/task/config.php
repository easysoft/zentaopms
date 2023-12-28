<?php
declare(strict_types=1);
helper::import(dirname(__FILE__) . 'config/form.php');

global $lang, $app;
$config->task = new stdclass();

$config->task->create      = new stdclass();
$config->task->edit        = new stdclass();
$config->task->start       = new stdclass();
$config->task->finish      = new stdclass();
$config->task->activate    = new stdclass();
$config->task->view        = new stdclass();
$config->task->batchcreate = new stdclass();
$config->task->batchedit   = new stdclass();

$config->task->create->requiredFields      = 'execution,name,type';
$config->task->batchcreate->requiredFields = 'name,type';
$config->task->edit->requiredFields        = $config->task->create->requiredFields;
$config->task->batchedit->requiredFields   = $config->task->edit->requiredFields;
$config->task->finish->requiredFields      = 'realStarted,finishedDate,currentConsumed';
$config->task->activate->requiredFields    = 'left';

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
    id, project, execution, module, story, fromBug,
    name, desc,
    type, pri,estStarted, realStarted, deadline, status,estimate, consumed, left,
    mailto, progress, mode,
    openedBy, openedDate, assignedTo, assignedDate,
    finishedBy, finishedDate, canceledBy, canceledDate,
    closedBy, closedDate, closedReason,
    lastEditedBy, lastEditedDate, activatedDate, files
    ';

$config->task->list = new stdclass();
$config->task->list->customCreateFields      = 'story,estStarted,deadline,mailto,pri,estimate';
$config->task->list->customBatchCreateFields = 'module,story,assignedTo,estimate,estStarted,deadline,desc,pri';
$config->task->list->customBatchEditFields   = 'module,assignedTo,status,pri,estimate,record,left,estStarted,deadline,finishedBy,canceledBy,closedBy,closedReason';

$config->task->defaultLoadCount = 50;

$config->task->custom = new stdclass();
$config->task->custom->createFields      = $config->task->list->customCreateFields;
$config->task->custom->batchCreateFields = 'module,story,assignedTo,estimate,estStarted,deadline,desc,pri';
$config->task->custom->batchEditFields   = 'module,assignedTo,status,pri,estimate,record,left';

$config->task->excludeCheckFields = ',pri,estStartedDitto,deadlineDitto,parent,regions,lanes,vision,region,';

$config->task->dateFields = array('assignedDate', 'finishedDate', 'canceledDate', 'closedDate', 'lastEditedDate', 'activatedDate', 'deadline', 'openedDate', 'realStarted', 'estStarted', 'estimateStartDate', 'actualStartDate', 'replacetypeDate');

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

$config->task->actionList['confirmStoryChange']['icon']  = 'search';
$config->task->actionList['confirmStoryChange']['hint']  = $lang->task->confirmStoryChange;
$config->task->actionList['confirmStoryChange']['url']   = helper::createLink('task', 'confirmStoryChange', 'taskID={id}');

$config->task->actionList['start']['icon']        = 'play';
$config->task->actionList['start']['hint']        = $lang->task->start;
$config->task->actionList['start']['text']        = $lang->task->start;
$config->task->actionList['start']['url']         = helper::createLink('task', 'start', 'taskID={id}');
$config->task->actionList['start']['data-toggle'] = 'modal';

$config->task->actionList['restart']['icon']        = 'restart';
$config->task->actionList['restart']['hint']        = $lang->task->restart;
$config->task->actionList['restart']['text']        = $lang->task->restart;
$config->task->actionList['restart']['url']         = helper::createLink('task', 'restart', 'taskID={id}');
$config->task->actionList['restart']['data-toggle'] = 'modal';

$config->task->actionList['finish']['icon']        = 'checked';
$config->task->actionList['finish']['hint']        = $lang->task->finish;
$config->task->actionList['finish']['text']        = $lang->task->finish;
$config->task->actionList['finish']['url']         = helper::createLink('task', 'finish', 'taskID={id}');
$config->task->actionList['finish']['data-toggle'] = 'modal';

$config->task->actionList['close']['icon']        = 'off';
$config->task->actionList['close']['hint']        = $lang->task->close;
$config->task->actionList['close']['text']        = $lang->task->close;
$config->task->actionList['close']['url']         = helper::createLink('task', 'close', 'taskID={id}');
$config->task->actionList['close']['data-toggle'] = 'modal';

$config->task->actionList['recordWorkhour']['icon']        = 'time';
$config->task->actionList['recordWorkhour']['hint']        = $lang->task->record;
$config->task->actionList['recordWorkhour']['text']        = $lang->task->record;
$config->task->actionList['recordWorkhour']['url']         = helper::createLink('task', 'recordWorkhour', 'taskID={id}');
$config->task->actionList['recordWorkhour']['data-toggle'] = 'modal';

$config->task->actionList['edit']['icon']     = 'edit';
$config->task->actionList['edit']['hint']     = $lang->task->edit;
$config->task->actionList['edit']['text']     = $lang->task->edit;
$config->task->actionList['edit']['url']      = helper::createLink('task', 'edit', 'taskID={id}');
$config->task->actionList['edit']['data-app'] = $app->tab;

if($config->vision != 'lite')
{
    $config->task->actionList['batchCreate']['icon']     = 'split';
    $config->task->actionList['batchCreate']['hint']     = $lang->task->batchCreate;
    $config->task->actionList['batchCreate']['text']     = $lang->task->batchCreate;
    $config->task->actionList['batchCreate']['url']      = helper::createLink('task', 'batchCreate', 'execution={execution}&storyID={story}&moduleID={module}&taskID={id}');
    $config->task->actionList['batchCreate']['data-app'] = $app->tab;
}

$config->task->actionList['create']['icon']     = 'copy';
$config->task->actionList['create']['hint']     = $lang->task->children;
$config->task->actionList['create']['text']     = $lang->task->children;
$config->task->actionList['create']['url']      = helper::createLink('task', 'create', 'projctID={execution}&storyID=0&moduleID=0&taskID={id}');
$config->task->actionList['create']['data-app'] = $app->tab;

$config->task->actionList['delete']['icon']         = 'trash';
$config->task->actionList['delete']['hint']         = $lang->task->delete;
$config->task->actionList['delete']['text']         = $lang->task->delete;
$config->task->actionList['delete']['url']          = helper::createLink('task', 'delete', 'executionID={execution}&taskID={id}');
$config->task->actionList['delete']['data-confirm'] = $lang->task->confirmDelete;
$config->task->actionList['delete']['class']        = 'ajax-submit';

$config->task->actionList['view']['icon'] = 'chevron-double-up';
$config->task->actionList['view']['hint'] = $lang->task->parent;
$config->task->actionList['view']['text'] = $lang->task->parent;
$config->task->actionList['view']['url']  = helper::createLink('task', 'view', 'taskID={parent}');

$config->task->actionList['cancel']['icon']        = 'ban-circle';
$config->task->actionList['cancel']['hint']        = $lang->task->cancel;
$config->task->actionList['cancel']['text']        = $lang->task->cancel;
$config->task->actionList['cancel']['url']         = helper::createLink('task', 'cancel', 'taskID={id}');
$config->task->actionList['cancel']['data-toggle'] = 'modal';

$config->task->actionList['pause']['icon']        = 'pause';
$config->task->actionList['pause']['hint']        = $lang->task->pause;
$config->task->actionList['pause']['text']        = $lang->task->pause;
$config->task->actionList['pause']['url']         = helper::createLink('task', 'pause', 'taskID={id}');
$config->task->actionList['pause']['data-toggle'] = 'modal';

$config->task->actionList['activate']['icon']        = 'magic';
$config->task->actionList['activate']['hint']        = $lang->task->activate;
$config->task->actionList['activate']['text']        = $lang->task->activate;
$config->task->actionList['activate']['url']         = helper::createLink('task', 'activate', 'taskID={id}');
$config->task->actionList['activate']['data-toggle'] = 'modal';

$config->task->actionList['assignTo']['icon']        = 'hand-right';
$config->task->actionList['assignTo']['hint']        = $lang->task->assign;
$config->task->actionList['assignTo']['text']        = $lang->task->assign;
$config->task->actionList['assignTo']['url']         = helper::createLink('task', 'assignTo', 'execution={execution}&taskID={id}');
$config->task->actionList['assignTo']['data-toggle'] = 'modal';

$config->task->actionList['createBranch']['icon']        = 'treemap';
$config->task->actionList['createBranch']['hint']        = $lang->task->createBranch;
$config->task->actionList['createBranch']['text']        = $lang->task->createBranch;
$config->task->actionList['createBranch']['url']         = helper::createLink('repo', 'createBranch', 'taskID={id}&execution={execution}');
$config->task->actionList['createBranch']['data-toggle'] = 'modal';

$config->task->view->operateList['main']   = array('batchCreate', 'assignTo', 'start', 'restart', 'createBranch', 'recordWorkhour', 'pause', 'finish', 'activate', 'close', 'cancel');
$config->task->view->operateList['common'] = array('edit', 'create', 'delete', 'view');
