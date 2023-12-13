<?php
global $lang,$config, $app;
$app->loadLang('todo');
$app->loadLang('score');
$app->loadLang('task');
$app->loadLang('story');
$app->loadLang('bug');
$app->loadLang('doc');
$app->loadLang('testtask');
$app->loadLang('testcase');
$app->loadLang('product');
$app->loadModuleConfig('testtask');
$app->loadModuleConfig('testcase');
$app->loadModuleConfig('company');

$config->my->todo = new stdclass();
$config->my->todo->actionList = array();
$config->my->todo->actionList['start']['icon'] = 'play';
$config->my->todo->actionList['start']['text'] = $lang->todo->start;
$config->my->todo->actionList['start']['hint'] = $lang->todo->start;
$config->my->todo->actionList['start']['url']  = array('module' => 'todo', 'method' => 'start', 'params' => 'todoID={id}');

$config->my->todo->actionList['activate']['icon'] = 'magic';
$config->my->todo->actionList['activate']['text'] = $lang->todo->activate;
$config->my->todo->actionList['activate']['hint'] = $lang->todo->activate;
$config->my->todo->actionList['activate']['url']  = array('module' => 'todo', 'method' => 'activate', 'params' => 'todoID={id}');

$config->my->todo->actionList['close']['icon'] = 'off';
$config->my->todo->actionList['close']['text'] = $lang->todo->close;
$config->my->todo->actionList['close']['hint'] = $lang->todo->close;
$config->my->todo->actionList['close']['url']  = array('module' => 'todo', 'method' => 'close', 'params' => 'todoID={id}');

$config->my->todo->actionList['assignTo']['icon']        = 'hand-right';
$config->my->todo->actionList['assignTo']['text']        = $lang->todo->assignedTo;
$config->my->todo->actionList['assignTo']['hint']        = $lang->todo->assignedTo;
$config->my->todo->actionList['assignTo']['url']         = array('module' => 'todo', 'method' => 'assignTo', 'params' => 'todoID={id}');
$config->my->todo->actionList['assignTo']['data-toggle'] = 'modal';

$config->my->todo->actionList['finish']['icon'] = 'checked';
$config->my->todo->actionList['finish']['text'] = $lang->todo->finish;
$config->my->todo->actionList['finish']['hint'] = $lang->todo->finish;
$config->my->todo->actionList['finish']['url']  = array('module' => 'todo', 'method' => 'finish', 'params' => 'todoID={id}');

$config->my->todo->actionList['edit']['icon']        = 'edit';
$config->my->todo->actionList['edit']['text']        = $lang->todo->edit;
$config->my->todo->actionList['edit']['hint']        = $lang->todo->edit;
$config->my->todo->actionList['edit']['url']         = array('module' => 'todo', 'method' => 'edit', 'params' => 'todoID={id}');
$config->my->todo->actionList['edit']['data-toggle'] = 'modal';

$config->my->todo->actionList['delete']['icon'] = 'trash';
$config->my->todo->actionList['delete']['text'] = $lang->todo->delete;
$config->my->todo->actionList['delete']['hint'] = $lang->todo->delete;
$config->my->todo->actionList['delete']['url']  = array('module' => 'todo', 'method' => 'delete', 'params' => 'todoID={id}&confirm=no');

$config->my->todo->dtable = new stdclass();
$config->my->todo->dtable->fieldList['id']['name']  = 'id';
$config->my->todo->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->todo->dtable->fieldList['id']['type']  = 'checkID';

$config->my->todo->dtable->fieldList['name']['name']        = 'name';
$config->my->todo->dtable->fieldList['name']['title']       = $lang->todo->name;
$config->my->todo->dtable->fieldList['name']['type']        = 'title';
$config->my->todo->dtable->fieldList['name']['link']        = array('module' => 'todo', 'method' => 'view', 'params' => 'id={id}&from=my', 'onlybody' => true);
$config->my->todo->dtable->fieldList['name']['data-toggle'] = 'modal';
$config->my->todo->dtable->fieldList['name']['data-size']   = 'lg';
$config->my->todo->dtable->fieldList['name']['fixed']       = 'left';

$config->my->todo->dtable->fieldList['pri']['name']  = 'pri';
$config->my->todo->dtable->fieldList['pri']['title'] = $lang->priAB;
$config->my->todo->dtable->fieldList['pri']['type']  = 'pri';
$config->my->todo->dtable->fieldList['pri']['group'] = 'pri';
$config->my->todo->dtable->fieldList['pri']['flex']  = 1;

$config->my->todo->dtable->fieldList['date']['name']  = 'date';
$config->my->todo->dtable->fieldList['date']['title'] = $lang->todo->date;
$config->my->todo->dtable->fieldList['date']['type']  = 'date';
$config->my->todo->dtable->fieldList['date']['group'] = 'date';
$config->my->todo->dtable->fieldList['date']['flex']  = 1;

$config->my->todo->dtable->fieldList['begin']['name']  = 'begin';
$config->my->todo->dtable->fieldList['begin']['title'] = $lang->todo->beginAB;
$config->my->todo->dtable->fieldList['begin']['type']  = 'time';
$config->my->todo->dtable->fieldList['begin']['group'] = 'date';
$config->my->todo->dtable->fieldList['begin']['flex']  = 1;

$config->my->todo->dtable->fieldList['end']['name']  = 'end';
$config->my->todo->dtable->fieldList['end']['title'] = $lang->todo->endAB;
$config->my->todo->dtable->fieldList['end']['type']  = 'time';
$config->my->todo->dtable->fieldList['end']['group'] = 'date';
$config->my->todo->dtable->fieldList['end']['flex']  = 1;

$config->my->todo->dtable->fieldList['status']['name']      = 'status';
$config->my->todo->dtable->fieldList['status']['title']     = $lang->todo->status;
$config->my->todo->dtable->fieldList['status']['type']      = 'status';
$config->my->todo->dtable->fieldList['status']['statusMap'] = $lang->todo->statusList;
$config->my->todo->dtable->fieldList['status']['group']     = 'status';
$config->my->todo->dtable->fieldList['status']['flex']      = 1;

$config->my->todo->dtable->fieldList['type']['name']  = 'type';
$config->my->todo->dtable->fieldList['type']['title'] = $lang->todo->type;
$config->my->todo->dtable->fieldList['type']['type']  = 'category';
$config->my->todo->dtable->fieldList['type']['map']   = $lang->todo->typeList;
$config->my->todo->dtable->fieldList['type']['group'] = 'status';
$config->my->todo->dtable->fieldList['type']['flex']  = 2;

$config->my->todo->dtable->fieldList['assignedBy']['name']  = 'assignedBy';
$config->my->todo->dtable->fieldList['assignedBy']['title'] = $lang->todo->assignedBy;
$config->my->todo->dtable->fieldList['assignedBy']['type']  = 'user';
$config->my->todo->dtable->fieldList['assignedBy']['width'] = 90;
$config->my->todo->dtable->fieldList['assignedBy']['group'] = 'assignedBy';
$config->my->todo->dtable->fieldList['assignedBy']['flex']  = 1;

$config->my->todo->dtable->fieldList['assignedTo']['name']  = 'assignedTo';
$config->my->todo->dtable->fieldList['assignedTo']['title'] = $lang->todo->assignedTo;
$config->my->todo->dtable->fieldList['assignedTo']['type']  = 'user';
$config->my->todo->dtable->fieldList['assignedTo']['group'] = 'assignedBy';
$config->my->todo->dtable->fieldList['assignedTo']['flex']  = 1;

$config->my->todo->dtable->fieldList['actions']['name']     = 'actions';
$config->my->todo->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->todo->dtable->fieldList['actions']['type']     = 'actions';
$config->my->todo->dtable->fieldList['actions']['sortType'] = false;
$config->my->todo->dtable->fieldList['actions']['list']     = $config->my->todo->actionList;
$config->my->todo->dtable->fieldList['actions']['menu']     = array('start', 'activate|assignTo', 'close|finish', 'edit', 'delete');

$config->my->score         = new stdclass();
$config->my->score->dtable = new stdclass();

$config->my->score->dtable->fieldList['time']['name']     = 'time';
$config->my->score->dtable->fieldList['time']['title']    = $lang->score->time;
$config->my->score->dtable->fieldList['time']['type']     = 'datetime';
$config->my->score->dtable->fieldList['time']['fixed']    = 'left';
$config->my->score->dtable->fieldList['time']['sortType'] = false;

$config->my->score->dtable->fieldList['module']['name']     = 'module';
$config->my->score->dtable->fieldList['module']['title']    = $lang->score->module;
$config->my->score->dtable->fieldList['module']['type']     = 'category';
$config->my->score->dtable->fieldList['module']['map']      = $lang->score->modules;
$config->my->score->dtable->fieldList['module']['sortType'] = false;

$config->my->score->dtable->fieldList['method']['name']     = 'method';
$config->my->score->dtable->fieldList['method']['title']    = $lang->score->method;
$config->my->score->dtable->fieldList['method']['type']     = 'text';
$config->my->score->dtable->fieldList['method']['map']      = $lang->score->methods;
$config->my->score->dtable->fieldList['method']['sortType'] = false;

$config->my->score->dtable->fieldList['before']['name']     = 'before';
$config->my->score->dtable->fieldList['before']['title']    = $lang->score->before;
$config->my->score->dtable->fieldList['before']['type']     = 'count';
$config->my->score->dtable->fieldList['before']['sortType'] = false;

