<?php
global $lang,$config, $app;
$app->loadLang('todo');
$app->loadLang('score');
$app->loadLang('task');
$app->loadLang('story');

$config->my->todo = new stdclass();
$config->my->todo->actionList = array();
$config->my->todo->actionList['start']['icon'] = 'play';
$config->my->todo->actionList['start']['text'] = $lang->todo->start;
$config->my->todo->actionList['start']['hint'] = $lang->todo->start;
$config->my->todo->actionList['start']['url']  = helper::createLink('todo', 'start', 'todoID={id}');

$config->my->todo->actionList['activate']['icon'] = 'magic';
$config->my->todo->actionList['activate']['text'] = $lang->todo->activate;
$config->my->todo->actionList['activate']['hint'] = $lang->todo->activate;
$config->my->todo->actionList['activate']['url']  = helper::createLink('todo', 'activate', 'todoID={id}');

$config->my->todo->actionList['close']['icon'] = 'off';
$config->my->todo->actionList['close']['text'] = $lang->todo->close;
$config->my->todo->actionList['close']['hint'] = $lang->todo->close;
$config->my->todo->actionList['close']['url']  = helper::createLink('todo', 'close', 'todoID={id}');

$config->my->todo->actionList['assignTo']['icon']        = 'hand-right';
$config->my->todo->actionList['assignTo']['text']        = $lang->todo->assignedTo;
$config->my->todo->actionList['assignTo']['hint']        = $lang->todo->assignedTo;
$config->my->todo->actionList['assignTo']['url']         = helper::createLink('todo', 'assignTo', 'todoID={id}');
$config->my->todo->actionList['assignTo']['data-toggle'] = 'modal';

$config->my->todo->actionList['finish']['icon'] = 'checked';
$config->my->todo->actionList['finish']['text'] = $lang->todo->finish;
$config->my->todo->actionList['finish']['hint'] = $lang->todo->finish;
$config->my->todo->actionList['finish']['url']  = helper::createLink('todo', 'finish', 'todoID={id}');

$config->my->todo->actionList['edit']['icon']        = 'edit';
$config->my->todo->actionList['edit']['text']        = $lang->todo->edit;
$config->my->todo->actionList['edit']['hint']        = $lang->todo->edit;
$config->my->todo->actionList['edit']['url']         = helper::createLink('todo', 'edit', 'todoID={id}');
$config->my->todo->actionList['edit']['data-toggle'] = 'modal';

$config->my->todo->actionList['delete']['icon'] = 'trash';
$config->my->todo->actionList['delete']['text'] = $lang->todo->delete;
$config->my->todo->actionList['delete']['hint'] = $lang->todo->delete;
$config->my->todo->actionList['delete']['url']  = helper::createLink('todo', 'delete', 'todoID={id}&confirm=yes');

$config->my->todo->dtable = new stdclass();
$config->my->todo->dtable->fieldList['id']['name']  = 'id';
$config->my->todo->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->todo->dtable->fieldList['id']['type']  = 'checkID';
$config->my->todo->dtable->fieldList['id']['fixed'] = 'left';

$config->my->todo->dtable->fieldList['name']['name']  = 'name';
$config->my->todo->dtable->fieldList['name']['title'] = $lang->todo->name;
$config->my->todo->dtable->fieldList['name']['type']  = 'title';
$config->my->todo->dtable->fieldList['name']['link']  = helper::createLink('todo', 'view', 'id={id}&from=my');
$config->my->todo->dtable->fieldList['name']['fixed'] = 'left';

$config->my->todo->dtable->fieldList['pri']['name']  = 'pri';
$config->my->todo->dtable->fieldList['pri']['title'] = $lang->priAB;
$config->my->todo->dtable->fieldList['pri']['type']  = 'pri';
$config->my->todo->dtable->fieldList['pri']['group'] = 'pri';

$config->my->todo->dtable->fieldList['date']['name']  = 'date';
$config->my->todo->dtable->fieldList['date']['title'] = $lang->todo->date;
$config->my->todo->dtable->fieldList['date']['type']  = 'date';
$config->my->todo->dtable->fieldList['date']['group'] = 'date';

$config->my->todo->dtable->fieldList['begin']['name']  = 'begin';
$config->my->todo->dtable->fieldList['begin']['title'] = $lang->todo->beginAB;
$config->my->todo->dtable->fieldList['begin']['type']  = 'time';
$config->my->todo->dtable->fieldList['begin']['group'] = 'date';

$config->my->todo->dtable->fieldList['end']['name']  = 'end';
$config->my->todo->dtable->fieldList['end']['title'] = $lang->todo->endAB;
$config->my->todo->dtable->fieldList['end']['type']  = 'time';
$config->my->todo->dtable->fieldList['end']['group'] = 'date';

