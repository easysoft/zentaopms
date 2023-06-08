<?php
$config->task->dtable = new stdclass();

$config->task->dtable->fieldList['id']['title']    = $lang->idAB;
$config->task->dtable->fieldList['id']['name']     = 'id';
$config->task->dtable->fieldList['id']['type']     = 'checkID';
$config->task->dtable->fieldList['id']['sortType'] = 'desc';
$config->task->dtable->fieldList['id']['checkbox'] = true;

$config->task->dtable->fieldList['name']['title']    = $lang->task->name;
$config->task->dtable->fieldList['name']['name']     = 'name';
$config->task->dtable->fieldList['name']['fixed']    = 'left';
$config->task->dtable->fieldList['name']['flex']     = 1;
$config->task->dtable->fieldList['name']['type']     = 'nestedTitle';
$config->task->dtable->fieldList['name']['sortType'] = true;
$config->task->dtable->fieldList['name']['link']     = helper::createLink('task', 'view', 'taskID={id}');

$config->task->dtable->fieldList['pri']['title']    = $lang->priAB;
$config->task->dtable->fieldList['pri']['name']     = 'pri';
$config->task->dtable->fieldList['pri']['type']     = 'pri';
$config->task->dtable->fieldList['pri']['sortType'] = true;

$config->task->dtable->fieldList['type']['title']    = $lang->task->typeAB;
$config->task->dtable->fieldList['type']['name']     = 'type';
$config->task->dtable->fieldList['type']['type']     = 'category';
$config->task->dtable->fieldList['type']['map']      = $lang->task->typeList;
$config->task->dtable->fieldList['type']['sortType'] = true;

$config->task->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->task->dtable->fieldList['status']['name']      = 'status';
$config->task->dtable->fieldList['status']['type']      = 'status';
$config->task->dtable->fieldList['status']['statusMap'] = $lang->task->statusList;
$config->task->dtable->fieldList['status']['sortType']  = true;

$config->task->dtable->fieldList['openedBy']['title']    = $lang->task->openedByAB;
$config->task->dtable->fieldList['openedBy']['name']     = 'openedBy';
$config->task->dtable->fieldList['openedBy']['type']     = 'user';
$config->task->dtable->fieldList['openedBy']['sortType'] = true;

$config->task->dtable->fieldList['openedDate']['title']    = $lang->task->openedDate;
$config->task->dtable->fieldList['openedDate']['name']     = 'openedDate';
$config->task->dtable->fieldList['openedDate']['type']     = 'date';
$config->task->dtable->fieldList['openedDate']['sortType'] = true;

$config->task->dtable->fieldList['assignedTo']['title']       = $lang->task->assignedTo;
$config->task->dtable->fieldList['assignedTo']['name']        = 'assignedTo';
$config->task->dtable->fieldList['assignedTo']['type']        = 'assign';
$config->task->dtable->fieldList['assignedTo']['currentUser'] = $app->user->account;
$config->task->dtable->fieldList['assignedTo']['assignLink']  = helper::createLink('task', 'assignTo', "executionID={execution}&taskID={id}", '', true);
$config->task->dtable->fieldList['assignedTo']['sortType']    = true;

$config->task->dtable->fieldList['assignedDate']['title']    = $lang->task->assignedDate;
$config->task->dtable->fieldList['assignedDate']['name']     = 'assignedDate';
$config->task->dtable->fieldList['assignedDate']['type']     = 'date';
$config->task->dtable->fieldList['assignedDate']['sortType'] = true;

$config->task->dtable->fieldList['estStarted']['title']    = $lang->task->estStarted;
$config->task->dtable->fieldList['estStarted']['name']     = 'estStarted';
$config->task->dtable->fieldList['estStarted']['type']     = 'date';
$config->task->dtable->fieldList['estStarted']['sortType'] = true;

$config->task->dtable->fieldList['realStarted']['title']    = $lang->task->realStarted;
$config->task->dtable->fieldList['realStarted']['name']     = 'realStarted';
$config->task->dtable->fieldList['realStarted']['type']     = 'date';
$config->task->dtable->fieldList['realStarted']['sortType'] = true;

$config->task->dtable->fieldList['finishedBy']['title']    = $lang->task->finishedByAB;
$config->task->dtable->fieldList['finishedBy']['name']     = 'finishedBy';
$config->task->dtable->fieldList['finishedBy']['type']     = 'user';
$config->task->dtable->fieldList['finishedBy']['sortType'] = true;