$config->my->score->dtable->fieldList['score']['name']     = 'score';
$config->my->score->dtable->fieldList['score']['title']    = $lang->score->score;
$config->my->score->dtable->fieldList['score']['type']     = 'number';
$config->my->score->dtable->fieldList['score']['sortType'] = false;

$config->my->score->dtable->fieldList['after']['name']     = 'after';
$config->my->score->dtable->fieldList['after']['title']    = $lang->score->after;
$config->my->score->dtable->fieldList['after']['type']     = 'count';
$config->my->score->dtable->fieldList['after']['sortType'] = false;

$config->my->score->dtable->fieldList['desc']['name']  = 'desc';
$config->my->score->dtable->fieldList['desc']['title'] = $lang->score->desc;
$config->my->score->dtable->fieldList['desc']['type']  = 'desc';

$config->my->task = new stdclass();
$config->my->task->actionList = array();
$config->my->task->actionList['confirmStoryChange']['icon']      = 'search';
$config->my->task->actionList['confirmStoryChange']['text']      = $lang->task->confirmStoryChange;
$config->my->task->actionList['confirmStoryChange']['hint']      = $lang->task->confirmStoryChange;
$config->my->task->actionList['confirmStoryChange']['url']       = array('module' => 'task', 'method' => 'confirmStoryChange', 'params' => 'taskID={id}');
$config->my->task->actionList['confirmStoryChange']['className'] = 'ajax-submit';

$config->my->task->actionList['start']['icon']        = 'play';
$config->my->task->actionList['start']['text']        = $lang->task->start;
$config->my->task->actionList['start']['hint']        = $lang->task->start;
$config->my->task->actionList['start']['url']         = array('module' => 'task', 'method' => 'start', 'params' => 'taskID={id}');
$config->my->task->actionList['start']['data-toggle'] = 'modal';

$config->my->task->actionList['restart']['icon']        = 'play';
$config->my->task->actionList['restart']['text']        = $lang->task->restart;
$config->my->task->actionList['restart']['hint']        = $lang->task->restart;
$config->my->task->actionList['restart']['url']         = array('module' => 'task', 'method' => 'restart', 'params' => 'taskID={id}');
$config->my->task->actionList['restart']['data-toggle'] = 'modal';

$config->my->task->actionList['finish']['icon']        = 'checked';
$config->my->task->actionList['finish']['text']        = $lang->task->finish;
$config->my->task->actionList['finish']['hint']        = $lang->task->finish;
$config->my->task->actionList['finish']['url']         = array('module' => 'task', 'method' => 'finish', 'params' => 'taskID={id}');
$config->my->task->actionList['finish']['data-toggle'] = 'modal';

$config->my->task->actionList['close']['icon']        = 'off';
$config->my->task->actionList['close']['text']        = $lang->task->close;
$config->my->task->actionList['close']['hint']        = $lang->task->close;
$config->my->task->actionList['close']['url']         = array('module' => 'task', 'method' => 'close', 'params' => 'taskID={id}');
$config->my->task->actionList['close']['data-toggle'] = 'modal';

$config->my->task->actionList['record']['icon']          = 'time';
$config->my->task->actionList['record']['text']          = $lang->task->logEfforts;
$config->my->task->actionList['record']['hint']          = $lang->task->logEfforts;
$config->my->task->actionList['record']['url']           = array('module' => 'task', 'method' => 'recordWorkhour', 'params' => 'taskID={id}');
$config->my->task->actionList['record']['data-toggle']   = 'modal';
$config->my->task->actionList['record']['data-position'] = 'center';

$config->my->task->actionList['edit']['icon']          = 'edit';
$config->my->task->actionList['edit']['text']          = $lang->task->edit;
$config->my->task->actionList['edit']['hint']          = $lang->task->edit;
$config->my->task->actionList['edit']['url']           = array('module' => 'task', 'method' => 'edit', 'params' => 'taskID={id}');
$config->my->task->actionList['edit']['data-toggle']   = 'modal';
$config->my->task->actionList['edit']['data-size']     = 'lg';
$config->my->task->actionList['edit']['data-position'] = 'center';

if($config->vision != 'lite')
{
    $config->my->task->actionList['batchCreate']['icon']          = 'split';
    $config->my->task->actionList['batchCreate']['text']          = $lang->task->batchCreate;
    $config->my->task->actionList['batchCreate']['hint']          = $lang->task->batchCreate;
    $config->my->task->actionList['batchCreate']['url']           = array('module' => 'task', 'method' => 'batchCreate', 'params' => 'executionID={execution}&storyID={story}&moduleID={module}&taskID={id}iframe=true');
    $config->my->task->actionList['batchCreate']['data-toggle']   = 'modal';
    $config->my->task->actionList['batchCreate']['data-size']     = 'lg';
    $config->my->task->actionList['batchCreate']['data-position'] = 'center';
}

$config->my->task->dtable = new stdclass();
$config->my->task->dtable->fieldList['id']['name']     = 'id';
$config->my->task->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->task->dtable->fieldList['id']['type']     = 'checkID';
$config->my->task->dtable->fieldList['id']['sortType'] = true;

$config->my->task->dtable->fieldList['name']['name']         = 'name';
$config->my->task->dtable->fieldList['name']['title']        = $lang->task->name;
$config->my->task->dtable->fieldList['name']['type']         = 'title';
$config->my->task->dtable->fieldList['name']['nestedToggle'] = true;
$config->my->task->dtable->fieldList['name']['link']         = array('url' => array('module' => 'task', 'method' => 'view', 'params' => 'taskID={id}'), 'data-app' => 'execution');
$config->my->task->dtable->fieldList['name']['fixed']        = 'left';
$config->my->task->dtable->fieldList['name']['sortType']     = true;

$config->my->task->dtable->fieldList['pri']['name']     = 'pri';
$config->my->task->dtable->fieldList['pri']['title']    = $lang->priAB;
$config->my->task->dtable->fieldList['pri']['type']     = 'pri';
$config->my->task->dtable->fieldList['pri']['map']      = $lang->task->priList;
$config->my->task->dtable->fieldList['pri']['group']    = 'pri';
$config->my->task->dtable->fieldList['pri']['sortType'] = true;

$config->my->task->dtable->fieldList['status']['name']      = 'status';
$config->my->task->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->my->task->dtable->fieldList['status']['type']      = 'status';
$config->my->task->dtable->fieldList['status']['statusMap'] = $lang->task->statusList;
$config->my->task->dtable->fieldList['status']['group']     = 'pri';
$config->my->task->dtable->fieldList['status']['sortType']  = true;

$config->my->task->dtable->fieldList['project']['name']     = 'projectName';
$config->my->task->dtable->fieldList['project']['title']    = $lang->task->project;
$config->my->task->dtable->fieldList['project']['type']     = 'text';
$config->my->task->dtable->fieldList['project']['link']     = array('module' => 'project', 'method' => 'view', 'params' => 'projectID={project}');
$config->my->task->dtable->fieldList['project']['group']    = 'project';
$config->my->task->dtable->fieldList['project']['sortType'] = true;

$config->my->task->dtable->fieldList['execution']['name']     = 'executionName';
$config->my->task->dtable->fieldList['execution']['title']    = $lang->task->execution;
$config->my->task->dtable->fieldList['execution']['type']     = 'text';
$config->my->task->dtable->fieldList['execution']['link']     = array('module' => 'execution', 'method' => 'task', 'params' => 'executionID={execution}');
$config->my->task->dtable->fieldList['execution']['group']    = 'project';
$config->my->task->dtable->fieldList['execution']['sortType'] = true;

$config->my->task->dtable->fieldList['openedBy']['name']     = 'openedBy';
$config->my->task->dtable->fieldList['openedBy']['title']    = $lang->task->openedByAB;
$config->my->task->dtable->fieldList['openedBy']['type']     = 'user';
$config->my->task->dtable->fieldList['openedBy']['group']    = 'user';
$config->my->task->dtable->fieldList['openedBy']['sortType'] = true;

$config->my->task->dtable->fieldList['assignedTo']['name']     = 'assignedTo';
$config->my->task->dtable->fieldList['assignedTo']['title']    = $lang->task->assignedToAB;
$config->my->task->dtable->fieldList['assignedTo']['type']     = 'user';
$config->my->task->dtable->fieldList['assignedTo']['group']    = 'user';
$config->my->task->dtable->fieldList['assignedTo']['sortType'] = true;

$config->my->task->dtable->fieldList['finishedBy']['name']     = 'finishedBy';
$config->my->task->dtable->fieldList['finishedBy']['title']    = $lang->task->finishedByAB;
$config->my->task->dtable->fieldList['finishedBy']['type']     = 'user';
$config->my->task->dtable->fieldList['finishedBy']['group']    = 'user';
$config->my->task->dtable->fieldList['finishedBy']['sortType'] = true;

$config->my->task->dtable->fieldList['deadline']['name']     = 'deadline';
$config->my->task->dtable->fieldList['deadline']['title']    = $lang->task->deadlineAB;
$config->my->task->dtable->fieldList['deadline']['type']     = 'date';
$config->my->task->dtable->fieldList['deadline']['group']    = 'deadline';
$config->my->task->dtable->fieldList['deadline']['sortType'] = true;