$config->my->todo->dtable->fieldList['status']['name']      = 'status';
$config->my->todo->dtable->fieldList['status']['title']     = $lang->todo->status;
$config->my->todo->dtable->fieldList['status']['type']      = 'status';
$config->my->todo->dtable->fieldList['status']['statusMap'] = $lang->todo->statusList;
$config->my->todo->dtable->fieldList['status']['group']     = 'status';

$config->my->todo->dtable->fieldList['type']['name']  = 'type';
$config->my->todo->dtable->fieldList['type']['title'] = $lang->todo->type;
$config->my->todo->dtable->fieldList['type']['type']  = 'category';
$config->my->todo->dtable->fieldList['type']['map']   = $lang->todo->typeList;
$config->my->todo->dtable->fieldList['type']['group'] = 'status';

$config->my->todo->dtable->fieldList['assignedBy']['name']  = 'assignedBy';
$config->my->todo->dtable->fieldList['assignedBy']['title'] = $lang->todo->assignedBy;
$config->my->todo->dtable->fieldList['assignedBy']['type']  = 'user';
$config->my->todo->dtable->fieldList['assignedBy']['group'] = 'assignedBy';

$config->my->todo->dtable->fieldList['actions']['name']     = 'actions';
$config->my->todo->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->todo->dtable->fieldList['actions']['type']     = 'actions';
$config->my->todo->dtable->fieldList['actions']['sortType'] = false;
$config->my->todo->dtable->fieldList['actions']['list']     = $config->my->todo->actionList;
$config->my->todo->dtable->fieldList['actions']['menu']     = array('start', 'activate|assignTo', 'close|finish', 'edit', 'delete');

$config->my->score         = new stdclass();
$config->my->score->dtable = new stdclass();

$config->my->score->dtable->fieldList['time']['name']  = 'time';
$config->my->score->dtable->fieldList['time']['title'] = $lang->score->time;
$config->my->score->dtable->fieldList['time']['type']  = 'datetime';
$config->my->score->dtable->fieldList['time']['fixed'] = 'left';

$config->my->score->dtable->fieldList['module']['name']  = 'module';
$config->my->score->dtable->fieldList['module']['title'] = $lang->score->module;
$config->my->score->dtable->fieldList['module']['type']  = 'category';
$config->my->score->dtable->fieldList['module']['map']   = $lang->score->modules;

$config->my->score->dtable->fieldList['method']['name']  = 'method';
$config->my->score->dtable->fieldList['method']['title'] = $lang->score->method;
$config->my->score->dtable->fieldList['method']['type']  = 'text';
$config->my->score->dtable->fieldList['method']['map']   = $lang->score->methods;

$config->my->score->dtable->fieldList['before']['name']  = 'before';
$config->my->score->dtable->fieldList['before']['title'] = $lang->score->before;
$config->my->score->dtable->fieldList['before']['type']  = 'count';

$config->my->score->dtable->fieldList['score']['name']  = 'score';
$config->my->score->dtable->fieldList['score']['title'] = $lang->score->score;
$config->my->score->dtable->fieldList['score']['type']  = 'number';

$config->my->score->dtable->fieldList['after']['name']  = 'after';
$config->my->score->dtable->fieldList['after']['title'] = $lang->score->after;
$config->my->score->dtable->fieldList['after']['type']  = 'count';

$config->my->score->dtable->fieldList['desc']['name']  = 'desc';
$config->my->score->dtable->fieldList['desc']['title'] = $lang->score->desc;
$config->my->score->dtable->fieldList['desc']['type']  = 'desc';

$config->my->task = new stdclass();
$config->my->task->actionList = array();
$config->my->task->actionList['confirmStoryChange']['icon'] = 'search';
$config->my->task->actionList['confirmStoryChange']['text'] = $lang->task->confirmStoryChange;
$config->my->task->actionList['confirmStoryChange']['hint'] = $lang->task->confirmStoryChange;
$config->my->task->actionList['confirmStoryChange']['url']  = helper::createLink('task', 'confirmStoryChange', 'taskID={id}');

$config->my->task->actionList['start']['icon']        = 'play';
$config->my->task->actionList['start']['text']        = $lang->task->start;
$config->my->task->actionList['start']['hint']        = $lang->task->start;
$config->my->task->actionList['start']['url']         = helper::createLink('task', 'start', 'taskID={id}');
$config->my->task->actionList['start']['data-toggle'] = 'modal';

$config->my->task->actionList['restart']['icon']        = 'play';
$config->my->task->actionList['restart']['text']        = $lang->task->restart;
$config->my->task->actionList['restart']['hint']        = $lang->task->restart;
$config->my->task->actionList['restart']['url']         = helper::createLink('task', 'restart', 'taskID={id}');
$config->my->task->actionList['restart']['data-toggle'] = 'modal';

