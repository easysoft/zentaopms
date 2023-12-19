<?php
$config->task->dtable = new stdclass();

$config->task->dtable->fieldList['id']['title']    = $lang->idAB;
$config->task->dtable->fieldList['id']['type']     = 'checkID';
$config->task->dtable->fieldList['id']['sortType'] = true;
$config->task->dtable->fieldList['id']['checkbox'] = true;
$config->task->dtable->fieldList['id']['required'] = true;

$config->task->dtable->fieldList['name']['fixed']        = 'left';
$config->task->dtable->fieldList['name']['flex']         = 1;
$config->task->dtable->fieldList['name']['type']         = 'nestedTitle';
$config->task->dtable->fieldList['name']['nestedToggle'] = true;
$config->task->dtable->fieldList['name']['sortType']     = true;
$config->task->dtable->fieldList['name']['link']         = array('url' => array('module' => 'task', 'method' => 'view', 'params' => 'taskID={id}'), 'className' => 'text-inherit', 'style' => array('color' => 'var(--color-link)'));
$config->task->dtable->fieldList['name']['required']     = true;
$config->task->dtable->fieldList['name']['styleMap']     = array('--color-link' => 'color');
$config->task->dtable->fieldList['name']['data-app']     = $app->tab;

$config->task->dtable->fieldList['pri']['title']    = $lang->priAB;
$config->task->dtable->fieldList['pri']['type']     = 'pri';
$config->task->dtable->fieldList['pri']['sortType'] = true;
$config->task->dtable->fieldList['pri']['show']     = true;
$config->task->dtable->fieldList['pri']['group']    = 1;

$config->task->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->task->dtable->fieldList['status']['type']      = 'status';
$config->task->dtable->fieldList['status']['statusMap'] = $lang->task->statusList;
$config->task->dtable->fieldList['status']['sortType']  = true;
$config->task->dtable->fieldList['status']['show']      = true;
$config->task->dtable->fieldList['status']['group']     = 1;

$config->task->dtable->fieldList['type']['title']    = $lang->task->typeAB;
$config->task->dtable->fieldList['type']['type']     = 'category';
$config->task->dtable->fieldList['type']['map']      = $lang->task->typeList;
$config->task->dtable->fieldList['type']['sortType'] = true;
$config->task->dtable->fieldList['type']['group']    = 1;

$config->task->dtable->fieldList['branch']['title']    = $lang->branchName;
$config->task->dtable->fieldList['branch']['type']     = 'text';
$config->task->dtable->fieldList['branch']['sortType'] = false;
$config->task->dtable->fieldList['branch']['width']    = '100';
$config->task->dtable->fieldList['branch']['group']    = 1;

$config->task->dtable->fieldList['openedBy']['title']    = $lang->task->openedByAB;
$config->task->dtable->fieldList['openedBy']['type']     = 'user';
$config->task->dtable->fieldList['openedBy']['sortType'] = true;
$config->task->dtable->fieldList['openedBy']['group']    = 2;

$config->task->dtable->fieldList['openedDate']['type']     = 'date';
$config->task->dtable->fieldList['openedDate']['sortType'] = true;
$config->task->dtable->fieldList['openedDate']['group']    = 2;

$config->task->dtable->fieldList['assignedTo']['type']        = 'assign';
$config->task->dtable->fieldList['assignedTo']['currentUser'] = $app->user->account;
$config->task->dtable->fieldList['assignedTo']['assignLink']  = array('module' => 'task', 'method' => 'assignTo', 'params' => 'executionID={execution}&taskID={id}');
$config->task->dtable->fieldList['assignedTo']['sortType']    = true;
$config->task->dtable->fieldList['assignedTo']['show']        = true;
$config->task->dtable->fieldList['assignedTo']['group']       = 3;
$config->task->dtable->fieldList['assignedTo']['control']     = 'select';
$config->task->dtable->fieldList['assignedTo']['dataSource']  = array('module' => 'user', 'method' => 'getTeamMemberPairs', 'params' => '$executionID&execution');

$config->task->dtable->fieldList['assignedDate']['type']     = 'date';
$config->task->dtable->fieldList['assignedDate']['sortType'] = true;
$config->task->dtable->fieldList['assignedDate']['group']    = 3;

$config->task->dtable->fieldList['estStarted']['type']     = 'date';
$config->task->dtable->fieldList['estStarted']['sortType'] = true;
$config->task->dtable->fieldList['estStarted']['group']    = 4;

$config->task->dtable->fieldList['realStarted']['type']     = 'date';
$config->task->dtable->fieldList['realStarted']['sortType'] = true;
$config->task->dtable->fieldList['realStarted']['group']    = 4;