$config->my->task->dtable->fieldList['estimate']['name']     = 'estimateLabel';
$config->my->task->dtable->fieldList['estimate']['title']    = $lang->task->estimateAB;
$config->my->task->dtable->fieldList['estimate']['type']     = 'number';
$config->my->task->dtable->fieldList['estimate']['group']    = 'deadline';
$config->my->task->dtable->fieldList['estimate']['sortType'] = true;

$config->my->task->dtable->fieldList['consumed']['name']     = 'consumedLabel';
$config->my->task->dtable->fieldList['consumed']['title']    = $lang->task->consumedAB;
$config->my->task->dtable->fieldList['consumed']['type']     = 'number';
$config->my->task->dtable->fieldList['consumed']['group']    = 'deadline';
$config->my->task->dtable->fieldList['consumed']['sortType'] = true;

$config->my->task->dtable->fieldList['left']['name']     = 'leftLabel';
$config->my->task->dtable->fieldList['left']['title']    = $lang->task->leftAB;
$config->my->task->dtable->fieldList['left']['type']     = 'number';
$config->my->task->dtable->fieldList['left']['group']    = 'deadline';
$config->my->task->dtable->fieldList['left']['sortType'] = true;

$config->my->task->dtable->fieldList['actions']['name']     = 'actions';
$config->my->task->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->task->dtable->fieldList['actions']['type']     = 'actions';
$config->my->task->dtable->fieldList['actions']['sortType'] = false;
$config->my->task->dtable->fieldList['actions']['list']     = $config->my->task->actionList;
$config->my->task->dtable->fieldList['actions']['menu']     = array(array('confirmStoryChange'), array('start|restart', 'finish', 'close', 'record', 'edit', 'batchCreate'));

$config->my->requirement = new stdclass();
$config->my->requirement->actionList = array();
$config->my->requirement->actionList['change']['icon']        = 'alter';
$config->my->requirement->actionList['change']['text']        = $lang->story->change;
$config->my->requirement->actionList['change']['hint']        = $lang->story->change;
$config->my->requirement->actionList['change']['url']         = array('module' => 'story', 'method' => 'change', 'params' => 'storyID={id}&from=&storyType=requirement');
$config->my->requirement->actionList['change']['data-toggle'] = 'modal';

$config->my->requirement->actionList['submitReview']['icon']        = 'confirm';
$config->my->requirement->actionList['submitReview']['text']        = $lang->story->submitReview;
$config->my->requirement->actionList['submitReview']['hint']        = $lang->story->submitReview;
$config->my->requirement->actionList['submitReview']['url']         = array('module' => 'story', 'method' => 'submitReview', 'params' => 'storyID={id}&storyType=requirement');
$config->my->requirement->actionList['submitReview']['data-toggle'] = 'modal';

$config->my->requirement->actionList['review']['icon']        = 'search';
$config->my->requirement->actionList['review']['text']        = $lang->story->review;
$config->my->requirement->actionList['review']['hint']        = $lang->story->review;
$config->my->requirement->actionList['review']['url']         = array('module' => 'story', 'method' => 'review', 'params' => 'storyID={id}&from=product&storyType=requirement');
$config->my->requirement->actionList['review']['data-toggle'] = 'modal';

$config->my->requirement->actionList['recall']['icon'] = 'undo';
$config->my->requirement->actionList['recall']['text'] = $lang->story->recall;
$config->my->requirement->actionList['recall']['hint'] = $lang->story->recall;
$config->my->requirement->actionList['recall']['url']  = array('module' => 'story', 'method' => 'recall', 'params' => 'storyID={id}&from=list&confirm=no&storyType=requirement');

$config->my->requirement->actionList['edit']['icon']        = 'edit';
$config->my->requirement->actionList['edit']['text']        = $lang->story->edit;
$config->my->requirement->actionList['edit']['hint']        = $lang->story->edit;
$config->my->requirement->actionList['edit']['url']         = array('module' => 'story', 'method' => 'edit', 'params' => 'storyID={id}&from=default&storyType=requirement');
$config->my->requirement->actionList['edit']['data-toggle'] = 'modal';

$config->my->requirement->actionList['close']['icon']        = 'off';
$config->my->requirement->actionList['close']['text']        = $lang->story->close;
$config->my->requirement->actionList['close']['hint']        = $lang->story->close;
$config->my->requirement->actionList['close']['url']         = array('module' => 'story', 'method' => 'close', 'params' => 'storyID={id}&from=&storyType=requirement');
$config->my->requirement->actionList['close']['data-toggle'] = 'modal';

$config->my->requirement->dtable = new stdclass();
$config->my->requirement->dtable->fieldList['id']['name']     = 'id';
$config->my->requirement->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->requirement->dtable->fieldList['id']['type']     = 'id';
$config->my->requirement->dtable->fieldList['id']['sortType'] = true;

$config->my->requirement->dtable->fieldList['title']['name']         = 'title';
$config->my->requirement->dtable->fieldList['title']['title']        = common::checkNotCN() ? $lang->URCommon . ' ' . $lang->my->name : $lang->URCommon . $lang->my->name;
$config->my->requirement->dtable->fieldList['title']['type']         = 'title';
$config->my->requirement->dtable->fieldList['title']['nestedToggle'] = true;
$config->my->requirement->dtable->fieldList['title']['link']         = array('module' => 'story', 'method' => 'view', 'params' => 'id={id}&version=0&param=0&storyType=requirement');
$config->my->requirement->dtable->fieldList['title']['fixed']        = 'left';
$config->my->requirement->dtable->fieldList['title']['sortType']     = true;

$config->my->requirement->dtable->fieldList['pri']['name']     = 'pri';
$config->my->requirement->dtable->fieldList['pri']['title']    = $lang->priAB;
$config->my->requirement->dtable->fieldList['pri']['type']     = 'pri';
$config->my->requirement->dtable->fieldList['pri']['group']    = 'pri';
$config->my->requirement->dtable->fieldList['pri']['sortType'] = true;

$config->my->requirement->dtable->fieldList['product']['name']     = 'productTitle';
$config->my->requirement->dtable->fieldList['product']['title']    = $lang->story->product;
$config->my->requirement->dtable->fieldList['product']['type']     = 'text';
$config->my->requirement->dtable->fieldList['product']['group']    = 'pri';
$config->my->requirement->dtable->fieldList['product']['sortType'] = true;

$config->my->requirement->dtable->fieldList['status']['name']      = 'status';
$config->my->requirement->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->my->requirement->dtable->fieldList['status']['type']      = 'status';
$config->my->requirement->dtable->fieldList['status']['statusMap'] = $lang->story->statusList;
$config->my->requirement->dtable->fieldList['status']['group']     = 'pri';
$config->my->requirement->dtable->fieldList['status']['sortType']  = true;

$config->my->requirement->dtable->fieldList['openedBy']['name']     = 'openedBy';
$config->my->requirement->dtable->fieldList['openedBy']['title']    = $lang->story->openedByAB;
$config->my->requirement->dtable->fieldList['openedBy']['type']     = 'user';
$config->my->requirement->dtable->fieldList['openedBy']['group']    = 'openedBy';
$config->my->requirement->dtable->fieldList['openedBy']['sortType'] = true;

$config->my->requirement->dtable->fieldList['estimate']['name']     = 'estimate';
$config->my->requirement->dtable->fieldList['estimate']['title']    = $lang->story->estimateAB;
$config->my->requirement->dtable->fieldList['estimate']['type']     = 'count';
$config->my->requirement->dtable->fieldList['estimate']['group']    = 'openedBy';
$config->my->requirement->dtable->fieldList['estimate']['sortType'] = true;

$config->my->requirement->dtable->fieldList['actions']['name']     = 'actions';
$config->my->requirement->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->requirement->dtable->fieldList['actions']['type']     = 'actions';
$config->my->requirement->dtable->fieldList['actions']['sortType'] = false;
$config->my->requirement->dtable->fieldList['actions']['list']     = $config->my->requirement->actionList;
$config->my->requirement->dtable->fieldList['actions']['menu']     = array('change', 'review|submitReview', 'recall', 'edit', 'close');

$config->my->story = new stdclass();
$config->my->story->actionList = array();
$config->my->story->actionList['change']['icon']        = 'alter';
$config->my->story->actionList['change']['text']        = $lang->story->change;
$config->my->story->actionList['change']['hint']        = $lang->story->change;
$config->my->story->actionList['change']['url']         = array('module' => 'story', 'method' => 'change', 'params' => 'storyID={id}');
$config->my->story->actionList['change']['data-toggle'] = 'modal';

$config->my->story->actionList['submitReview']['icon']        = 'confirm';
$config->my->story->actionList['submitReview']['text']        = $lang->story->submitReview;
$config->my->story->actionList['submitReview']['hint']        = $lang->story->submitReview;
$config->my->story->actionList['submitReview']['url']         = array('module' => 'story', 'method' => 'submitReview', 'params' => 'storyID={id}');
$config->my->story->actionList['submitReview']['data-toggle'] = 'modal';

$config->my->story->actionList['review']['icon']        = 'search';
$config->my->story->actionList['review']['text']        = $lang->story->review;
$config->my->story->actionList['review']['hint']        = $lang->story->review;
$config->my->story->actionList['review']['url']         = array('module' => 'story', 'method' => 'review', 'params' => 'storyID={id}');
$config->my->story->actionList['review']['data-toggle'] = 'modal';