$config->my->task->actionList['finish']['icon']        = 'checked';
$config->my->task->actionList['finish']['text']        = $lang->task->finish;
$config->my->task->actionList['finish']['hint']        = $lang->task->finish;
$config->my->task->actionList['finish']['url']         = helper::createLink('task', 'finish', 'taskID={id}');
$config->my->task->actionList['finish']['data-toggle'] = 'modal';

$config->my->task->actionList['close']['icon']        = 'off';
$config->my->task->actionList['close']['text']        = $lang->task->close;
$config->my->task->actionList['close']['hint']        = $lang->task->close;
$config->my->task->actionList['close']['url']         = helper::createLink('task', 'close', 'taskID={id}');
$config->my->task->actionList['close']['data-toggle'] = 'modal';

$config->my->task->actionList['record']['icon']        = 'time';
$config->my->task->actionList['record']['text']        = $lang->task->logEfforts;
$config->my->task->actionList['record']['hint']        = $lang->task->logEfforts;
$config->my->task->actionList['record']['url']         = helper::createLink('task', 'recordEstimate', 'taskID={id}');
$config->my->task->actionList['record']['data-toggle'] = 'modal';

$config->my->task->actionList['edit']['icon']        = 'edit';
$config->my->task->actionList['edit']['text']        = $lang->task->edit;
$config->my->task->actionList['edit']['hint']        = $lang->task->edit;
$config->my->task->actionList['edit']['url']         = helper::createLink('task', 'edit', 'taskID={id}');
$config->my->task->actionList['edit']['data-toggle'] = 'modal';

$config->my->task->actionList['batchCreate']['icon']        = 'split';
$config->my->task->actionList['batchCreate']['text']        = $lang->task->batchCreate;
$config->my->task->actionList['batchCreate']['hint']        = $lang->task->batchCreate;
$config->my->task->actionList['batchCreate']['url']         = helper::createLink('task', 'batchCreate', 'executionID={execution}&storyID={story}&moduleID={module}&taskID={id}iframe=true');
$config->my->task->actionList['batchCreate']['data-toggle'] = 'modal';

$config->my->task->dtable = new stdclass();
$config->my->task->dtable->fieldList['id']['name']  = 'id';
$config->my->task->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->task->dtable->fieldList['id']['type']  = 'checkID';
$config->my->task->dtable->fieldList['id']['fixed'] = 'left';

$config->my->task->dtable->fieldList['name']['name']  = 'name';
$config->my->task->dtable->fieldList['name']['title'] = $lang->task->name;
$config->my->task->dtable->fieldList['name']['type']  = 'title';
$config->my->task->dtable->fieldList['name']['link']  = helper::createLink('task', 'view', 'taskID={id}');
$config->my->task->dtable->fieldList['name']['fixed'] = 'left';

$config->my->task->dtable->fieldList['pri']['name']  = 'pri';
$config->my->task->dtable->fieldList['pri']['title'] = $lang->priAB;
$config->my->task->dtable->fieldList['pri']['type']  = 'pri';
$config->my->task->dtable->fieldList['pri']['map']   = $lang->task->priList;
$config->my->task->dtable->fieldList['pri']['group'] = 'pri';

$config->my->task->dtable->fieldList['status']['name']      = 'status';
$config->my->task->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->my->task->dtable->fieldList['status']['type']      = 'status';
$config->my->task->dtable->fieldList['status']['statusMap'] = $lang->task->statusList;
$config->my->task->dtable->fieldList['status']['group']     = 'pri';

$config->my->task->dtable->fieldList['project']['name']  = 'projectName';
$config->my->task->dtable->fieldList['project']['title'] = $lang->task->project;
$config->my->task->dtable->fieldList['project']['type']  = 'text';
$config->my->task->dtable->fieldList['project']['group'] = 'project';

$config->my->task->dtable->fieldList['execution']['name']  = 'executionName';
$config->my->task->dtable->fieldList['execution']['title'] = $lang->task->execution;
$config->my->task->dtable->fieldList['execution']['type']  = 'text';
$config->my->task->dtable->fieldList['execution']['group'] = 'project';

$config->my->task->dtable->fieldList['openedBy']['name']  = 'openedBy';
$config->my->task->dtable->fieldList['openedBy']['title'] = $lang->task->openedByAB;
$config->my->task->dtable->fieldList['openedBy']['type']  = 'user';
$config->my->task->dtable->fieldList['openedBy']['group'] = 'user';

$config->my->task->dtable->fieldList['assignedTo']['name']  = 'assignedTo';
$config->my->task->dtable->fieldList['assignedTo']['title'] = $lang->task->assignedToAB;
$config->my->task->dtable->fieldList['assignedTo']['type']  = 'user';
$config->my->task->dtable->fieldList['assignedTo']['group'] = 'user';