$config->task->dtable->fieldList['finishedBy']['title']    = $lang->task->finishedByAB;
$config->task->dtable->fieldList['finishedBy']['type']     = 'user';
$config->task->dtable->fieldList['finishedBy']['sortType'] = true;
$config->task->dtable->fieldList['finishedBy']['show']     = true;
$config->task->dtable->fieldList['finishedBy']['group']    = 4;

$config->task->dtable->fieldList['finishedDate']['title']    = $lang->task->finishedDateAB;
$config->task->dtable->fieldList['finishedDate']['type']     = 'date';
$config->task->dtable->fieldList['finishedDate']['sortType'] = true;
$config->task->dtable->fieldList['finishedDate']['group']    = 4;

$config->task->dtable->fieldList['deadline']['title']    = $lang->task->deadlineAB;
$config->task->dtable->fieldList['deadline']['type']     = 'date';
$config->task->dtable->fieldList['deadline']['sortType'] = true;
$config->task->dtable->fieldList['deadline']['show']     = true;
$config->task->dtable->fieldList['deadline']['group']    = 5;

$config->task->dtable->fieldList['estimate']['title']    = $lang->task->estimateAB;
$config->task->dtable->fieldList['estimate']['type']     = 'number';
$config->task->dtable->fieldList['estimate']['sortType'] = true;
$config->task->dtable->fieldList['estimate']['show']     = true;
$config->task->dtable->fieldList['estimate']['group']    = 5;

$config->task->dtable->fieldList['consumed']['title']    = $lang->task->consumedAB;
$config->task->dtable->fieldList['consumed']['type']     = 'number';
$config->task->dtable->fieldList['consumed']['sortType'] = true;
$config->task->dtable->fieldList['consumed']['show']     = true;
$config->task->dtable->fieldList['consumed']['group']    = 5;

$config->task->dtable->fieldList['left']['title']    = $lang->task->leftAB;
$config->task->dtable->fieldList['left']['type']     = 'number';
$config->task->dtable->fieldList['left']['sortType'] = true;
$config->task->dtable->fieldList['left']['show']     = true;
$config->task->dtable->fieldList['left']['group']    = 5;

$config->task->dtable->fieldList['progress']['title'] = $lang->task->progressAB;
$config->task->dtable->fieldList['progress']['type']  = 'progress';
$config->task->dtable->fieldList['progress']['show']  = true;
$config->task->dtable->fieldList['progress']['group'] = 5;

$config->task->dtable->fieldList['closedBy']['type']     = 'user';
$config->task->dtable->fieldList['closedBy']['sortType'] = true;
$config->task->dtable->fieldList['closedBy']['group']    = 6;

$config->task->dtable->fieldList['closedDate']['type']     = 'datetime';
$config->task->dtable->fieldList['closedDate']['sortType'] = true;
$config->task->dtable->fieldList['closedDate']['group']    = 6;

$config->task->dtable->fieldList['closedReason']['type']     = 'category';
$config->task->dtable->fieldList['closedReason']['map']      = $lang->task->reasonList;
$config->task->dtable->fieldList['closedReason']['sortType'] = true;
$config->task->dtable->fieldList['closedReason']['group']    = 6;

$config->task->dtable->fieldList['canceledBy']['type']     = 'user';
$config->task->dtable->fieldList['canceledBy']['sortType'] = true;
$config->task->dtable->fieldList['canceledBy']['group']    = 7;

$config->task->dtable->fieldList['canceledDate']['type']     = 'date';
$config->task->dtable->fieldList['canceledDate']['sortType'] = true;
$config->task->dtable->fieldList['canceledDate']['group']    = 7;

$config->task->dtable->fieldList['lastEditedBy']['type']     = 'user';
$config->task->dtable->fieldList['lastEditedBy']['sortType'] = true;
$config->task->dtable->fieldList['lastEditedBy']['group']    = 8;

$config->task->dtable->fieldList['lastEditedDate']['type']     = 'date';
$config->task->dtable->fieldList['lastEditedDate']['sortType'] = true;
$config->task->dtable->fieldList['lastEditedDate']['group']    = 8;

$config->task->dtable->fieldList['activatedDate']['type']     = 'date';
$config->task->dtable->fieldList['activatedDate']['sortType'] = true;
$config->task->dtable->fieldList['activatedDate']['group']    = 8;

$config->task->dtable->fieldList['story']['title']      = $lang->task->storyAB;
$config->task->dtable->fieldList['story']['name']       = 'storyTitle';
$config->task->dtable->fieldList['story']['type']       = 'desc';
$config->task->dtable->fieldList['story']['sortType']   = true;
$config->task->dtable->fieldList['story']['show']       = true;
$config->task->dtable->fieldList['story']['group']      = 9;
$config->task->dtable->fieldList['story']['dataSource'] = array('module' => 'story', 'method' => 'getExecutionStoryPairs', 'params' => '$executionID&0&all&&&active');