$config->my->story->actionList['recall']['icon'] = 'undo';
$config->my->story->actionList['recall']['text'] = $lang->story->recall;
$config->my->story->actionList['recall']['hint'] = $lang->story->recall;
$config->my->story->actionList['recall']['url']  = array('module' => 'story', 'method' => 'recall', 'params' => 'storyID={id}');

$config->my->story->actionList['edit']['icon']        = 'edit';
$config->my->story->actionList['edit']['text']        = $lang->story->edit;
$config->my->story->actionList['edit']['hint']        = $lang->story->edit;
$config->my->story->actionList['edit']['url']         = array('module' => 'story', 'method' => 'edit', 'params' => 'storyID={id}');
$config->my->story->actionList['edit']['data-toggle'] = 'modal';
$config->my->story->actionList['edit']['data-size']   = 'lg';

$config->my->story->actionList['create']['icon']        = 'sitemap';
$config->my->story->actionList['create']['text']        = $lang->testcase->create;
$config->my->story->actionList['create']['hint']        = $lang->testcase->create;
$config->my->story->actionList['create']['url']         = array('module' => 'testcase', 'method' => 'create', 'params' => 'productID={product}&branch={branch}&module=0&from=&param=0&storyID={id}');
$config->my->story->actionList['create']['data-toggle'] = 'modal';

$config->my->story->actionList['close']['icon']        = 'off';
$config->my->story->actionList['close']['text']        = $lang->story->close;
$config->my->story->actionList['close']['hint']        = $lang->story->close;
$config->my->story->actionList['close']['url']         = array('module' => 'story', 'method' => 'close', 'params' => 'storyID={id}');
$config->my->story->actionList['close']['data-toggle'] = 'modal';

$config->my->story->dtable = new stdclass();
$config->my->story->dtable->fieldList['id']['name']     = 'id';
$config->my->story->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->story->dtable->fieldList['id']['type']     = 'id';
$config->my->story->dtable->fieldList['id']['sortType'] = true;

$config->my->story->dtable->fieldList['title']['name']         = 'title';
$config->my->story->dtable->fieldList['title']['title']        = common::checkNotCN() ? $lang->SRCommon . ' ' . $lang->my->name : $lang->SRCommon . $lang->my->name;
$config->my->story->dtable->fieldList['title']['type']         = 'title';
$config->my->story->dtable->fieldList['title']['nestedToggle'] = true;
$config->my->story->dtable->fieldList['title']['link']         = array('module' => 'story', 'method' => 'view', 'params' => 'id={id}');
$config->my->story->dtable->fieldList['title']['fixed']        = 'left';
$config->my->story->dtable->fieldList['title']['sortType']     = true;

$config->my->story->dtable->fieldList['pri']['name']     = 'pri';
$config->my->story->dtable->fieldList['pri']['title']    = $lang->priAB;
$config->my->story->dtable->fieldList['pri']['type']     = 'pri';
$config->my->story->dtable->fieldList['pri']['group']    = 'pri';
$config->my->story->dtable->fieldList['pri']['sortType'] = true;

$config->my->story->dtable->fieldList['product']['name']     = 'productTitle';
$config->my->story->dtable->fieldList['product']['title']    = $lang->story->product;
$config->my->story->dtable->fieldList['product']['type']     = 'text';
$config->my->story->dtable->fieldList['product']['group']    = 'product';
$config->my->story->dtable->fieldList['product']['sortType'] = true;

$config->my->story->dtable->fieldList['plan']['name']     = 'planTitle';
$config->my->story->dtable->fieldList['plan']['title']    = $lang->story->plan;
$config->my->story->dtable->fieldList['plan']['type']     = 'text';
$config->my->story->dtable->fieldList['plan']['group']    = 'product';
$config->my->story->dtable->fieldList['plan']['sortType'] = true;

$config->my->story->dtable->fieldList['status']['name']      = 'status';
$config->my->story->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->my->story->dtable->fieldList['status']['type']      = 'status';
$config->my->story->dtable->fieldList['status']['statusMap'] = $lang->story->statusList;
$config->my->story->dtable->fieldList['status']['group']     = 'product';
$config->my->story->dtable->fieldList['status']['sortType']  = true;

$config->my->story->dtable->fieldList['openedBy']['name']     = 'openedBy';
$config->my->story->dtable->fieldList['openedBy']['title']    = $lang->story->openedByAB;
$config->my->story->dtable->fieldList['openedBy']['type']     = 'user';
$config->my->story->dtable->fieldList['openedBy']['group']    = 'openedBy';
$config->my->story->dtable->fieldList['openedBy']['sortType'] = true;

$config->my->story->dtable->fieldList['estimate']['name']     = 'estimate';
$config->my->story->dtable->fieldList['estimate']['title']    = $lang->story->estimateAB;
$config->my->story->dtable->fieldList['estimate']['type']     = 'count';
$config->my->story->dtable->fieldList['estimate']['group']    = 'openedBy';
$config->my->story->dtable->fieldList['estimate']['sortType'] = true;

$config->my->story->dtable->fieldList['stage']['name']     = 'stage';
$config->my->story->dtable->fieldList['stage']['title']    = $lang->story->stageAB;
$config->my->story->dtable->fieldList['stage']['type']     = 'category';
$config->my->story->dtable->fieldList['stage']['map']      = $lang->story->stageList;
$config->my->story->dtable->fieldList['stage']['group']    = 'openedBy';
$config->my->story->dtable->fieldList['stage']['sortType'] = true;

$config->my->story->dtable->fieldList['actions']['name']     = 'actions';
$config->my->story->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->story->dtable->fieldList['actions']['type']     = 'actions';
$config->my->story->dtable->fieldList['actions']['sortType'] = false;
$config->my->story->dtable->fieldList['actions']['list']     = $config->my->story->actionList;
$config->my->story->dtable->fieldList['actions']['menu']     = array('change', 'review|submitReview', 'recall', 'edit', 'create', 'close');

$config->my->bug = new stdclass();
$config->my->bug->actionList = array();
$config->my->bug->actionList['confirm']['icon']        = 'ok';
$config->my->bug->actionList['confirm']['text']        = $lang->bug->abbr->confirmed;
$config->my->bug->actionList['confirm']['hint']        = $lang->bug->abbr->confirmed;
$config->my->bug->actionList['confirm']['url']         = helper::createLink('bug', 'confirm',"bugID={id}");
$config->my->bug->actionList['confirm']['data-toggle'] = 'modal';

$config->my->bug->actionList['resolve']['icon']        = 'checked';
$config->my->bug->actionList['resolve']['text']        = $lang->bug->resolve;
$config->my->bug->actionList['resolve']['hint']        = $lang->bug->resolve;
$config->my->bug->actionList['resolve']['url']         = helper::createLink('bug', 'resolve',"bugID={id}");
$config->my->bug->actionList['resolve']['data-toggle'] = 'modal';

$config->my->bug->actionList['close']['icon']        = 'off';
$config->my->bug->actionList['close']['text']        = $lang->bug->close;
$config->my->bug->actionList['close']['hint']        = $lang->bug->close;
$config->my->bug->actionList['close']['url']         = helper::createLink('bug', 'close',"bugID={id}");
$config->my->bug->actionList['close']['data-toggle'] = 'modal';

$config->my->bug->actionList['activate']['icon']        = 'magic';
$config->my->bug->actionList['activate']['text']        = $lang->bug->activate;
$config->my->bug->actionList['activate']['hint']        = $lang->bug->activate;
$config->my->bug->actionList['activate']['url']         = helper::createLink('bug', 'activate',"bugID={id}");
$config->my->bug->actionList['activate']['data-toggle'] = 'modal';

$config->my->bug->actionList['edit']['icon']        = 'edit';
$config->my->bug->actionList['edit']['text']        = $lang->bug->edit;
$config->my->bug->actionList['edit']['hint']        = $lang->bug->edit;
$config->my->bug->actionList['edit']['url']         = helper::createLink('bug', 'edit',"bugID={id}");
$config->my->bug->actionList['edit']['data-size']   = 'lg';
$config->my->bug->actionList['edit']['data-toggle'] = 'modal';

$config->my->bug->actionList['copy']['icon']        = 'copy';
$config->my->bug->actionList['copy']['text']        = $lang->bug->copy;
$config->my->bug->actionList['copy']['hint']        = $lang->bug->copy;
$config->my->bug->actionList['copy']['url']         = helper::createLink('bug', 'create',"productID={product}&branch={branch}&extra=bugID={id}");
$config->my->bug->actionList['copy']['data-size']   = 'lg';
$config->my->bug->actionList['copy']['data-toggle'] = 'modal';

$config->my->bug->dtable = new stdclass();
$config->my->bug->dtable->fieldList['id']['name']  = 'id';
$config->my->bug->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->bug->dtable->fieldList['id']['type']  = 'checkID';
$config->my->bug->dtable->fieldList['id']['group'] = 'left';

$config->my->bug->dtable->fieldList['title']['name']     = 'title';
$config->my->bug->dtable->fieldList['title']['title']    = $lang->bug->title;
$config->my->bug->dtable->fieldList['title']['type']     = 'title';
$config->my->bug->dtable->fieldList['title']['minWidth'] = '200';
$config->my->bug->dtable->fieldList['title']['fixed']    = 'left';
$config->my->bug->dtable->fieldList['title']['link']     = array('module' => 'bug', 'method' => 'view', 'params' => 'bugID={id}');
$config->my->bug->dtable->fieldList['title']['group']    = 'left';