$config->my->task->dtable->fieldList['finishedBy']['name']  = 'finishedBy';
$config->my->task->dtable->fieldList['finishedBy']['title'] = $lang->task->finishedByAB;
$config->my->task->dtable->fieldList['finishedBy']['type']  = 'user';
$config->my->task->dtable->fieldList['finishedBy']['group'] = 'user';

$config->my->task->dtable->fieldList['deadline']['name']  = 'deadline';
$config->my->task->dtable->fieldList['deadline']['title'] = $lang->task->deadlineAB;
$config->my->task->dtable->fieldList['deadline']['type']  = 'date';
$config->my->task->dtable->fieldList['deadline']['group'] = 'deadline';

$config->my->task->dtable->fieldList['estimate']['name']  = 'estimateLabel';
$config->my->task->dtable->fieldList['estimate']['title'] = $lang->task->estimateAB;
$config->my->task->dtable->fieldList['estimate']['type']  = 'number';
$config->my->task->dtable->fieldList['estimate']['group'] = 'deadline';

$config->my->task->dtable->fieldList['consumed']['name']  = 'consumedLabel';
$config->my->task->dtable->fieldList['consumed']['title'] = $lang->task->consumedAB;
$config->my->task->dtable->fieldList['consumed']['type']  = 'number';
$config->my->task->dtable->fieldList['consumed']['group'] = 'deadline';

$config->my->task->dtable->fieldList['left']['name']  = 'leftLabel';
$config->my->task->dtable->fieldList['left']['title'] = $lang->task->leftAB;
$config->my->task->dtable->fieldList['left']['type']  = 'number';
$config->my->task->dtable->fieldList['left']['group'] = 'deadline';

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
$config->my->requirement->actionList['change']['url']         = helper::createLink('story', 'change', 'storyID={id}&from=&storyType=requirement');
$config->my->requirement->actionList['change']['data-toggle'] = 'modal';

$config->my->requirement->actionList['submitReview']['icon']        = 'confirm';
$config->my->requirement->actionList['submitReview']['text']        = $lang->story->submitReview;
$config->my->requirement->actionList['submitReview']['hint']        = $lang->story->submitReview;
$config->my->requirement->actionList['submitReview']['url']         = helper::createLink('story', 'submitReview', 'storyID={id}&storyType=requirement');
$config->my->requirement->actionList['submitReview']['data-toggle'] = 'modal';

$config->my->requirement->actionList['review']['icon']        = 'search';
$config->my->requirement->actionList['review']['text']        = $lang->story->review;
$config->my->requirement->actionList['review']['hint']        = $lang->story->review;
$config->my->requirement->actionList['review']['url']         = helper::createLink('story', 'review', 'storyID={id}&from=product&storyType=requirement');
$config->my->requirement->actionList['review']['data-toggle'] = 'modal';

$config->my->requirement->actionList['recall']['icon'] = 'undo';
$config->my->requirement->actionList['recall']['text'] = $lang->story->recall;
$config->my->requirement->actionList['recall']['hint'] = $lang->story->recall;
$config->my->requirement->actionList['recall']['url']  = helper::createLink('story', 'recall', 'storyID={id}&from=list&confirm=no&storyType=requirement');

$config->my->requirement->actionList['edit']['icon']        = 'edit';
$config->my->requirement->actionList['edit']['text']        = $lang->story->edit;
$config->my->requirement->actionList['edit']['hint']        = $lang->story->edit;
$config->my->requirement->actionList['edit']['url']         = helper::createLink('story', 'edit', 'storyID={id}&from=default&storyType=requirement');
$config->my->requirement->actionList['edit']['data-toggle'] = 'modal';

$config->my->requirement->actionList['close']['icon'] = 'off';
$config->my->requirement->actionList['close']['text'] = $lang->story->close;
$config->my->requirement->actionList['close']['hint'] = $lang->story->close;
$config->my->requirement->actionList['close']['url']  = helper::createLink('story', 'close', 'storyID={id}&from=&storyType=requirement');

$config->my->requirement->dtable = new stdclass();
$config->my->requirement->dtable->fieldList['id']['name']  = 'id';
$config->my->requirement->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->requirement->dtable->fieldList['id']['type']  = 'checkID';
$config->my->requirement->dtable->fieldList['id']['fixed'] = 'left';

$config->my->requirement->dtable->fieldList['title']['name']  = 'title';
$config->my->requirement->dtable->fieldList['title']['title'] = common::checkNotCN() ? $lang->URCommon . ' ' . $lang->my->name : $lang->URCommon . $lang->my->name;
$config->my->requirement->dtable->fieldList['title']['type']  = 'title';
$config->my->requirement->dtable->fieldList['title']['link']  = helper::createLink('story', 'view', 'id={id}&version=0&param=0&storyType=requirement');
$config->my->requirement->dtable->fieldList['title']['fixed'] = 'left';