$config->task->dtable->fieldList['module']['title']      = 'module';
$config->task->dtable->fieldList['module']['control']    = 'select';
$config->task->dtable->fieldList['module']['dataSource'] = array('module' => 'tree', 'method' => 'getTaskOptionMenu', 'params' => '$executionID');
$config->task->dtable->fieldList['module']['display']    = false;

$config->task->dtable->fieldList['execution']['title']      = 'execution';
$config->task->dtable->fieldList['execution']['control']    = 'hidden';
$config->task->dtable->fieldList['execution']['type']       = 'html';
$config->task->dtable->fieldList['execution']['dataSource'] = array('module' => 'execution', 'method' => 'getPairs');
$config->task->dtable->fieldList['execution']['display']    = false;

$config->task->dtable->fieldList['project']['title']      = 'project';
$config->task->dtable->fieldList['project']['control']    = 'hidden';
$config->task->dtable->fieldList['project']['type']       = 'html';
$config->task->dtable->fieldList['project']['dataSource'] = array('module' => 'project', 'method' => 'getPairs');
$config->task->dtable->fieldList['project']['display']    = false;

$config->task->dtable->fieldList['mode']['title']   = 'mode';
$config->task->dtable->fieldList['mode']['control'] = 'hidden';
$config->task->dtable->fieldList['mode']['display'] = false;

$config->task->dtable->fieldList['desc']['title']   = 'desc';
$config->task->dtable->fieldList['desc']['control'] = 'textarea';
$config->task->dtable->fieldList['desc']['display'] = false;

$config->task->dtable->fieldList['mailto']['type']     = 'user';
$config->task->dtable->fieldList['mailto']['sortType'] = true;
$config->task->dtable->fieldList['mailto']['group']    = 9;

$config->task->dtable->fieldList['actions']['type']     = 'actions';
$config->task->dtable->fieldList['actions']['width']    = '160px';
$config->task->dtable->fieldList['actions']['list']     = $config->task->actionList;
$config->task->dtable->fieldList['actions']['menu']     = array(array('confirmStoryChange'), array('start|restart', 'finish', 'close', 'recordWorkhour', 'edit', 'batchCreate'));
$config->task->dtable->fieldList['actions']['required'] = true;

/* Record effort page. */
$config->task->effortTable = new stdclass();

$config->task->effortTable->fieldList['id']['title']    = $lang->idAB;
$config->task->effortTable->fieldList['id']['name']     = 'id';
$config->task->effortTable->fieldList['id']['checkbox'] = false;
$config->task->effortTable->fieldList['id']['width']    = '80';
$config->task->effortTable->fieldList['id']['group']    = '1';

$config->task->effortTable->fieldList['account']['title']    = $lang->task->recordedBy;
$config->task->effortTable->fieldList['account']['name']     = 'account';
$config->task->effortTable->fieldList['account']['checkbox'] = false;
$config->task->effortTable->fieldList['account']['width']    = '120';
$config->task->effortTable->fieldList['account']['group']    = '1';

$config->task->effortTable->fieldList['work']['title']    = $lang->task->work;
$config->task->effortTable->fieldList['work']['name']     = 'work';
$config->task->effortTable->fieldList['work']['checkbox'] = false;
$config->task->effortTable->fieldList['work']['width']    = '280';
$config->task->effortTable->fieldList['work']['flex']     = '1';
$config->task->effortTable->fieldList['work']['group']    = '2';

$config->task->effortTable->fieldList['consumed']['title']    = $lang->task->consumed;
$config->task->effortTable->fieldList['consumed']['name']     = 'consumed';
$config->task->effortTable->fieldList['consumed']['checkbox'] = false;
$config->task->effortTable->fieldList['consumed']['width']    = '80';
$config->task->effortTable->fieldList['consumed']['group']    = '3';

$config->task->effortTable->fieldList['left']['title']    = $lang->task->left;
$config->task->effortTable->fieldList['left']['name']     = 'left';
$config->task->effortTable->fieldList['left']['checkbox'] = false;
$config->task->effortTable->fieldList['left']['width']    = '80';
$config->task->effortTable->fieldList['left']['group']    = '3';

$config->task->effortTable->fieldList['actions']['title']    = $lang->actions;
$config->task->effortTable->fieldList['actions']['name']     = 'actions';
$config->task->effortTable->fieldList['actions']['fixed']    = 'right';
$config->task->effortTable->fieldList['actions']['minWidth'] = '80';
$config->task->effortTable->fieldList['actions']['type']     = 'actions';
$config->task->effortTable->fieldList['actions']['sortType'] = false;
$config->task->effortTable->fieldList['actions']['menu']     = array('editEffort', 'deleteWorkhour');