$config->my->bug->dtable->fieldList['severity']['name']  = 'severity';
$config->my->bug->dtable->fieldList['severity']['title'] = $lang->bug->severity;
$config->my->bug->dtable->fieldList['severity']['type']  = 'severity';
$config->my->bug->dtable->fieldList['severity']['group'] = '2';

$config->my->bug->dtable->fieldList['pri']['name']  = 'pri';
$config->my->bug->dtable->fieldList['pri']['title'] = $lang->bug->pri;
$config->my->bug->dtable->fieldList['pri']['type']  = 'pri';
$config->my->bug->dtable->fieldList['pri']['group'] = '2';

$config->my->bug->dtable->fieldList['status']['name']      = 'status';
$config->my->bug->dtable->fieldList['status']['title']     = $lang->bug->abbr->status;
$config->my->bug->dtable->fieldList['status']['type']      = 'status';
$config->my->bug->dtable->fieldList['status']['statusMap'] = $lang->bug->statusList;
$config->my->bug->dtable->fieldList['status']['group']     = '2';

$config->my->bug->dtable->fieldList['type']['name']   = 'type';
$config->my->bug->dtable->fieldList['type']['title']  = $lang->bug->type;
$config->my->bug->dtable->fieldList['type']['type']   = 'category';
$config->my->bug->dtable->fieldList['type']['map']    = $lang->bug->typeList;
$config->my->bug->dtable->fieldList['type']['group']  = '2';
$config->my->bug->dtable->fieldList['type']['hidden'] = true;

$config->my->bug->dtable->fieldList['product']['name']  = 'productName';
$config->my->bug->dtable->fieldList['product']['title'] = $lang->bug->product;
$config->my->bug->dtable->fieldList['product']['type']  = 'text';
$config->my->bug->dtable->fieldList['product']['group'] = '3';

$config->my->bug->dtable->fieldList['project']['name']   = 'project';
$config->my->bug->dtable->fieldList['project']['title']  = $lang->bug->project;
$config->my->bug->dtable->fieldList['project']['type']   = 'text';
$config->my->bug->dtable->fieldList['project']['group']  = '3';
$config->my->bug->dtable->fieldList['project']['hidden'] = true;

$config->my->bug->dtable->fieldList['execution']['name']   = 'execution';
$config->my->bug->dtable->fieldList['execution']['title']  = $lang->bug->execution;
$config->my->bug->dtable->fieldList['execution']['type']   = 'text';
$config->my->bug->dtable->fieldList['execution']['group']  = '3';
$config->my->bug->dtable->fieldList['execution']['hidden'] = true;

$config->my->bug->dtable->fieldList['plan']['name']   = 'plan';
$config->my->bug->dtable->fieldList['plan']['title']  = $lang->bug->plan;
$config->my->bug->dtable->fieldList['plan']['width']  = 120;
$config->my->bug->dtable->fieldList['plan']['group']  = '3';
$config->my->bug->dtable->fieldList['plan']['hidden'] = true;

$config->my->bug->dtable->fieldList['openedBuild']['name']   = 'openedBuild';
$config->my->bug->dtable->fieldList['openedBuild']['title']  = $lang->bug->openedBuild;
$config->my->bug->dtable->fieldList['openedBuild']['type']   = 'text';
$config->my->bug->dtable->fieldList['openedBuild']['group']  = '3';
$config->my->bug->dtable->fieldList['openedBuild']['hidden'] = true;

$config->my->bug->dtable->fieldList['openedBy']['name']  = 'openedBy';
$config->my->bug->dtable->fieldList['openedBy']['title'] = $lang->bug->abbr->openedBy;
$config->my->bug->dtable->fieldList['openedBy']['type']  = 'user';
$config->my->bug->dtable->fieldList['openedBy']['group'] = '4';

$config->my->bug->dtable->fieldList['openedDate']['name']  = 'openedDate';
$config->my->bug->dtable->fieldList['openedDate']['title'] = $lang->bug->abbr->openedDate;
$config->my->bug->dtable->fieldList['openedDate']['type']  = 'date';
$config->my->bug->dtable->fieldList['openedDate']['group'] = '4';

$config->my->bug->dtable->fieldList['confirmed']['name']  = 'confirmed';
$config->my->bug->dtable->fieldList['confirmed']['title'] = $lang->bug->confirmed;
$config->my->bug->dtable->fieldList['confirmed']['type']  = 'category';
$config->my->bug->dtable->fieldList['confirmed']['map']   = $lang->bug->confirmedList;
$config->my->bug->dtable->fieldList['confirmed']['group'] = '5';

$config->my->bug->dtable->fieldList['assignedTo']['name']       = 'assignedTo';
$config->my->bug->dtable->fieldList['assignedTo']['title']      = $lang->bug->assignedTo;
$config->my->bug->dtable->fieldList['assignedTo']['type']       = 'assign';
$config->my->bug->dtable->fieldList['assignedTo']['assignLink'] = array('module' => 'bug', 'method' => 'assignTo', 'params' => 'bugID={id}');
$config->my->bug->dtable->fieldList['assignedTo']['group']      = '5';

$config->my->bug->dtable->fieldList['assignedDate']['name']   = 'assignedDate';
$config->my->bug->dtable->fieldList['assignedDate']['title']  = $lang->bug->assignedDate;
$config->my->bug->dtable->fieldList['assignedDate']['type']   = 'date';
$config->my->bug->dtable->fieldList['assignedDate']['group']  = '5';
$config->my->bug->dtable->fieldList['assignedDate']['hidden'] = true;

$config->my->bug->dtable->fieldList['deadline']['name']   = 'deadline';
$config->my->bug->dtable->fieldList['deadline']['title']  = $lang->bug->deadline;
$config->my->bug->dtable->fieldList['deadline']['type']   = 'date';
$config->my->bug->dtable->fieldList['deadline']['group']  = '5';
$config->my->bug->dtable->fieldList['deadline']['hidden'] = true;

$config->my->bug->dtable->fieldList['resolvedBy']['name']   = 'resolvedBy';
$config->my->bug->dtable->fieldList['resolvedBy']['title']  = $lang->bug->resolvedBy;
$config->my->bug->dtable->fieldList['resolvedBy']['type']   = 'user';
$config->my->bug->dtable->fieldList['resolvedBy']['group']  = '6';
$config->my->bug->dtable->fieldList['resolvedBy']['hidden'] = true;

$config->my->bug->dtable->fieldList['resolution']['name']  = 'resolution';
$config->my->bug->dtable->fieldList['resolution']['title'] = $lang->bug->resolution;
$config->my->bug->dtable->fieldList['resolution']['type']  = 'category';
$config->my->bug->dtable->fieldList['resolution']['map']   = $lang->bug->resolutionList;
$config->my->bug->dtable->fieldList['resolution']['group'] = '6';

$config->my->bug->dtable->fieldList['toTask']['name']   = 'toTaskName';
$config->my->bug->dtable->fieldList['toTask']['title']  = $lang->bug->toTask;
$config->my->bug->dtable->fieldList['toTask']['type']   = 'text';
$config->my->bug->dtable->fieldList['toTask']['link']   = array('module' => 'task', 'method' => 'view', 'params' => 'taskID={toTask}');
$config->my->bug->dtable->fieldList['toTask']['group']  = '6';
$config->my->bug->dtable->fieldList['toTask']['hidden'] = true;

$config->my->bug->dtable->fieldList['resolvedDate']['name']   = 'assignedDate';
$config->my->bug->dtable->fieldList['resolvedDate']['title']  = $lang->bug->abbr->resolvedDate;
$config->my->bug->dtable->fieldList['resolvedDate']['type']   = 'date';
$config->my->bug->dtable->fieldList['resolvedDate']['group']  = '6';
$config->my->bug->dtable->fieldList['resolvedDate']['hidden'] = true;

$config->my->bug->dtable->fieldList['resolvedBuild']['name']   = 'resolvedBuild';
$config->my->bug->dtable->fieldList['resolvedBuild']['title']  = $lang->bug->resolvedBuild;
$config->my->bug->dtable->fieldList['resolvedBuild']['type']   = 'text';
$config->my->bug->dtable->fieldList['resolvedBuild']['group']  = '6';
$config->my->bug->dtable->fieldList['resolvedBuild']['hidden'] = true;

$config->my->bug->dtable->fieldList['os']['name']   = 'os';
$config->my->bug->dtable->fieldList['os']['title']  = $lang->bug->os;
$config->my->bug->dtable->fieldList['os']['type']   = 'category';
$config->my->bug->dtable->fieldList['os']['map']    = $lang->bug->osList;
$config->my->bug->dtable->fieldList['os']['group']  = '7';
$config->my->bug->dtable->fieldList['os']['hidden'] = true;

$config->my->bug->dtable->fieldList['browser']['name']   = 'browser';
$config->my->bug->dtable->fieldList['browser']['title']  = $lang->bug->browser;
$config->my->bug->dtable->fieldList['browser']['type']   = 'category';
$config->my->bug->dtable->fieldList['browser']['map']    = $lang->bug->browserList;
$config->my->bug->dtable->fieldList['browser']['group']  = '7';
$config->my->bug->dtable->fieldList['browser']['hidden'] = true;

$config->my->bug->dtable->fieldList['activatedCount']['name']   = 'activatedCount';
$config->my->bug->dtable->fieldList['activatedCount']['title']  = $lang->bug->abbr->activatedCount;
$config->my->bug->dtable->fieldList['activatedCount']['type']   = 'count';
$config->my->bug->dtable->fieldList['activatedCount']['group']  = '8';
$config->my->bug->dtable->fieldList['activatedCount']['hidden'] = true;