$config->my->requirement->dtable->fieldList['pri']['name']  = 'pri';
$config->my->requirement->dtable->fieldList['pri']['title'] = $lang->priAB;
$config->my->requirement->dtable->fieldList['pri']['type']  = 'pri';
$config->my->requirement->dtable->fieldList['pri']['group'] = 'pri';

$config->my->requirement->dtable->fieldList['product']['name']  = 'productTitle';
$config->my->requirement->dtable->fieldList['product']['title'] = $lang->story->product;
$config->my->requirement->dtable->fieldList['product']['type']  = 'text';
$config->my->requirement->dtable->fieldList['product']['group'] = 'pri';

$config->my->requirement->dtable->fieldList['status']['name']      = 'status';
$config->my->requirement->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->my->requirement->dtable->fieldList['status']['type']      = 'status';
$config->my->requirement->dtable->fieldList['status']['statusMap'] = $lang->story->statusList;
$config->my->requirement->dtable->fieldList['status']['group']     = 'pri';

$config->my->requirement->dtable->fieldList['openedBy']['name']      = 'openedBy';
$config->my->requirement->dtable->fieldList['openedBy']['title']     = $lang->story->openedByAB;
$config->my->requirement->dtable->fieldList['openedBy']['type']      = 'user';
$config->my->requirement->dtable->fieldList['openedBy']['group']     = 'openedBy';

$config->my->requirement->dtable->fieldList['estimate']['name']  = 'estimate';
$config->my->requirement->dtable->fieldList['estimate']['title'] = $lang->story->estimateAB;
$config->my->requirement->dtable->fieldList['estimate']['type']  = 'count';
$config->my->requirement->dtable->fieldList['estimate']['group'] = 'openedBy';

$config->my->requirement->dtable->fieldList['stage']['name']  = 'stage';
$config->my->requirement->dtable->fieldList['stage']['title'] = $lang->story->stageAB;
$config->my->requirement->dtable->fieldList['stage']['type']  = 'category';
$config->my->requirement->dtable->fieldList['stage']['map']   = $lang->story->stageList;
$config->my->requirement->dtable->fieldList['stage']['group'] = 'openedBy';

$config->my->requirement->dtable->fieldList['actions']['name']     = 'actions';
$config->my->requirement->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->requirement->dtable->fieldList['actions']['type']     = 'actions';
$config->my->requirement->dtable->fieldList['actions']['sortType'] = false;
$config->my->requirement->dtable->fieldList['actions']['list']     = $config->my->requirement->actionList;
$config->my->requirement->dtable->fieldList['actions']['menu']     = array('change', 'submitReview|review', 'recall', 'edit', 'close');

$config->my->story = new stdclass();
$config->my->story->actionList = array();
$config->my->story->actionList['change']['icon']        = 'alter';
$config->my->story->actionList['change']['text']        = $lang->story->change;
$config->my->story->actionList['change']['hint']        = $lang->story->change;
$config->my->story->actionList['change']['url']         = helper::createLink('story', 'change', 'storyID={id}');
$config->my->story->actionList['change']['data-toggle'] = 'modal';

$config->my->story->actionList['submitReview']['icon']        = 'confirm';
$config->my->story->actionList['submitReview']['text']        = $lang->story->submitReview;
$config->my->story->actionList['submitReview']['hint']        = $lang->story->submitReview;
$config->my->story->actionList['submitReview']['url']         = helper::createLink('story', 'submitReview', 'storyID={id}');
$config->my->story->actionList['submitReview']['data-toggle'] = 'modal';

$config->my->story->actionList['review']['icon']        = 'search';
$config->my->story->actionList['review']['text']        = $lang->story->review;
$config->my->story->actionList['review']['hint']        = $lang->story->review;
$config->my->story->actionList['review']['url']         = helper::createLink('story', 'review', 'storyID={id}');
$config->my->story->actionList['review']['data-toggle'] = 'modal';

$config->my->story->actionList['recall']['icon'] = 'undo';
$config->my->story->actionList['recall']['text'] = $lang->story->recall;
$config->my->story->actionList['recall']['hint'] = $lang->story->recall;
$config->my->story->actionList['recall']['url']  = helper::createLink('story', 'recall', 'storyID={id}');

$config->my->story->actionList['create']['icon']        = 'sitemap';
$config->my->story->actionList['create']['text']        = $lang->story->edit;
$config->my->story->actionList['create']['hint']        = $lang->story->edit;
$config->my->story->actionList['create']['url']         = helper::createLink('testcase', 'create', 'productID={product}&branch={branch}&module=0&from=&param=0&storyID={id}');
$config->my->story->actionList['create']['data-toggle'] = 'modal';

$config->my->story->actionList['close']['icon'] = 'off';
$config->my->story->actionList['close']['text'] = $lang->story->close;
$config->my->story->actionList['close']['hint'] = $lang->story->close;
$config->my->story->actionList['close']['url']  = helper::createLink('story', 'close', 'storyID={id}');

