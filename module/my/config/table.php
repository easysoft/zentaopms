<?php
global $lang,$config;
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

global $app;
$app->loadLang('score');

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