$config->task->dtable->fieldList['finishedDate']['title']    = $lang->task->finishedDateAB;
$config->task->dtable->fieldList['finishedDate']['name']     = 'finishedDateAB';
$config->task->dtable->fieldList['finishedDate']['type']     = 'date';
$config->task->dtable->fieldList['finishedDate']['sortType'] = true;

$config->task->dtable->fieldList['deadline']['title']    = $lang->task->deadlineAB;
$config->task->dtable->fieldList['deadline']['name']     = 'deadline';
$config->task->dtable->fieldList['deadline']['type']     = 'date';
$config->task->dtable->fieldList['deadline']['sortType'] = true;

$config->task->dtable->fieldList['estimate']['title']    = $lang->task->estimateAB;
$config->task->dtable->fieldList['estimate']['name']     = 'estimate';
$config->task->dtable->fieldList['estimate']['type']     = 'number';
$config->task->dtable->fieldList['estimate']['sortType'] = true;

$config->task->dtable->fieldList['consumed']['title']    = $lang->task->consumedAB;
$config->task->dtable->fieldList['consumed']['name']     = 'consumed';
$config->task->dtable->fieldList['consumed']['type']     = 'number';
$config->task->dtable->fieldList['consumed']['sortType'] = true;

$config->task->dtable->fieldList['left']['title']    = $lang->task->leftAB;
$config->task->dtable->fieldList['left']['name']     = 'left';
$config->task->dtable->fieldList['left']['type']     = 'number';
$config->task->dtable->fieldList['left']['sortType'] = true;

$config->task->dtable->fieldList['progress']['title'] = $lang->task->progressAB;
$config->task->dtable->fieldList['progress']['name']  = 'progress';
$config->task->dtable->fieldList['progress']['type']  = 'progress';

$config->task->dtable->fieldList['closedBy']['title']    = $lang->task->closedBy;
$config->task->dtable->fieldList['closedBy']['name']     = 'closedBy';
$config->task->dtable->fieldList['closedBy']['type']     = 'user';
$config->task->dtable->fieldList['closedBy']['sortType'] = true;

$config->task->dtable->fieldList['closedDate']['title']    = $lang->task->closedDate;
$config->task->dtable->fieldList['closedDate']['name']     = 'closedDate';
$config->task->dtable->fieldList['closedDate']['type']     = 'datetime';
$config->task->dtable->fieldList['closedDate']['sortType'] = true;

$config->task->dtable->fieldList['closedReason']['title']    = $lang->task->closedReason;
$config->task->dtable->fieldList['closedReason']['name']     = 'closedReason';
$config->task->dtable->fieldList['closedReason']['width']    = '120';
$config->task->dtable->fieldList['closedReason']['type']     = 'category';
$config->task->dtable->fieldList['closedReason']['map']      = $lang->task->reasonList;
$config->task->dtable->fieldList['closedReason']['sortType'] = true;

$config->task->dtable->fieldList['canceledBy']['title']    = $lang->task->canceledBy;
$config->task->dtable->fieldList['canceledBy']['name']     = 'canceledBy';
$config->task->dtable->fieldList['canceledBy']['type']     = 'user';
$config->task->dtable->fieldList['canceledBy']['sortType'] = true;

$config->task->dtable->fieldList['canceledDate']['title']    = $lang->task->canceledDate;
$config->task->dtable->fieldList['canceledDate']['name']     = 'canceledDate';
$config->task->dtable->fieldList['canceledDate']['type']     = 'date';
$config->task->dtable->fieldList['canceledDate']['sortType'] = true;

$config->task->dtable->fieldList['lastEditedBy']['title']    = $lang->task->lastEditedBy;
$config->task->dtable->fieldList['lastEditedBy']['name']     = 'lastEditedBy';
$config->task->dtable->fieldList['lastEditedBy']['type']     = 'user';
$config->task->dtable->fieldList['lastEditedBy']['sortType'] = true;

$config->task->dtable->fieldList['lastEditedDate']['title']    = $lang->task->lastEditedDate;
$config->task->dtable->fieldList['lastEditedDate']['name']     = 'lastEditedDate';
$config->task->dtable->fieldList['lastEditedDate']['type']     = 'date';
$config->task->dtable->fieldList['lastEditedDate']['sortType'] = true;