$config->my->bug->dtable->fieldList['activatedDate']['name']   = 'activatedDate';
$config->my->bug->dtable->fieldList['activatedDate']['title']  = $lang->bug->activatedDate;
$config->my->bug->dtable->fieldList['activatedDate']['type']   = 'date';
$config->my->bug->dtable->fieldList['activatedDate']['group']  = '8';
$config->my->bug->dtable->fieldList['activatedDate']['hidden'] = true;

$config->my->bug->dtable->fieldList['story']['name']   = 'storyName';
$config->my->bug->dtable->fieldList['story']['title']  = $lang->bug->story;
$config->my->bug->dtable->fieldList['story']['type']   = 'text';
$config->my->bug->dtable->fieldList['story']['link']   = array('module' => 'story', 'method' => 'view', 'params' => 'storyID={story}');
$config->my->bug->dtable->fieldList['story']['group']  = '8';
$config->my->bug->dtable->fieldList['story']['hidden'] = true;

$config->my->bug->dtable->fieldList['task']['name']   = 'taskName';
$config->my->bug->dtable->fieldList['task']['title']  = $lang->bug->task;
$config->my->bug->dtable->fieldList['task']['type']   = 'text';
$config->my->bug->dtable->fieldList['task']['link']   = array('module' => 'task', 'method' => 'view', 'params' => 'taskID={task}');
$config->my->bug->dtable->fieldList['task']['group']  = '8';
$config->my->bug->dtable->fieldList['task']['hidden'] = true;

$config->my->bug->dtable->fieldList['mailto']['name']   = 'mailto';
$config->my->bug->dtable->fieldList['mailto']['title']  = $lang->bug->mailto;
$config->my->bug->dtable->fieldList['mailto']['type']   = 'user';
$config->my->bug->dtable->fieldList['mailto']['group']  = '9';
$config->my->bug->dtable->fieldList['mailto']['hidden'] = true;

$config->my->bug->dtable->fieldList['keywords']['name']  = 'keywords';
$config->my->bug->dtable->fieldList['keywords']['title'] = $lang->bug->keywords;
$config->my->bug->dtable->fieldList['keywords']['type']  = 'text';
$config->my->bug->dtable->fieldList['keywords']['group']  = '9';
$config->my->bug->dtable->fieldList['keywords']['hidden'] = true;

$config->my->bug->dtable->fieldList['lastEditedBy']['name']   = 'lastEditedBy';
$config->my->bug->dtable->fieldList['lastEditedBy']['title']  = $lang->bug->lastEditedBy;
$config->my->bug->dtable->fieldList['lastEditedBy']['type']   = 'user';
$config->my->bug->dtable->fieldList['lastEditedBy']['group']  = '10';
$config->my->bug->dtable->fieldList['lastEditedBy']['hidden'] = true;

$config->my->bug->dtable->fieldList['lastEditedDate']['name']   = 'lastEditedDate';
$config->my->bug->dtable->fieldList['lastEditedDate']['title']  = $lang->bug->abbr->lastEditedDate;
$config->my->bug->dtable->fieldList['lastEditedDate']['type']   = 'date';
$config->my->bug->dtable->fieldList['lastEditedDate']['group']  = '10';
$config->my->bug->dtable->fieldList['lastEditedDate']['hidden'] = true;

$config->my->bug->dtable->fieldList['closedBy']['name']   = 'closedBy';
$config->my->bug->dtable->fieldList['closedBy']['title']  = $lang->bug->closedBy;
$config->my->bug->dtable->fieldList['closedBy']['type']   = 'user';
$config->my->bug->dtable->fieldList['closedBy']['group']  = '10';
$config->my->bug->dtable->fieldList['closedBy']['hidden'] = true;

$config->my->bug->dtable->fieldList['closedDate']['name']   = 'closedDate';
$config->my->bug->dtable->fieldList['closedDate']['title']  = $lang->bug->closedDate;
$config->my->bug->dtable->fieldList['closedDate']['type']   = 'date';
$config->my->bug->dtable->fieldList['closedDate']['group']  = '10';
$config->my->bug->dtable->fieldList['closedDate']['hidden'] = true;

$config->my->bug->dtable->fieldList['module']['name']   = 'module';
$config->my->bug->dtable->fieldList['module']['title']  = $lang->bug->module;
$config->my->bug->dtable->fieldList['module']['type']   = 'text';
$config->my->bug->dtable->fieldList['module']['hidden'] = true;

$config->my->bug->dtable->fieldList['branch']['name']   = 'branch';
$config->my->bug->dtable->fieldList['branch']['title']  = $lang->bug->branch;
$config->my->bug->dtable->fieldList['branch']['type']   = 'text';
$config->my->bug->dtable->fieldList['branch']['hidden'] = true;

$config->my->bug->dtable->fieldList['actions']['name']     = 'actions';
$config->my->bug->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->bug->dtable->fieldList['actions']['type']     = 'actions';
$config->my->bug->dtable->fieldList['actions']['width']    = '140';
$config->my->bug->dtable->fieldList['actions']['sortType'] = false;
$config->my->bug->dtable->fieldList['actions']['fixed']    = 'right';
$config->my->bug->dtable->fieldList['actions']['list']     = $config->my->bug->actionList;
$config->my->bug->dtable->fieldList['actions']['menu']     = array('confirm', 'resolve', 'close|activate', 'edit', 'copy');

$config->my->testtask = new stdclass();
$config->my->testtask->dtable = new stdclass();
$config->my->testtask->dtable->fieldList['id']['name']     = 'id';
$config->my->testtask->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->testtask->dtable->fieldList['id']['type']     = 'id';
$config->my->testtask->dtable->fieldList['id']['sortType'] = true;

$config->my->testtask->dtable->fieldList['title']['name']     = 'name';
$config->my->testtask->dtable->fieldList['title']['title']    = $lang->testtask->name;
$config->my->testtask->dtable->fieldList['title']['type']     = 'title';
$config->my->testtask->dtable->fieldList['title']['link']     = array('module' => 'testtask', 'method' => 'cases', 'params' => 'taskID={id}');
$config->my->testtask->dtable->fieldList['title']['fixed']    = 'left';
$config->my->testtask->dtable->fieldList['title']['sortType'] = true;

$config->my->testtask->dtable->fieldList['build']['name']     = 'buildName';
$config->my->testtask->dtable->fieldList['build']['title']    = $lang->testtask->build;
$config->my->testtask->dtable->fieldList['build']['type']     = 'text';
$config->my->testtask->dtable->fieldList['build']['link']     = array('module' => 'build', 'method' => 'view', 'params' => 'buildID={build}');
$config->my->testtask->dtable->fieldList['build']['group']    = 'text';
$config->my->testtask->dtable->fieldList['build']['sortType'] = true;

$config->my->testtask->dtable->fieldList['execution']['name']     = 'executionName';
$config->my->testtask->dtable->fieldList['execution']['title']    = $lang->testtask->execution;
$config->my->testtask->dtable->fieldList['execution']['type']     = 'text';
$config->my->testtask->dtable->fieldList['execution']['group']    = 'text';
$config->my->testtask->dtable->fieldList['execution']['sortType'] = true;

$config->my->testtask->dtable->fieldList['status']['name']      = 'status';
$config->my->testtask->dtable->fieldList['status']['title']     = $lang->testtask->status;
$config->my->testtask->dtable->fieldList['status']['type']      = 'status';
$config->my->testtask->dtable->fieldList['status']['statusMap'] = $lang->testtask->statusList;
$config->my->testtask->dtable->fieldList['status']['group']     = 'text';
$config->my->testtask->dtable->fieldList['status']['sortType']  = true;

$config->my->testtask->dtable->fieldList['begin']['name']     = 'begin';
$config->my->testtask->dtable->fieldList['begin']['title']    = $lang->testtask->begin;
$config->my->testtask->dtable->fieldList['begin']['type']     = 'date';
$config->my->testtask->dtable->fieldList['begin']['group']    = 'user';
$config->my->testtask->dtable->fieldList['begin']['sortType'] = true;

$config->my->testtask->dtable->fieldList['end']['name']     = 'end';
$config->my->testtask->dtable->fieldList['end']['title']    = $lang->testtask->end;
$config->my->testtask->dtable->fieldList['end']['type']     = 'date';
$config->my->testtask->dtable->fieldList['end']['group']    = 'user';
$config->my->testtask->dtable->fieldList['end']['sortType'] = true;

$config->my->testtask->dtable->fieldList['actions']['name']     = 'actions';
$config->my->testtask->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->testtask->dtable->fieldList['actions']['type']     = 'actions';
$config->my->testtask->dtable->fieldList['actions']['sortType'] = false;
$config->my->testtask->dtable->fieldList['actions']['list']     = $config->testtask->actionList;
$config->my->testtask->dtable->fieldList['actions']['menu']     = array('cases', 'linkCase', 'report', 'view', 'edit', 'delete');

$config->my->testtask->dtable->fieldList['actions']['list']['edit']['data-toggle'] = 'modal';
$config->my->testtask->dtable->fieldList['actions']['list']['edit']['data-size']   = 'lg';

