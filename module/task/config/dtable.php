<?php
$config->task->dtable = new stdclass();
$config->task->dtable->defaultField = array('id', 'name', 'pri', 'assignedTo', 'status', 'finishedBy', 'deadline', 'estimate', 'consumed', 'left', 'progress', 'actions');

$config->task->dtable->fieldList['id']['title']    = $lang->idAB;
$config->task->dtable->fieldList['id']['name']     = 'id';
$config->task->dtable->fieldList['id']['width']    = '70';
$config->task->dtable->fieldList['id']['type']     = 'checkID';
$config->task->dtable->fieldList['id']['sortType'] = 'desc';
$config->task->dtable->fieldList['id']['checkbox'] = true;

$config->task->dtable->fieldList['name']['title']    = $lang->task->name;
$config->task->dtable->fieldList['name']['name']     = 'name';
$config->task->dtable->fieldList['name']['fixed']    = 'left';
$config->task->dtable->fieldList['name']['flex']     = 1;
$config->task->dtable->fieldList['name']['maxWidth'] = 300;
$config->task->dtable->fieldList['name']['type']     = 'nestedTitle';
$config->task->dtable->fieldList['name']['link']     = helper::createLink('task', 'view', 'taskID={id}');

$config->task->dtable->fieldList['pri']['title'] = $lang->priAB;
$config->task->dtable->fieldList['pri']['name']  = 'pri';
$config->task->dtable->fieldList['pri']['fixed'] = 'left';
$config->task->dtable->fieldList['pri']['width'] = '35';
$config->task->dtable->fieldList['pri']['flex']  = 1;
$config->task->dtable->fieldList['pri']['type']  = 'pri';

$config->task->dtable->fieldList['assignedTo']['title']          = $lang->task->assignedTo;
$config->task->dtable->fieldList['assignedTo']['name']           = 'assignedTo';
$config->task->dtable->fieldList['assignedTo']['width']          = '100';
$config->task->dtable->fieldList['assignedTo']['type']           = 'assign';
$config->task->dtable->fieldList['assignedTo']['unassignedText'] = $lang->task->noAssigned;
$config->task->dtable->fieldList['assignedTo']['currentUser']    = $app->user->account;
$config->task->dtable->fieldList['assignedTo']['assignLink']     = helper::createLink('task', 'assignTo', "executionID={execution}&taskID={id}");

$config->task->dtable->fieldList['assignedDate']['title'] = $lang->task->assignedDate;
$config->task->dtable->fieldList['assignedDate']['name']  = 'assignedDate';
$config->task->dtable->fieldList['assignedDate']['width'] = '110';
$config->task->dtable->fieldList['assignedDate']['type']  = 'date';

$config->task->dtable->fieldList['type']['title'] = $lang->task->typeAB;
$config->task->dtable->fieldList['type']['name']  = 'type';
$config->task->dtable->fieldList['type']['fixed'] = 'no';
$config->task->dtable->fieldList['type']['width'] = '80';
$config->task->dtable->fieldList['type']['type']  = 'category';
$config->task->dtable->fieldList['type']['map']   = $lang->task->typeList;

$config->task->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->task->dtable->fieldList['status']['name']      = 'status';
$config->task->dtable->fieldList['status']['width']     = '60';
$config->task->dtable->fieldList['status']['type']      = 'status';
$config->task->dtable->fieldList['status']['statusMap'] = $lang->task->statusList;

$config->task->dtable->fieldList['finishedBy']['title'] = $lang->task->finishedByAB;
$config->task->dtable->fieldList['finishedBy']['name']  = 'finishedBy';
$config->task->dtable->fieldList['finishedBy']['width'] = '80';
$config->task->dtable->fieldList['finishedBy']['type']  = 'user';

$config->task->dtable->fieldList['deadline']['title'] = $lang->task->deadlineAB;
$config->task->dtable->fieldList['deadline']['name']  = 'deadline';
$config->task->dtable->fieldList['deadline']['width'] = '70';
$config->task->dtable->fieldList['deadline']['type']  = 'date';

$config->task->dtable->fieldList['estimate']['title'] = $lang->task->estimateAB;
$config->task->dtable->fieldList['estimate']['name']  = 'estimate';
$config->task->dtable->fieldList['estimate']['width'] = '65';
$config->task->dtable->fieldList['estimate']['type']  = 'number';

$config->task->dtable->fieldList['consumed']['title'] = $lang->task->consumedAB;
$config->task->dtable->fieldList['consumed']['name']  = 'consumed';
$config->task->dtable->fieldList['consumed']['width'] = '65';
$config->task->dtable->fieldList['consumed']['type']  = 'number';