$config->my->story->dtable = new stdclass();
$config->my->story->dtable->fieldList['id']['name']  = 'id';
$config->my->story->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->story->dtable->fieldList['id']['type']  = 'checkID';
$config->my->story->dtable->fieldList['id']['fixed'] = 'left';

$config->my->story->dtable->fieldList['title']['name']  = 'title';
$config->my->story->dtable->fieldList['title']['title'] = common::checkNotCN() ? $lang->SRCommon . ' ' . $lang->my->name : $lang->SRCommon . $lang->my->name;
$config->my->story->dtable->fieldList['title']['type']  = 'title';
$config->my->story->dtable->fieldList['title']['link']  = helper::createLink('story', 'view', 'id={id}');
$config->my->story->dtable->fieldList['title']['fixed'] = 'left';

$config->my->story->dtable->fieldList['pri']['name']  = 'pri';
$config->my->story->dtable->fieldList['pri']['title'] = $lang->priAB;
$config->my->story->dtable->fieldList['pri']['type']  = 'pri';
$config->my->story->dtable->fieldList['pri']['group'] = 'pri';

$config->my->story->dtable->fieldList['product']['name']  = 'productTitle';
$config->my->story->dtable->fieldList['product']['title'] = $lang->story->product;
$config->my->story->dtable->fieldList['product']['type']  = 'text';
$config->my->story->dtable->fieldList['product']['group'] = 'product';

$config->my->story->dtable->fieldList['plan']['name']  = 'planTitle';
$config->my->story->dtable->fieldList['plan']['title'] = $lang->story->plan;
$config->my->story->dtable->fieldList['plan']['type']  = 'text';
$config->my->story->dtable->fieldList['plan']['group'] = 'product';

$config->my->story->dtable->fieldList['status']['name']      = 'status';
$config->my->story->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->my->story->dtable->fieldList['status']['type']      = 'status';
$config->my->story->dtable->fieldList['status']['statusMap'] = $lang->story->statusList;
$config->my->story->dtable->fieldList['status']['group']     = 'product';

$config->my->story->dtable->fieldList['openedBy']['name']  = 'openedBy';
$config->my->story->dtable->fieldList['openedBy']['title'] = $lang->story->openedByAB;
$config->my->story->dtable->fieldList['openedBy']['type']  = 'user';
$config->my->story->dtable->fieldList['openedBy']['group'] = 'openedBy';

$config->my->story->dtable->fieldList['estimate']['name']  = 'estimate';
$config->my->story->dtable->fieldList['estimate']['title'] = $lang->story->estimateAB;
$config->my->story->dtable->fieldList['estimate']['type']  = 'count';
$config->my->story->dtable->fieldList['estimate']['group'] = 'openedBy';

$config->my->story->dtable->fieldList['stage']['name']  = 'stage';
$config->my->story->dtable->fieldList['stage']['title'] = $lang->story->stageAB;
$config->my->story->dtable->fieldList['stage']['type']  = 'category';
$config->my->story->dtable->fieldList['stage']['map']   = $lang->story->stageList;
$config->my->story->dtable->fieldList['stage']['group'] = 'openedBy';

$config->my->story->dtable->fieldList['actions']['name']     = 'actions';
$config->my->story->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->story->dtable->fieldList['actions']['type']     = 'actions';
$config->my->story->dtable->fieldList['actions']['sortType'] = false;
$config->my->story->dtable->fieldList['actions']['list']     = $config->my->story->actionList;
$config->my->story->dtable->fieldList['actions']['menu']     = array('change', 'submitReview|review', 'recall', 'create', 'close');

$config->my->audit->dtable = new stdclass();
$config->my->audit->dtable->fieldList['id']['name']  = 'id';
$config->my->audit->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->audit->dtable->fieldList['id']['fixed'] = 'left';

$config->my->audit->dtable->fieldList['title']['name']        = 'title';
$config->my->audit->dtable->fieldList['title']['title']       = $lang->my->auditField->title;
$config->my->audit->dtable->fieldList['title']['type']        = 'title';
$config->my->audit->dtable->fieldList['title']['link']        = helper::createLink('story', 'view', 'id={id}');
$config->my->audit->dtable->fieldList['title']['fixed']       = 'left';
$config->my->audit->dtable->fieldList['title']['data-toggle'] = 'modal';

$config->my->audit->dtable->fieldList['type']['name']  = 'type';
$config->my->audit->dtable->fieldList['type']['title'] = $lang->my->auditField->type;
$config->my->audit->dtable->fieldList['type']['type']  = 'catetory';

$config->my->audit->dtable->fieldList['time']['name']  = 'time';
$config->my->audit->dtable->fieldList['time']['title'] = $lang->my->auditField->time;
$config->my->audit->dtable->fieldList['time']['type']  = 'datetime';

