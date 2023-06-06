<?php
global $lang,$config;
$config->my->todo->dtable = new stdclass();
$config->my->todo->dtable->fieldList['id']['name']  = 'id';
$config->my->todo->dtable->fieldList['id']['title'] = $lang->idAB;
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