$config->task->dtable->fieldList['left']['title'] = $lang->task->leftAB;
$config->task->dtable->fieldList['left']['name']  = 'left';
$config->task->dtable->fieldList['left']['width'] = '65';
$config->task->dtable->fieldList['left']['type']  = 'number';

$config->task->dtable->fieldList['progress']['title']    = $lang->task->progressAB;
$config->task->dtable->fieldList['progress']['name']     = 'progress';
$config->task->dtable->fieldList['progress']['width']    = '75';
$config->task->dtable->fieldList['progress']['type']     = 'progress';

$config->task->dtable->fieldList['openedBy']['title'] = $lang->task->openedByAB;
$config->task->dtable->fieldList['openedBy']['name']  = 'openedBy';
$config->task->dtable->fieldList['openedBy']['width'] = '90';
$config->task->dtable->fieldList['openedBy']['type']  = 'user';

$config->task->dtable->fieldList['openedDate']['title'] = $lang->task->openedDate;
$config->task->dtable->fieldList['openedDate']['name']  = 'openedDate';
$config->task->dtable->fieldList['openedDate']['width'] = '110';
$config->task->dtable->fieldList['openedDate']['type']  = 'date';

$config->task->dtable->fieldList['estStarted']['title'] = $lang->task->estStarted;
$config->task->dtable->fieldList['estStarted']['name']  = 'estStarted';
$config->task->dtable->fieldList['estStarted']['width'] = '90';
$config->task->dtable->fieldList['estStarted']['type']  = 'date';

$config->task->dtable->fieldList['realStarted']['title'] = $lang->task->realStarted;
$config->task->dtable->fieldList['realStarted']['name']  = 'realStarted';
$config->task->dtable->fieldList['realStarted']['type']  = 'date';
$config->task->dtable->fieldList['realStarted']['width'] = '95';

$config->task->dtable->fieldList['finishedDate']['title'] = $lang->task->finishedDateAB;
$config->task->dtable->fieldList['finishedDate']['name']  = 'finishedDateAB';
$config->task->dtable->fieldList['finishedDate']['width'] = '105';
$config->task->dtable->fieldList['finishedDate']['type']  = 'date';

$config->task->dtable->fieldList['canceledBy']['title'] = $lang->task->canceledBy;
$config->task->dtable->fieldList['canceledBy']['name']  = 'canceledBy';
$config->task->dtable->fieldList['canceledBy']['width'] = '110';
$config->task->dtable->fieldList['canceledBy']['type']  = 'user';

$config->task->dtable->fieldList['canceledDate']['title'] = $lang->task->canceledDate;
$config->task->dtable->fieldList['canceledDate']['name']  = 'canceledDate';
$config->task->dtable->fieldList['canceledDate']['width'] = '115';
$config->task->dtable->fieldList['canceledDate']['type']  = 'date';

$config->task->dtable->fieldList['closedBy']['title'] = $lang->task->closedBy;
$config->task->dtable->fieldList['closedBy']['name']  = 'closedBy';
$config->task->dtable->fieldList['closedBy']['width'] = '100';
$config->task->dtable->fieldList['closedBy']['type']  = 'user';

$config->task->dtable->fieldList['closedDate']['title'] = $lang->task->closedDate;
$config->task->dtable->fieldList['closedDate']['name']  = 'closedDate';
$config->task->dtable->fieldList['closedDate']['width'] = '95';
$config->task->dtable->fieldList['closedDate']['type']  = 'date';

$config->task->dtable->fieldList['closedReason']['title'] = $lang->task->closedReason;
$config->task->dtable->fieldList['closedReason']['name']  = 'closedReason';
$config->task->dtable->fieldList['closedReason']['width'] = '120';
$config->task->dtable->fieldList['closedReason']['type']  = 'category';
$config->task->dtable->fieldList['closedReason']['map']   = $lang->task->reasonList;

$config->task->dtable->fieldList['lastEditedBy']['title'] = $lang->task->lastEditedBy;
$config->task->dtable->fieldList['lastEditedBy']['name']  = 'lastEditedBy';
$config->task->dtable->fieldList['lastEditedBy']['width'] = '95';
$config->task->dtable->fieldList['lastEditedBy']['type']  = 'user';

$config->task->dtable->fieldList['lastEditedDate']['title'] = $lang->task->lastEditedDate;
$config->task->dtable->fieldList['lastEditedDate']['name']  = 'lastEditedDate';
$config->task->dtable->fieldList['lastEditedDate']['width'] = '120';
$config->task->dtable->fieldList['lastEditedDate']['type']  = 'date';

