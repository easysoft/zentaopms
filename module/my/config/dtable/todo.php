<?php
$config->my->todo = new stdclass();
$config->my->todo->actionList = array();
$config->my->todo->actionList['start']['icon']      = 'play';
$config->my->todo->actionList['start']['text']      = $lang->todo->start;
$config->my->todo->actionList['start']['hint']      = $lang->todo->start;
$config->my->todo->actionList['start']['className'] = 'ajax-submit';
$config->my->todo->actionList['start']['url']       = array('module' => 'todo', 'method' => 'start', 'params' => 'todoID={id}');

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

$config->my->todo->actionList['finish']['icon']      = 'checked';
$config->my->todo->actionList['finish']['text']      = $lang->todo->finish;
$config->my->todo->actionList['finish']['hint']      = $lang->todo->finish;
$config->my->todo->actionList['finish']['url']       = array('module' => 'todo', 'method' => 'finish', 'params' => 'todoID={id}');
$config->my->todo->actionList['finish']['className'] = 'ajax-submit';

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
$config->my->todo->dtable->fieldList['name']['link']        = array('module' => 'todo', 'method' => 'view', 'params' => 'id={id}&from=my');
$config->my->todo->dtable->fieldList['name']['data-toggle'] = 'modal';
$config->my->todo->dtable->fieldList['name']['data-size']   = 'lg';
$config->my->todo->dtable->fieldList['name']['fixed']       = 'left';

$config->my->todo->dtable->fieldList['pri']['name']  = 'pri';
$config->my->todo->dtable->fieldList['pri']['title'] = $lang->priAB;
$config->my->todo->dtable->fieldList['pri']['type']  = 'pri';
$config->my->todo->dtable->fieldList['pri']['group'] = 'pri';
$config->my->todo->dtable->fieldList['pri']['flex']  = 1;
$config->my->todo->dtable->fieldList['pri']['show']  = true;

$config->my->todo->dtable->fieldList['date']['name']  = 'date';
$config->my->todo->dtable->fieldList['date']['title'] = $lang->todo->date;
$config->my->todo->dtable->fieldList['date']['type']  = 'date';
$config->my->todo->dtable->fieldList['date']['group'] = 'date';
$config->my->todo->dtable->fieldList['date']['flex']  = 1;
$config->my->todo->dtable->fieldList['date']['show']  = true;

$config->my->todo->dtable->fieldList['begin']['name']  = 'begin';
$config->my->todo->dtable->fieldList['begin']['title'] = $lang->todo->beginAB;
$config->my->todo->dtable->fieldList['begin']['type']  = 'time';
$config->my->todo->dtable->fieldList['begin']['group'] = 'date';
$config->my->todo->dtable->fieldList['begin']['flex']  = 1;
$config->my->todo->dtable->fieldList['begin']['show']  = true;

$config->my->todo->dtable->fieldList['end']['name']  = 'end';
$config->my->todo->dtable->fieldList['end']['title'] = $lang->todo->endAB;
$config->my->todo->dtable->fieldList['end']['type']  = 'time';
$config->my->todo->dtable->fieldList['end']['group'] = 'date';
$config->my->todo->dtable->fieldList['end']['flex']  = 1;
$config->my->todo->dtable->fieldList['end']['show']  = true;

$config->my->todo->dtable->fieldList['status']['name']      = 'status';
$config->my->todo->dtable->fieldList['status']['title']     = $lang->todo->status;
$config->my->todo->dtable->fieldList['status']['type']      = 'status';
$config->my->todo->dtable->fieldList['status']['statusMap'] = $lang->todo->statusList;
$config->my->todo->dtable->fieldList['status']['group']     = 'status';
$config->my->todo->dtable->fieldList['status']['flex']      = 1;
$config->my->todo->dtable->fieldList['status']['show']      = true;

$config->my->todo->dtable->fieldList['type']['name']  = 'type';
$config->my->todo->dtable->fieldList['type']['title'] = $lang->todo->type;
$config->my->todo->dtable->fieldList['type']['type']  = 'category';
$config->my->todo->dtable->fieldList['type']['map']   = $lang->todo->typeList;
$config->my->todo->dtable->fieldList['type']['group'] = 'status';
$config->my->todo->dtable->fieldList['type']['flex']  = 2;
$config->my->todo->dtable->fieldList['type']['show']  = true;

$config->my->todo->dtable->fieldList['assignedBy']['name']  = 'assignedBy';
$config->my->todo->dtable->fieldList['assignedBy']['title'] = $lang->todo->assignedBy;
$config->my->todo->dtable->fieldList['assignedBy']['type']  = 'user';
$config->my->todo->dtable->fieldList['assignedBy']['width'] = $isEn ? 110 : 90;
$config->my->todo->dtable->fieldList['assignedBy']['group'] = 'assignedBy';
$config->my->todo->dtable->fieldList['assignedBy']['flex']  = 1;
$config->my->todo->dtable->fieldList['assignedBy']['show']  = true;

$config->my->todo->dtable->fieldList['assignedTo']['name']  = 'assignedTo';
$config->my->todo->dtable->fieldList['assignedTo']['title'] = $lang->todo->assignedTo;
$config->my->todo->dtable->fieldList['assignedTo']['type']  = 'user';
$config->my->todo->dtable->fieldList['assignedTo']['group'] = 'assignedBy';
$config->my->todo->dtable->fieldList['assignedTo']['flex']  = 1;
$config->my->todo->dtable->fieldList['assignedTo']['show']  = true;

$config->my->todo->dtable->fieldList['actions']['name']     = 'actions';
$config->my->todo->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->todo->dtable->fieldList['actions']['type']     = 'actions';
$config->my->todo->dtable->fieldList['actions']['width']    = 140;
$config->my->todo->dtable->fieldList['actions']['sortType'] = false;
$config->my->todo->dtable->fieldList['actions']['fixed']    = 'right';
$config->my->todo->dtable->fieldList['actions']['list']     = $config->my->todo->actionList;
$config->my->todo->dtable->fieldList['actions']['menu']     = array('start', 'activate|assignTo', 'close|finish', 'edit', 'delete');