$config->my->audit->dtable->fieldList['result']['name']  = 'result';
$config->my->audit->dtable->fieldList['result']['title'] = $lang->my->auditField->result;
$config->my->audit->dtable->fieldList['result']['type']  = 'text';

$config->my->audit->dtable->fieldList['status']['name']  = 'status';
$config->my->audit->dtable->fieldList['status']['title'] = $lang->my->auditField->status;
$config->my->audit->dtable->fieldList['status']['type']  = 'status';

$config->my->audit->dtable->fieldList['actions']['name']     = 'actions';
$config->my->audit->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->audit->dtable->fieldList['actions']['type']     = 'actions';
$config->my->audit->dtable->fieldList['actions']['sortType'] = false;
$config->my->audit->dtable->fieldList['actions']['fixed']    = 'right';
$config->my->audit->dtable->fieldList['actions']['list']     = $config->my->audit->actionList;
$config->my->audit->dtable->fieldList['actions']['menu']     = array('review');

$config->my->project->dtable = new stdclass();
$config->my->project->dtable->fieldList['name']['name']       = 'name';
$config->my->project->dtable->fieldList['name']['title']      = $lang->project->name;
$config->my->project->dtable->fieldList['name']['type']       = 'title';
$config->my->project->dtable->fieldList['name']['link']       = helper::createLink('project', 'index', 'id={id}');
$config->my->project->dtable->fieldList['name']['fixed']      = 'left';
$config->my->project->dtable->fieldList['name']['group']      = '1';
$config->my->project->dtable->fieldList['name']['iconRender'] = 'RAWJS<function(val,row){ if(row.data.model == \'scrum\') return \'icon-sprint text-gray\'; if([\'waterfall\', \'kanban\', \'agileplus\', \'waterfallplus\'].indexOf(row.data.model) !== -1) return \'icon-\' + row.data.model + \' text-gray\'; return \'\';}>RAWJS';

$config->my->project->dtable->fieldList['code']['name']   = 'code';
$config->my->project->dtable->fieldList['code']['title']  = $lang->project->code;
$config->my->project->dtable->fieldList['code']['type']   = 'text';
$config->my->project->dtable->fieldList['code']['group']  = '1';
$config->my->project->dtable->fieldList['code']['hidden'] = true;

$config->my->project->dtable->fieldList['status']['name']      = 'status';
$config->my->project->dtable->fieldList['status']['title']     = $lang->project->status;
$config->my->project->dtable->fieldList['status']['type']      = 'status';
$config->my->project->dtable->fieldList['status']['statusMap'] = $lang->project->statusList;
$config->my->project->dtable->fieldList['status']['group']     = '2';
$config->my->project->dtable->fieldList['status']['hidden']    = true;

$config->my->project->dtable->fieldList['PM']['name']   = 'PM';
$config->my->project->dtable->fieldList['PM']['title']  = $lang->project->PM;
$config->my->project->dtable->fieldList['PM']['type']   = 'avatarName';
$config->my->project->dtable->fieldList['PM']['group']  = '3';

$config->my->project->dtable->fieldList['storyCount']['name']  = 'storyCount';
$config->my->project->dtable->fieldList['storyCount']['title'] = $lang->project->storyCount;
$config->my->project->dtable->fieldList['storyCount']['type']  = 'count';
$config->my->project->dtable->fieldList['storyCount']['group'] = '4';

$config->my->project->dtable->fieldList['executionCount']['name']  = 'executionCount';
$config->my->project->dtable->fieldList['executionCount']['title'] = $lang->project->executionCount;
$config->my->project->dtable->fieldList['executionCount']['type']  = 'count';
$config->my->project->dtable->fieldList['executionCount']['group'] = '4';

$config->my->project->dtable->fieldList['budget']['name']   = 'budget';
$config->my->project->dtable->fieldList['budget']['title']  = $lang->project->budget;
$config->my->project->dtable->fieldList['budget']['type']   = 'money';
$config->my->project->dtable->fieldList['budget']['group']  = '5';
$config->my->project->dtable->fieldList['budget']['hidden'] = true;

$config->my->project->dtable->fieldList['invested']['name']  = 'invest';
$config->my->project->dtable->fieldList['invested']['title'] = $lang->project->invested;
$config->my->project->dtable->fieldList['invested']['type']  = 'money';
$config->my->project->dtable->fieldList['invested']['group'] = '5';

$config->my->project->dtable->fieldList['begin']['name']  = 'begin';
$config->my->project->dtable->fieldList['begin']['title'] = $lang->project->begin;
$config->my->project->dtable->fieldList['begin']['type']  = 'date';
$config->my->project->dtable->fieldList['begin']['group'] = '6';

$config->my->project->dtable->fieldList['end']['name']  = 'end';
$config->my->project->dtable->fieldList['end']['title'] = $lang->project->end;
$config->my->project->dtable->fieldList['end']['type']  = 'date';
$config->my->project->dtable->fieldList['end']['group'] = '6';