$config->task->dtable->fieldList['activatedDate']['title']    = $lang->task->activatedDate;
$config->task->dtable->fieldList['activatedDate']['name']     = 'activatedDate';
$config->task->dtable->fieldList['activatedDate']['type']     = 'date';
$config->task->dtable->fieldList['activatedDate']['sortType'] = true;

$config->task->dtable->fieldList['story']['title']    = $lang->task->storyAB;
$config->task->dtable->fieldList['story']['name']     = 'storyTitle';
$config->task->dtable->fieldList['story']['width']    = '80';
$config->task->dtable->fieldList['story']['type']     = 'desc';
$config->task->dtable->fieldList['story']['sortType'] = true;

$config->task->dtable->fieldList['mailto']['title']    = $lang->task->mailto;
$config->task->dtable->fieldList['mailto']['name']     = 'mailto';
$config->task->dtable->fieldList['mailto']['type']     = 'user';
$config->task->dtable->fieldList['mailto']['sortType'] = true;

$config->task->dtable->fieldList['actions']['title'] = $lang->actions;
$config->task->dtable->fieldList['actions']['name']  = 'actions';
$config->task->dtable->fieldList['actions']['type']  = 'actions';
$config->task->dtable->fieldList['actions']['list']  = $config->task->actionList;
$config->task->dtable->fieldList['actions']['menu']  = array(array('confirmStoryChange'), array('start|restart', 'finish', 'close', 'recordWorkhour', 'edit', 'batchCreate'));

/* Record effort page. */
$config->task->effortTable = new stdclass();

$config->task->effortTable->fieldList['id']['title']    = $lang->idAB;
$config->task->effortTable->fieldList['id']['name']     = 'id';
$config->task->effortTable->fieldList['id']['checkbox'] = false;
$config->task->effortTable->fieldList['id']['width']    = '80';

$config->task->effortTable->fieldList['account']['title']    = $lang->task->recordedBy;
$config->task->effortTable->fieldList['account']['name']     = 'account';
$config->task->effortTable->fieldList['account']['checkbox'] = false;
$config->task->effortTable->fieldList['account']['width']    = '120';

$config->task->effortTable->fieldList['work']['title']    = $lang->task->work;
$config->task->effortTable->fieldList['work']['name']     = 'work';
$config->task->effortTable->fieldList['work']['checkbox'] = false;
$config->task->effortTable->fieldList['work']['width']    = '280';
$config->task->effortTable->fieldList['work']['flex']     = '1';

$config->task->effortTable->fieldList['consumed']['title']    = $lang->task->consumed;
$config->task->effortTable->fieldList['consumed']['name']     = 'consumed';
$config->task->effortTable->fieldList['consumed']['checkbox'] = false;
$config->task->effortTable->fieldList['consumed']['width']    = '80';

$config->task->effortTable->fieldList['left']['title']    = $lang->task->left;
$config->task->effortTable->fieldList['left']['name']     = 'consumed';
$config->task->effortTable->fieldList['left']['checkbox'] = false;
$config->task->effortTable->fieldList['left']['width']    = '80';

$config->task->effortTable->fieldList['actions']['title']    = $lang->actions;
$config->task->effortTable->fieldList['actions']['name']     = 'actions';
$config->task->effortTable->fieldList['actions']['fixed']    = 'right';
$config->task->effortTable->fieldList['actions']['width']    = '100';
$config->task->effortTable->fieldList['actions']['type']     = 'actions';
$config->task->effortTable->fieldList['actions']['sortType'] = false;

$config->task->effortTable->fieldList['actions']['actionsMap']['editEffort']['icon']  = 'edit';
$config->task->effortTable->fieldList['actions']['actionsMap']['editEffort']['hint']  = $lang->task->editEffort;
$config->task->effortTable->fieldList['actions']['actionsMap']['editEffort']['url']   = helper::createLink('task', 'editEffort', 'taskID={id}', '', true);
$config->task->effortTable->fieldList['actions']['actionsMap']['editEffort']['order'] = 5;
$config->task->effortTable->fieldList['actions']['actionsMap']['editEffort']['show']  = 'clickable';