$config->my->testcase = new stdclass();
$config->my->testcase->dtable = new stdclass();
$config->my->testcase->dtable->fieldList['id']['name']     = 'id';
$config->my->testcase->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->testcase->dtable->fieldList['id']['type']     = 'checkID';
$config->my->testcase->dtable->fieldList['id']['fixed']    = 'left';
$config->my->testcase->dtable->fieldList['id']['sortType'] = true;

$config->my->testcase->dtable->fieldList['title']['name']     = 'title';
$config->my->testcase->dtable->fieldList['title']['title']    = $lang->testcase->title;
$config->my->testcase->dtable->fieldList['title']['type']     = 'title';
$config->my->testcase->dtable->fieldList['title']['link']     = array('module' => 'testcase', 'method' => 'view', 'params' => 'caseID={id}&version={version}');
$config->my->testcase->dtable->fieldList['title']['fixed']    = 'left';
$config->my->testcase->dtable->fieldList['title']['sortType'] = true;

$config->my->testcase->dtable->fieldList['pri']    = $config->testcase->dtable->fieldList['pri'];
$config->my->testcase->dtable->fieldList['type']   = $config->testcase->dtable->fieldList['type'];
$config->my->testcase->dtable->fieldList['status'] = $config->testcase->dtable->fieldList['status'];

$config->my->testcase->dtable->fieldList['testtask']['name']     = 'taskName';
$config->my->testcase->dtable->fieldList['testtask']['title']    = $lang->testtask->common;
$config->my->testcase->dtable->fieldList['testtask']['type']     = 'text';
$config->my->testcase->dtable->fieldList['testtask']['group']    = 'testtask';
$config->my->testcase->dtable->fieldList['testtask']['sortType'] = true;

$config->my->testcase->dtable->fieldList['openedBy']      = $config->testcase->dtable->fieldList['openedBy'];
$config->my->testcase->dtable->fieldList['lastRunner']    = $config->testcase->dtable->fieldList['lastRunner'];
$config->my->testcase->dtable->fieldList['lastRunDate']   = $config->testcase->dtable->fieldList['lastRunDate'];
$config->my->testcase->dtable->fieldList['lastRunResult'] = $config->testcase->dtable->fieldList['lastRunResult'];
$config->my->testcase->dtable->fieldList['actions']       = $config->testcase->dtable->fieldList['actions'];

$config->my->testcase->dtable->fieldList['actions']['list']['edit']['data-toggle']   = 'modal';
$config->my->testcase->dtable->fieldList['actions']['list']['edit']['data-size']     = 'lg';
$config->my->testcase->dtable->fieldList['actions']['list']['create']['data-toggle'] = 'modal';
$config->my->testcase->dtable->fieldList['actions']['list']['create']['data-size']   = 'lg';
$config->my->testcase->dtable->fieldList['actions']['menu'] = array('runCase', 'runResult', 'edit', 'createBug', 'create');

$config->my->audit = new stdclass();
$config->my->audit->actionList = array();
$config->my->audit->actionList['review']['icon']        = 'search';
$config->my->audit->actionList['review']['text']        = $lang->review->common;
$config->my->audit->actionList['review']['hint']        = $lang->review->common;
$config->my->audit->actionList['review']['url']         = array('module' => 'story', 'method' => 'review', 'params' => 'storyID={id}');
$config->my->audit->actionList['review']['data-toggle'] = 'modal';

$config->my->audit->dtable = new stdclass();
$config->my->audit->dtable->fieldList['id']['name']     = 'id';
$config->my->audit->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->audit->dtable->fieldList['id']['type']     = 'id';
$config->my->audit->dtable->fieldList['id']['sortType'] = true;

$config->my->audit->dtable->fieldList['title']['name']        = 'title';
$config->my->audit->dtable->fieldList['title']['title']       = $lang->my->auditField->title;
$config->my->audit->dtable->fieldList['title']['type']        = 'title';
$config->my->audit->dtable->fieldList['title']['fixed']       = 'left';
$config->my->audit->dtable->fieldList['title']['data-toggle'] = 'modal';
$config->my->audit->dtable->fieldList['title']['data-size']   = 'lg';
$config->my->audit->dtable->fieldList['title']['sortType']    = true;

$config->my->audit->dtable->fieldList['type']['name']     = 'type';
$config->my->audit->dtable->fieldList['type']['title']    = $lang->my->auditField->type;
$config->my->audit->dtable->fieldList['type']['type']     = 'catetory';
$config->my->audit->dtable->fieldList['type']['sortType'] = true;

$config->my->audit->dtable->fieldList['time']['name']     = 'time';
$config->my->audit->dtable->fieldList['time']['title']    = $lang->my->auditField->time;
$config->my->audit->dtable->fieldList['time']['type']     = 'datetime';
$config->my->audit->dtable->fieldList['time']['sortType'] = true;

$config->my->audit->dtable->fieldList['result']['name']     = 'result';
$config->my->audit->dtable->fieldList['result']['title']    = $lang->my->auditField->result;
$config->my->audit->dtable->fieldList['result']['type']     = 'text';
$config->my->audit->dtable->fieldList['result']['sortType'] = true;

$config->my->audit->dtable->fieldList['status']['name']     = 'status';
$config->my->audit->dtable->fieldList['status']['title']    = $lang->my->auditField->status;
$config->my->audit->dtable->fieldList['status']['type']     = 'status';
$config->my->audit->dtable->fieldList['status']['sortType'] = true;

$config->my->audit->dtable->fieldList['actions']['name']     = 'actions';
$config->my->audit->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->audit->dtable->fieldList['actions']['type']     = 'actions';
$config->my->audit->dtable->fieldList['actions']['sortType'] = false;
$config->my->audit->dtable->fieldList['actions']['fixed']    = 'right';
$config->my->audit->dtable->fieldList['actions']['list']     = $config->my->audit->actionList;
$config->my->audit->dtable->fieldList['actions']['menu']     = array('review');

$app->loadLang('execution');
$config->my->execution = new stdclass();
$config->my->execution->dtable = new stdclass();
$config->my->execution->dtable->fieldList['id']['name']  = 'id';
$config->my->execution->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->execution->dtable->fieldList['id']['type']  = 'id';
$config->my->execution->dtable->fieldList['id']['group'] = '1';

$config->my->execution->dtable->fieldList['name']['name']  = 'name';
$config->my->execution->dtable->fieldList['name']['title'] = $lang->execution->name;
$config->my->execution->dtable->fieldList['name']['type']  = 'title';
$config->my->execution->dtable->fieldList['name']['link']  = 'RAWJS<function(info){ if(info.row.data.isParent) return false; else return \'' . helper::createLink('execution', 'browse', 'id={id}&from=my') . '\'; }>RAWJS';
$config->my->execution->dtable->fieldList['name']['fixed'] = 'left';
$config->my->execution->dtable->fieldList['name']['group'] = '1';

$config->my->execution->dtable->fieldList['code']['name']   = 'code';
$config->my->execution->dtable->fieldList['code']['title']  = $lang->execution->code;
$config->my->execution->dtable->fieldList['code']['type']   = 'text';
$config->my->execution->dtable->fieldList['code']['fixed']  = 'left';
$config->my->execution->dtable->fieldList['code']['group']  = '1';
$config->my->execution->dtable->fieldList['code']['hidden'] = true;

$config->my->execution->dtable->fieldList['project']['name']   = 'project';
$config->my->execution->dtable->fieldList['project']['title']  = $lang->execution->project;
$config->my->execution->dtable->fieldList['project']['type']   = 'text';
$config->my->execution->dtable->fieldList['project']['link']   = array('module' => 'project', 'method' => 'view', 'params' => 'id={project}');
$config->my->execution->dtable->fieldList['project']['group']  = '2';
$config->my->execution->dtable->fieldList['project']['hidden'] = true;

$config->my->execution->dtable->fieldList['status']['name']      = 'status';
$config->my->execution->dtable->fieldList['status']['title']     = $lang->execution->status;
$config->my->execution->dtable->fieldList['status']['type']      = 'status';
$config->my->execution->dtable->fieldList['status']['statusMap'] = $lang->execution->statusList;
$config->my->execution->dtable->fieldList['status']['group']     = '2';

$config->my->execution->dtable->fieldList['PM']['name']   = 'PM';
$config->my->execution->dtable->fieldList['PM']['title']  = $lang->execution->PM;
$config->my->execution->dtable->fieldList['PM']['type']   = 'avatarBtn';
$config->my->execution->dtable->fieldList['PM']['group']  = '2';
$config->my->execution->dtable->fieldList['PM']['hidden'] = true;

$config->my->execution->dtable->fieldList['role']['name']  = 'role';
$config->my->execution->dtable->fieldList['role']['title'] = $lang->team->roleAB;
$config->my->execution->dtable->fieldList['role']['type']  = 'category';
$config->my->execution->dtable->fieldList['role']['group'] = '2';

$config->my->execution->dtable->fieldList['assignedToMeTasks']['name']     = 'assignedToMeTasks';
$config->my->execution->dtable->fieldList['assignedToMeTasks']['title']    = $lang->execution->myTask;
$config->my->execution->dtable->fieldList['assignedToMeTasks']['type']     = 'count';
$config->my->execution->dtable->fieldList['assignedToMeTasks']['group']    = '2';
$config->my->execution->dtable->fieldList['assignedToMeTasks']['sortType'] = false;