$config->my->project->dtable->fieldList['realBegan']['name']   = 'realBegan';
$config->my->project->dtable->fieldList['realBegan']['title']  = $lang->project->realBegan;
$config->my->project->dtable->fieldList['realBegan']['type']   = 'date';
$config->my->project->dtable->fieldList['realBegan']['group']  = '6';
$config->my->project->dtable->fieldList['realBegan']['hidden'] = true;

$config->my->project->dtable->fieldList['realEnd']['name']   = 'realEnd';
$config->my->project->dtable->fieldList['realEnd']['title']  = $lang->project->realEnd;
$config->my->project->dtable->fieldList['realEnd']['type']   = 'date';
$config->my->project->dtable->fieldList['realEnd']['group']  = '6';
$config->my->project->dtable->fieldList['realEnd']['hidden'] = true;

$config->my->project->dtable->fieldList['progress']['name']  = 'progress';
$config->my->project->dtable->fieldList['progress']['title'] = $lang->project->progress;
$config->my->project->dtable->fieldList['progress']['type']  = 'progress';
$config->my->project->dtable->fieldList['progress']['group'] = '6';

$config->my->project->dtable->fieldList['teamCount']['name']   = 'teamCount';
$config->my->project->dtable->fieldList['teamCount']['title']  = $lang->project->teamCount;
$config->my->project->dtable->fieldList['teamCount']['type']   = 'count';
$config->my->project->dtable->fieldList['teamCount']['group']  = '7';
$config->my->project->dtable->fieldList['teamCount']['hidden'] = true;

$config->my->project->dtable->fieldList['estimate']['name']   = 'estimate';
$config->my->project->dtable->fieldList['estimate']['title']  = $lang->project->estimate;
$config->my->project->dtable->fieldList['estimate']['type']   = 'count';
$config->my->project->dtable->fieldList['estimate']['group']  = '7';
$config->my->project->dtable->fieldList['estimate']['hidden'] = true;

$config->my->project->dtable->fieldList['consume']['name']   = 'consume';
$config->my->project->dtable->fieldList['consume']['title']  = $lang->project->consume;
$config->my->project->dtable->fieldList['consume']['type']   = 'count';
$config->my->project->dtable->fieldList['consume']['group']  = '7';
$config->my->project->dtable->fieldList['consume']['hidden'] = true;

$config->my->project->dtable->fieldList['actions']['name']     = 'actions';
$config->my->project->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->project->dtable->fieldList['actions']['type']     = 'actions';
$config->my->project->dtable->fieldList['actions']['sortType'] = false;
$config->my->project->dtable->fieldList['actions']['list']     = $config->my->project->actionList;
$config->my->project->dtable->fieldList['actions']['menu']     = array('start', 'close', 'active', 'edit', 'pause', 'group', 'perm', 'link', 'whitelist', 'delete');

$app->loadLang('execution');
$config->my->execution = new stdclass();
$config->my->execution->dtable = new stdclass();
$config->my->execution->dtable->fieldList['id']['name']  = 'id';
$config->my->execution->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->execution->dtable->fieldList['id']['type']  = 'ID';
$config->my->execution->dtable->fieldList['id']['fixed'] = 'left';
$config->my->execution->dtable->fieldList['id']['group'] = '1';

$config->my->execution->dtable->fieldList['name']['name']  = 'name';
$config->my->execution->dtable->fieldList['name']['title'] = $lang->execution->name;
$config->my->execution->dtable->fieldList['name']['type']  = 'title';
$config->my->execution->dtable->fieldList['name']['link']  = helper::createLink('execution', 'browse', 'id={id}&from=my');
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
$config->my->execution->dtable->fieldList['project']['link']   = helper::createLink('project', 'view', 'id={project}');
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

$config->my->execution->dtable->fieldList['assignedToMeTasks']['name']  = 'assignedToMeTasks';
$config->my->execution->dtable->fieldList['assignedToMeTasks']['title'] = $lang->execution->myTask;
$config->my->execution->dtable->fieldList['assignedToMeTasks']['type']  = 'count';
$config->my->execution->dtable->fieldList['assignedToMeTasks']['group'] = '2';

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

$config->my->execution->dtable->fieldList['join']['name']  = 'join';
$config->my->execution->dtable->fieldList['join']['title'] = $lang->team->join;
$config->my->execution->dtable->fieldList['join']['type']  = 'date';
$config->my->execution->dtable->fieldList['join']['group'] = '4';

$config->my->execution->dtable->fieldList['hours']['name']  = 'hours';
$config->my->execution->dtable->fieldList['hours']['title'] = $lang->my->hours;
$config->my->execution->dtable->fieldList['hours']['type']  = 'number';
$config->my->execution->dtable->fieldList['hours']['group'] = '4';

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