$config->task->effortTable->fieldList['actions']['actionsMap']['deleteWorkhour']['icon']  = 'trash';
$config->task->effortTable->fieldList['actions']['actionsMap']['deleteWorkhour']['hint']  = $lang->task->deleteWorkhour;
$config->task->effortTable->fieldList['actions']['actionsMap']['deleteWorkhour']['url']   = helper::createLink('task', 'deleteWorkhour', 'taskID={id}');
$config->task->effortTable->fieldList['actions']['actionsMap']['deleteWorkhour']['order'] = 10;
$config->task->effortTable->fieldList['actions']['actionsMap']['deleteWorkhour']['show']  = 'clickable';

$config->task->dtable->importTask = new stdclass();

$config->task->dtable->importTask->fieldList['id']['title']    = $lang->idAB;
$config->task->dtable->importTask->fieldList['id']['name']     = 'id';
$config->task->dtable->importTask->fieldList['id']['type']     = 'checkID';
$config->task->dtable->importTask->fieldList['id']['sortType'] = 'desc';
$config->task->dtable->importTask->fieldList['id']['checkbox'] = true;

$config->task->dtable->importTask->fieldList['name']['title']       = $lang->task->name;
$config->task->dtable->importTask->fieldList['name']['name']        = 'name';
$config->task->dtable->importTask->fieldList['name']['type']        = 'nestedTitle';
$config->task->dtable->importTask->fieldList['name']['link']        = helper::createLink('task', 'view', 'taskID={id}', '', true);
$config->task->dtable->importTask->fieldList['name']['data-toggle'] = 'modal';

$config->task->dtable->importTask->fieldList['pri']['title']    = $lang->priAB;
$config->task->dtable->importTask->fieldList['pri']['name']     = 'pri';
$config->task->dtable->importTask->fieldList['pri']['type']     = 'pri';
$config->task->dtable->importTask->fieldList['pri']['sortType'] = true;
$config->task->dtable->importTask->fieldList['pri']['group']    = 1;

$config->task->dtable->importTask->fieldList['status']['title']     = $lang->statusAB;
$config->task->dtable->importTask->fieldList['status']['name']      = 'status';
$config->task->dtable->importTask->fieldList['status']['type']      = 'status';
$config->task->dtable->importTask->fieldList['status']['statusMap'] = $lang->task->statusList;
$config->task->dtable->importTask->fieldList['status']['sortType']  = true;
$config->task->dtable->importTask->fieldList['status']['group']     = 1;

$config->task->dtable->importTask->fieldList['assignedTo']['title']       = $lang->task->assignedTo;
$config->task->dtable->importTask->fieldList['assignedTo']['name']        = 'assignedTo';
$config->task->dtable->importTask->fieldList['assignedTo']['type']        = 'assign';
$config->task->dtable->importTask->fieldList['assignedTo']['currentUser'] = $app->user->account;
$config->task->dtable->importTask->fieldList['assignedTo']['assignLink']  = helper::createLink('task', 'assignTo', "executionID={execution}&taskID={id}", '', true);
$config->task->dtable->importTask->fieldList['assignedTo']['sortType']    = false;
$config->task->dtable->importTask->fieldList['assignedTo']['group']       = 2;

$config->task->dtable->importTask->fieldList['left']['title']    = $lang->task->leftAB;
$config->task->dtable->importTask->fieldList['left']['name']     = 'left';
$config->task->dtable->importTask->fieldList['left']['type']     = 'number';
$config->task->dtable->importTask->fieldList['left']['sortType'] = true;
$config->task->dtable->importTask->fieldList['left']['group']    = 2;

$config->task->dtable->importTask->fieldList['deadline']['title']    = $lang->task->deadlineAB;
$config->task->dtable->importTask->fieldList['deadline']['name']     = 'deadline';
$config->task->dtable->importTask->fieldList['deadline']['type']     = 'date';
$config->task->dtable->importTask->fieldList['deadline']['sortType'] = true;
$config->task->dtable->importTask->fieldList['deadline']['group']    = 2;

$config->task->dtable->importTask->fieldList['execution']['title']    = $lang->task->stage;
$config->task->dtable->importTask->fieldList['execution']['name']     = 'execution';
$config->task->dtable->importTask->fieldList['execution']['type']     = 'desc';
$config->task->dtable->importTask->fieldList['execution']['sortType'] = false;

$config->task->dtable->importTask->fieldList['story']['title']    = $lang->task->story;
$config->task->dtable->importTask->fieldList['story']['name']     = 'storyTitle';
$config->task->dtable->importTask->fieldList['story']['type']     = 'desc';
$config->task->dtable->importTask->fieldList['story']['sortType'] = false;