$config->task->dtable->fieldList['activatedDate']['title'] = $lang->task->activatedDate;
$config->task->dtable->fieldList['activatedDate']['name']  = 'activatedDate';
$config->task->dtable->fieldList['activatedDate']['width'] = '90';
$config->task->dtable->fieldList['activatedDate']['type']  = 'date';

$config->task->dtable->fieldList['story']['title'] = $lang->task->storyAB;
$config->task->dtable->fieldList['story']['name']  = 'storyTitle';
$config->task->dtable->fieldList['story']['width'] = '80';

$config->task->dtable->fieldList['mailto']['title'] = $lang->task->mailto;
$config->task->dtable->fieldList['mailto']['name']  = 'mailto';
$config->task->dtable->fieldList['mailto']['width'] = '100';
$config->task->dtable->fieldList['mailto']['type']  = 'user';

$config->task->dtable->fieldList['actions']['title'] = $lang->actions;
$config->task->dtable->fieldList['actions']['name']  = 'actions';
$config->task->dtable->fieldList['actions']['fixed'] = 'right';
$config->task->dtable->fieldList['actions']['width'] = '180';
$config->task->dtable->fieldList['actions']['type']  = 'actions';

$config->task->dtable->fieldList['actions']['actionsMap']['confirmStoryChange']['icon'] = 'search';
$config->task->dtable->fieldList['actions']['actionsMap']['confirmStoryChange']['hint'] = $lang->task->activate;
$config->task->dtable->fieldList['actions']['actionsMap']['confirmStoryChange']['url']  = helper::createLink('task', 'confirmStoryChange', 'taskID={id}');

$config->task->dtable->fieldList['actions']['actionsMap']['start']['icon']        = 'play';
$config->task->dtable->fieldList['actions']['actionsMap']['start']['hint']        = $lang->task->start;
$config->task->dtable->fieldList['actions']['actionsMap']['start']['url']         = helper::createLink('task', 'start', 'taskID={id}', '', true);
$config->task->dtable->fieldList['actions']['actionsMap']['start']['data-toggle'] = 'modal';

$config->task->dtable->fieldList['actions']['actionsMap']['restart']['icon']        = 'icon-restart';
$config->task->dtable->fieldList['actions']['actionsMap']['restart']['hint']        = $lang->task->restart;
$config->task->dtable->fieldList['actions']['actionsMap']['restart']['url']         = helper::createLink('task', 'restart', 'taskID={id}', '', true);
$config->task->dtable->fieldList['actions']['actionsMap']['restart']['data-toggle'] = 'modal';

$config->task->dtable->fieldList['actions']['actionsMap']['finish']['icon']        = 'checked';
$config->task->dtable->fieldList['actions']['actionsMap']['finish']['hint']        = $lang->task->finish;
$config->task->dtable->fieldList['actions']['actionsMap']['finish']['url']         = helper::createLink('task', 'finish', 'taskID={id}', '', true);
$config->task->dtable->fieldList['actions']['actionsMap']['finish']['data-toggle'] = 'modal';

$config->task->dtable->fieldList['actions']['actionsMap']['close']['icon']        = 'off';
$config->task->dtable->fieldList['actions']['actionsMap']['close']['hint']        = $lang->task->close;
$config->task->dtable->fieldList['actions']['actionsMap']['close']['url']         = helper::createLink('task', 'close', 'taskID={id}', '', true);
$config->task->dtable->fieldList['actions']['actionsMap']['close']['data-toggle'] = 'modal';

$config->task->dtable->fieldList['actions']['actionsMap']['recordWorkhour']['icon']        = 'time';
$config->task->dtable->fieldList['actions']['actionsMap']['recordWorkhour']['hint']        = $lang->task->record;
$config->task->dtable->fieldList['actions']['actionsMap']['recordWorkhour']['url']         = helper::createLink('task', 'recordEstimate', 'taskID={id}', '', true);
$config->task->dtable->fieldList['actions']['actionsMap']['recordWorkhour']['data-toggle'] = 'modal';

$config->task->dtable->fieldList['actions']['actionsMap']['edit']['icon'] = 'edit';
$config->task->dtable->fieldList['actions']['actionsMap']['edit']['hint'] = $lang->task->edit;
$config->task->dtable->fieldList['actions']['actionsMap']['edit']['url']  = helper::createLink('task', 'edit', 'taskID={id}');

$config->task->dtable->fieldList['actions']['actionsMap']['batchCreate']['icon'] = 'split';
$config->task->dtable->fieldList['actions']['actionsMap']['batchCreate']['hint'] = $lang->task->batchCreate;
$config->task->dtable->fieldList['actions']['actionsMap']['batchCreate']['url']  = helper::createLink('task', 'batchCreate', 'taskID={id}');