$config->task->effortTable->fieldList['actions']['list']['editEffort']['icon']  = 'edit';
$config->task->effortTable->fieldList['actions']['list']['editEffort']['hint']  = $lang->task->editEffort;
$config->task->effortTable->fieldList['actions']['list']['editEffort']['url']   = helper::createLink('task', 'editEffort', 'taskID={id}');
$config->task->effortTable->fieldList['actions']['list']['editEffort']['order'] = 5;
$config->task->effortTable->fieldList['actions']['list']['editEffort']['show']  = 'clickable';

$config->task->effortTable->fieldList['actions']['list']['deleteWorkhour']['icon']         = 'trash';
$config->task->effortTable->fieldList['actions']['list']['deleteWorkhour']['hint']         = $lang->task->deleteWorkhour;
$config->task->effortTable->fieldList['actions']['list']['deleteWorkhour']['order']        = 10;
$config->task->effortTable->fieldList['actions']['list']['deleteWorkhour']['show']         = 'clickable';
$config->task->effortTable->fieldList['actions']['list']['deleteWorkhour']['url']          = array('module' => 'task', 'method' => 'deleteWorkhour', 'params' => 'taskID={id}');
$config->task->effortTable->fieldList['actions']['list']['deleteWorkhour']['class']        = 'ajax-submit btn ghost square size-sm';
$config->task->effortTable->fieldList['actions']['list']['deleteWorkhour']['data-confirm'] = $lang->task->confirmDeleteEffort;

$config->task->dtable->importTask = new stdclass();

$config->task->dtable->importTask->fieldList['id']['title']    = $lang->idAB;
$config->task->dtable->importTask->fieldList['id']['name']     = 'id';
$config->task->dtable->importTask->fieldList['id']['type']     = 'checkID';
$config->task->dtable->importTask->fieldList['id']['sortType'] = true;
$config->task->dtable->importTask->fieldList['id']['checkbox'] = true;

$config->task->dtable->importTask->fieldList['name']['title']       = $lang->task->name;
$config->task->dtable->importTask->fieldList['name']['name']        = 'name';
$config->task->dtable->importTask->fieldList['name']['type']        = 'nestedTitle';
$config->task->dtable->importTask->fieldList['name']['link']        = array('module' => 'task', 'method' => 'view', 'params' => 'taskID={id}');
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
$config->task->dtable->importTask->fieldList['assignedTo']['assignLink']  = helper::createLink('task', 'assignTo', "executionID={execution}&taskID={id}");
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

$config->task->dtable->children = new stdclass();

$config->task->dtable->children->fieldList['id']             = $config->task->dtable->fieldList['id'];
$config->task->dtable->children->fieldList['id']['checkbox'] = false;
$config->task->dtable->children->fieldList['id']['sortType'] = false;
$config->task->dtable->children->fieldList['id']['name']     = 'id';

$config->task->dtable->children->fieldList['name']                 = $config->task->dtable->fieldList['name'];
$config->task->dtable->children->fieldList['name']['title']        = $lang->task->name;
$config->task->dtable->children->fieldList['name']['sortType']     = false;
$config->task->dtable->children->fieldList['name']['name']         = 'name';
$config->task->dtable->children->fieldList['name']['nestedToggle'] = false;

$config->task->dtable->children->fieldList['pri']             = $config->task->dtable->fieldList['pri'];
$config->task->dtable->children->fieldList['pri']['sortType'] = false;
$config->task->dtable->children->fieldList['pri']['name']     = 'pri';

$config->task->dtable->children->fieldList['deadline']             = $config->task->dtable->fieldList['deadline'];
$config->task->dtable->children->fieldList['deadline']['sortType'] = false;

$config->task->dtable->children->fieldList['assignedTo']             = $config->task->dtable->fieldList['assignedTo'];
$config->task->dtable->children->fieldList['assignedTo']['title']    = $lang->task->assignedTo;
$config->task->dtable->children->fieldList['assignedTo']['sortType'] = false;
$config->task->dtable->children->fieldList['assignedTo']['name']     = 'assignedTo';

$config->task->dtable->children->fieldList['status']             = $config->task->dtable->fieldList['status'];
$config->task->dtable->children->fieldList['status']['sortType'] = false;
$config->task->dtable->children->fieldList['status']['name']     = 'status';

$config->task->dtable->children->fieldList['actions']          = $config->task->dtable->fieldList['actions'];
$config->task->dtable->children->fieldList['actions']['title'] = $lang->actions;
$config->task->dtable->children->fieldList['actions']['name']  = 'actions';