$config->my->execution->dtable->fieldList['openedDate']['name']   = 'openedDate';
$config->my->execution->dtable->fieldList['openedDate']['title']  = $lang->execution->openedDate;
$config->my->execution->dtable->fieldList['openedDate']['type']   = 'date';
$config->my->execution->dtable->fieldList['openedDate']['group']  = '3';
$config->my->execution->dtable->fieldList['openedDate']['hidden'] = true;

$config->my->execution->dtable->fieldList['begin']['name']  = 'begin';
$config->my->execution->dtable->fieldList['begin']['title'] = $lang->execution->begin;
$config->my->execution->dtable->fieldList['begin']['type']  = 'date';
$config->my->execution->dtable->fieldList['begin']['group'] = '3';

$config->my->execution->dtable->fieldList['end']['name']  = 'end';
$config->my->execution->dtable->fieldList['end']['title'] = $lang->execution->end;
$config->my->execution->dtable->fieldList['end']['type']  = 'date';
$config->my->execution->dtable->fieldList['end']['group'] = '3';

$config->my->execution->dtable->fieldList['join']['name']     = 'join';
$config->my->execution->dtable->fieldList['join']['title']    = $lang->team->join;
$config->my->execution->dtable->fieldList['join']['type']     = 'date';
$config->my->execution->dtable->fieldList['join']['group']    = '4';
$config->my->execution->dtable->fieldList['join']['sortType'] = false;

$config->my->execution->dtable->fieldList['hours']['name']     = 'hours';
$config->my->execution->dtable->fieldList['hours']['title']    = $lang->my->hours;
$config->my->execution->dtable->fieldList['hours']['type']     = 'number';
$config->my->execution->dtable->fieldList['hours']['group']    = '4';
$config->my->execution->dtable->fieldList['hours']['sortType'] = false;

$config->my->execution->dtable->fieldList['realBegan']['name']   = 'realBegan';
$config->my->execution->dtable->fieldList['realBegan']['title']  = $lang->execution->realBegan;
$config->my->execution->dtable->fieldList['realBegan']['type']   = 'date';
$config->my->execution->dtable->fieldList['realBegan']['group']  = '4';
$config->my->execution->dtable->fieldList['realBegan']['hidden'] = true;

$config->my->execution->dtable->fieldList['realEnd']['name']   = 'realEnd';
$config->my->execution->dtable->fieldList['realEnd']['title']  = $lang->execution->realEnd;
$config->my->execution->dtable->fieldList['realEnd']['type']   = 'date';
$config->my->execution->dtable->fieldList['realEnd']['group']  = '4';
$config->my->execution->dtable->fieldList['realEnd']['hidden'] = true;

$config->my->execution->dtable->fieldList['totalEstimate']['title']    = $lang->execution->totalEstimate;
$config->my->execution->dtable->fieldList['totalEstimate']['name']     = 'totalEstimate';
$config->my->execution->dtable->fieldList['totalEstimate']['type']     = 'number';
$config->my->execution->dtable->fieldList['totalEstimate']['sortType'] = false;
$config->my->execution->dtable->fieldList['totalEstimate']['group']    = '4';
$config->my->execution->dtable->fieldList['totalEstimate']['hidden']   = true;

$config->my->execution->dtable->fieldList['totalConsumed']['title']    = $lang->execution->totalConsumed;
$config->my->execution->dtable->fieldList['totalConsumed']['name']     = 'totalConsumed';
$config->my->execution->dtable->fieldList['totalConsumed']['type']     = 'number';
$config->my->execution->dtable->fieldList['totalConsumed']['sortType'] = false;
$config->my->execution->dtable->fieldList['totalConsumed']['group']    = '4';
$config->my->execution->dtable->fieldList['totalConsumed']['hidden']   = true;

$config->my->execution->dtable->fieldList['totalLeft']['title']    = $lang->execution->totalLeft;
$config->my->execution->dtable->fieldList['totalLeft']['name']     = 'totalLeft';
$config->my->execution->dtable->fieldList['totalLeft']['type']     = 'number';
$config->my->execution->dtable->fieldList['totalLeft']['sortType'] = false;
$config->my->execution->dtable->fieldList['totalLeft']['width']    = '64';
$config->my->execution->dtable->fieldList['totalLeft']['group']    = '4';
$config->my->execution->dtable->fieldList['totalLeft']['hidden']   = true;

$config->my->execution->dtable->fieldList['progress']['title']    = $lang->execution->progress;
$config->my->execution->dtable->fieldList['progress']['name']     = 'progress';
$config->my->execution->dtable->fieldList['progress']['type']     = 'progress';
$config->my->execution->dtable->fieldList['progress']['sortType'] = false;
$config->my->execution->dtable->fieldList['progress']['group']    = '4';

$config->my->execution->dtable->fieldList['burn']['title']    = $lang->execution->burn;
$config->my->execution->dtable->fieldList['burn']['name']     = 'burn';
$config->my->execution->dtable->fieldList['burn']['type']     = 'burn';
$config->my->execution->dtable->fieldList['burn']['sortType'] = false;
$config->my->execution->dtable->fieldList['burn']['group']    = '4';
$config->my->execution->dtable->fieldList['burn']['hidden']   = true;

$config->my->execution->dtable->fieldList['subStatus']['name']   = 'subStatus';
$config->my->execution->dtable->fieldList['subStatus']['title']  = $lang->execution->subStatus;
$config->my->execution->dtable->fieldList['subStatus']['type']   = 'text';
$config->my->execution->dtable->fieldList['subStatus']['group']  = '5';
$config->my->execution->dtable->fieldList['subStatus']['hidden'] = true;

$config->my->doc = new stdclass();
$config->my->doc->actionList = array();
$config->my->doc->actionList['edit']['icon']        = 'edit';
$config->my->doc->actionList['edit']['text']        = $lang->edit;
$config->my->doc->actionList['edit']['hint']        = $lang->edit;
$config->my->doc->actionList['edit']['url']         = array('module' => 'doc', 'method' => 'edit', 'params' => "docID={id}&comment=false&from={$lang->navGroup->doc}");
$config->my->doc->actionList['edit']['data-toggle'] = 'modal';

$config->my->doc->actionList['delete']['icon'] = 'trash';
$config->my->doc->actionList['delete']['text'] = $lang->delete;
$config->my->doc->actionList['delete']['hint'] = $lang->delete;
$config->my->doc->actionList['delete']['url']  = array('module' => 'doc', 'method' => 'delete', 'params' => "docID={id}&confirm=no&from={$lang->navGroup->doc}");

$config->my->doc->dtable = new stdclass();
$config->my->doc->dtable->fieldList['id']['name']  = 'id';
$config->my->doc->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->doc->dtable->fieldList['id']['type']  = 'id';

$config->my->doc->dtable->fieldList['title']['name']  = 'title';
$config->my->doc->dtable->fieldList['title']['title'] = $lang->doc->title;
$config->my->doc->dtable->fieldList['title']['type']  = 'text';
$config->my->doc->dtable->fieldList['title']['link']  = array('module' => 'doc', 'method' => 'view', 'params' => 'docID={id}');
$config->my->doc->dtable->fieldList['title']['fixed'] = 'left';

$config->my->doc->dtable->fieldList['object']['name']  = 'objectName';
$config->my->doc->dtable->fieldList['object']['title'] = $lang->doc->object;
$config->my->doc->dtable->fieldList['object']['type']  = 'text';
$config->my->doc->dtable->fieldList['object']['group'] = 'object';

$config->my->doc->dtable->fieldList['addedBy']['name']  = 'addedBy';
$config->my->doc->dtable->fieldList['addedBy']['title'] = $lang->doc->addedBy;
$config->my->doc->dtable->fieldList['addedBy']['type']  = 'user';
$config->my->doc->dtable->fieldList['addedBy']['group'] = 'addedBy';

$config->my->doc->dtable->fieldList['addedDate']['name']  = 'addedDate';
$config->my->doc->dtable->fieldList['addedDate']['title'] = $lang->doc->addedDate;
$config->my->doc->dtable->fieldList['addedDate']['type']  = 'date';
$config->my->doc->dtable->fieldList['addedDate']['group'] = 'addedBy';

$config->my->doc->dtable->fieldList['editedBy']['name']  = 'editedBy';
$config->my->doc->dtable->fieldList['editedBy']['title'] = $lang->doc->editedBy;
$config->my->doc->dtable->fieldList['editedBy']['type']  = 'user';
$config->my->doc->dtable->fieldList['editedBy']['group'] = 'addedBy';

$config->my->doc->dtable->fieldList['editedDate']['name']  = 'editedDate';
$config->my->doc->dtable->fieldList['editedDate']['title'] = $lang->doc->editedDate;
$config->my->doc->dtable->fieldList['editedDate']['type']  = 'date';
$config->my->doc->dtable->fieldList['editedDate']['group'] = 'addedBy';

$config->my->doc->dtable->fieldList['actions']['name']     = 'actions';
$config->my->doc->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->doc->dtable->fieldList['actions']['type']     = 'actions';
$config->my->doc->dtable->fieldList['actions']['sortType'] = false;
$config->my->doc->dtable->fieldList['actions']['list']     = $config->my->doc->actionList;
$config->my->doc->dtable->fieldList['actions']['menu']     = array('edit', 'delete');

$config->my->team = new stdclass();
$config->my->team->dtable = $config->company->user->dtable;
$config->my->team->dtable->fieldList['realname']['link'] = array('module' => 'user', 'method' => 'view', 'params' => 'userid={id}&from=my');
unset($config->my->team->dtable->fieldList['actions']);
