<?php
$config->task = new stdclass();
$config->task->batchCreate = 10;

$config->task->create   = new stdclass();
$config->task->edit     = new stdclass();
$config->task->start    = new stdclass();
$config->task->finish   = new stdclass();
$config->task->activate = new stdclass();

$config->task->create->requiredFields      = 'execution,name,type';
$config->task->edit->requiredFields        = $config->task->create->requiredFields;
$config->task->finish->requiredFields      = 'realStarted,finishedDate,currentConsumed';
$config->task->activate->requiredFields    = 'left';

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

$config->task->customCreateFields      = 'story,estStarted,deadline,mailto,pri,estimate';
$config->task->customBatchCreateFields = 'module,story,assignedTo,estimate,estStarted,deadline,desc,pri';
$config->task->customBatchEditFields   = 'module,assignedTo,status,pri,estimate,record,left,estStarted,deadline,finishedBy,canceledBy,closedBy,closedReason';
$config->task->defaultLoadCount        = 50;

$config->task->custom = new stdclass();
$config->task->custom->createFields      = $config->task->customCreateFields;
$config->task->custom->batchCreateFields = 'module,story,assignedTo,estimate,estStarted,deadline,desc,pri';
$config->task->custom->batchEditFields   = 'module,assignedTo,status,pri,estimate,record,left';

$config->task->excludeCheckFileds = ',pri,estStartedDitto,deadlineDitto,parent,regions,lanes,vision,region,';

$config->task->datatable = new stdclass();
$config->task->datatable->defaultField = array('id', 'name', 'pri', 'assignedTo', 'status', 'finishedBy', 'deadline', 'estimate', 'consumed', 'left', 'progress', 'actions');

global $app, $lang;
$config->task->datatable->fieldList['id']['title']    = 'idAB';
$config->task->datatable->fieldList['id']['fixed']    = 'left';
$config->task->datatable->fieldList['id']['minWidth'] = '70';
$config->task->datatable->fieldList['id']['required'] = 'yes';
$config->task->datatable->fieldList['id']['type']     = 'checkID';
$config->task->datatable->fieldList['id']['sortType'] = true;
$config->task->datatable->fieldList['id']['checkbox'] = true;

$config->task->datatable->fieldList['name']['title']        = 'name';
$config->task->datatable->fieldList['name']['required']     = 'yes';
$config->task->datatable->fieldList['name']['width']        = 'auto';
$config->task->datatable->fieldList['name']['type']         = 'html';
$config->task->datatable->fieldList['name']['fixed']        = 'left';
$config->task->datatable->fieldList['name']['sortType']     = true;
$config->task->datatable->fieldList['name']['nestedToggle'] = true;
$config->task->datatable->fieldList['name']['iconRender']   = true;

$config->task->datatable->fieldList['pri']['title']    = 'priAB';
$config->task->datatable->fieldList['pri']['fixed']    = 'left';
$config->task->datatable->fieldList['pri']['type']     = 'html';
$config->task->datatable->fieldList['pri']['width']    = '45';
$config->task->datatable->fieldList['pri']['required'] = 'no';
$config->task->datatable->fieldList['pri']['sortType'] = true;
$config->task->datatable->fieldList['pri']['name']     = $lang->task->pri;

$config->task->datatable->fieldList['assignedTo']['title']       = 'assignedTo';
$config->task->datatable->fieldList['assignedTo']['fixed']       = 'no';
$config->task->datatable->fieldList['assignedTo']['width']       = '100';
$config->task->datatable->fieldList['assignedTo']['type']        = 'html';
$config->task->datatable->fieldList['assignedTo']['required']    = 'no';
$config->task->datatable->fieldList['assignedTo']['currentUser'] = $app->user->account;
$config->task->datatable->fieldList['assignedTo']['assignLink']  = array('module' => 'task', 'method' => 'assignTo', 'params' => 'executionID={execution}&taskID={id}');
$config->task->datatable->fieldList['assignedTo']['sortType']    = true;
$config->task->datatable->fieldList['assignedTo']['control']     = 'select';
$config->task->datatable->fieldList['assignedTo']['dataSource']  = array('module' => 'user', 'method' => 'getTeamMemberPairs', 'params' => '$executionID&execution');

$config->task->datatable->fieldList['assignedDate']['title']    = 'assignedDate';
$config->task->datatable->fieldList['assignedDate']['fixed']    = 'no';
$config->task->datatable->fieldList['assignedDate']['type']     = 'date';
$config->task->datatable->fieldList['assignedDate']['width']    = '110';
$config->task->datatable->fieldList['assignedDate']['sortType'] = true;
$config->task->datatable->fieldList['assignedDate']['required'] = 'no';

$config->task->datatable->fieldList['type']['title']    = 'typeAB';
$config->task->datatable->fieldList['type']['type']     = 'category';
$config->task->datatable->fieldList['type']['width']    = '80';
$config->task->datatable->fieldList['type']['fixed']    = 'no';
$config->task->datatable->fieldList['type']['sortType'] = true;
$config->task->datatable->fieldList['type']['required'] = 'no';
$config->task->datatable->fieldList['type']['map']      = $lang->task->typeList;

$config->task->datatable->fieldList['status']['title']    = 'statusAB';
$config->task->datatable->fieldList['status']['type']     = 'html';
$config->task->datatable->fieldList['status']['width']    = '60';
$config->task->datatable->fieldList['status']['fixed']    = 'no';
$config->task->datatable->fieldList['status']['sortType'] = true;
$config->task->datatable->fieldList['status']['required'] = 'no';

$config->task->datatable->fieldList['finishedBy']['title']    = 'finishedByAB';
$config->task->datatable->fieldList['finishedBy']['type']     = 'user';
$config->task->datatable->fieldList['finishedBy']['width']    = '80';
$config->task->datatable->fieldList['finishedBy']['fixed']    = 'no';
$config->task->datatable->fieldList['finishedBy']['sortType'] = true;
$config->task->datatable->fieldList['finishedBy']['required'] = 'no';

$config->task->datatable->fieldList['deadline']['title']    = 'deadlineAB';
$config->task->datatable->fieldList['deadline']['type']     = 'html';
$config->task->datatable->fieldList['deadline']['width']    = '70';
$config->task->datatable->fieldList['deadline']['fixed']    = 'no';
$config->task->datatable->fieldList['deadline']['sortType'] = true;
$config->task->datatable->fieldList['deadline']['required'] = 'no';
$config->task->datatable->fieldList['deadline']['control']  = 'date';

$config->task->datatable->fieldList['estimate']['title']    = 'estimateAB';
$config->task->datatable->fieldList['estimate']['width']    = '65';
$config->task->datatable->fieldList['estimate']['sortType'] = true;
$config->task->datatable->fieldList['estimate']['fixed']    = 'no';
$config->task->datatable->fieldList['estimate']['required'] = 'no';

$config->task->datatable->fieldList['consumed']['title']    = 'consumedAB';
$config->task->datatable->fieldList['consumed']['width']    = '65';
$config->task->datatable->fieldList['consumed']['sortType'] = true;
$config->task->datatable->fieldList['consumed']['fixed']    = 'no';
$config->task->datatable->fieldList['consumed']['required'] = 'no';

$config->task->datatable->fieldList['left']['title']    = 'leftAB';
$config->task->datatable->fieldList['left']['width']    = '65';
$config->task->datatable->fieldList['left']['sortType'] = true;
$config->task->datatable->fieldList['left']['fixed']    = 'no';
$config->task->datatable->fieldList['left']['required'] = 'no';

$config->task->datatable->fieldList['progress']['title']    = 'progressAB';
$config->task->datatable->fieldList['progress']['width']    = '75';
$config->task->datatable->fieldList['progress']['type']     = 'progress';
$config->task->datatable->fieldList['progress']['fixed']    = 'no';
$config->task->datatable->fieldList['progress']['required'] = 'no';
$config->task->datatable->fieldList['progress']['sortType'] = false;
$config->task->datatable->fieldList['progress']['name']     = $lang->task->progress;

$config->task->datatable->fieldList['openedBy']['title']    = 'openedByAB';
$config->task->datatable->fieldList['openedBy']['type']     = 'user';
$config->task->datatable->fieldList['openedBy']['width']    = '90';
$config->task->datatable->fieldList['openedBy']['fixed']    = 'no';
$config->task->datatable->fieldList['openedBy']['sortType'] = true;
$config->task->datatable->fieldList['openedBy']['required'] = 'no';

$config->task->datatable->fieldList['openedDate']['title']    = 'openedDate';
$config->task->datatable->fieldList['openedDate']['type']     = 'date';
$config->task->datatable->fieldList['openedDate']['width']    = '110';
$config->task->datatable->fieldList['openedDate']['fixed']    = 'no';
$config->task->datatable->fieldList['openedDate']['sortType'] = true;
$config->task->datatable->fieldList['openedDate']['required'] = 'no';

$config->task->datatable->fieldList['estStarted']['title']    = 'estStarted';
$config->task->datatable->fieldList['estStarted']['type']     = 'date';
$config->task->datatable->fieldList['estStarted']['width']    = '90';
$config->task->datatable->fieldList['estStarted']['fixed']    = 'no';
$config->task->datatable->fieldList['estStarted']['sortType'] = true;
$config->task->datatable->fieldList['estStarted']['required'] = 'no';
$config->task->datatable->fieldList['estStarted']['control']  = 'date';

$config->task->datatable->fieldList['realStarted']['title']    = 'realStarted';
$config->task->datatable->fieldList['realStarted']['type']     = 'date';
$config->task->datatable->fieldList['realStarted']['width']    = '95';
$config->task->datatable->fieldList['realStarted']['fixed']    = 'no';
$config->task->datatable->fieldList['realStarted']['sortType'] = true;
$config->task->datatable->fieldList['realStarted']['required'] = 'no';

$config->task->datatable->fieldList['finishedDate']['title']    = 'finishedDateAB';
$config->task->datatable->fieldList['finishedDate']['type']     = 'date';
$config->task->datatable->fieldList['finishedDate']['width']    = '105';
$config->task->datatable->fieldList['finishedDate']['fixed']    = 'no';
$config->task->datatable->fieldList['finishedDate']['sortType'] = true;
$config->task->datatable->fieldList['finishedDate']['required'] = 'no';

$config->task->datatable->fieldList['canceledBy']['title']    = 'canceledBy';
$config->task->datatable->fieldList['canceledBy']['type']     = 'user';
$config->task->datatable->fieldList['canceledBy']['width']    = '110';
$config->task->datatable->fieldList['canceledBy']['fixed']    = 'no';
$config->task->datatable->fieldList['canceledBy']['sortType'] = true;
$config->task->datatable->fieldList['canceledBy']['required'] = 'no';

$config->task->datatable->fieldList['canceledDate']['title']    = 'canceledDate';
$config->task->datatable->fieldList['canceledDate']['type']     = 'date';
$config->task->datatable->fieldList['canceledDate']['width']    = '115';
$config->task->datatable->fieldList['canceledDate']['fixed']    = 'no';
$config->task->datatable->fieldList['canceledDate']['sortType'] = true;
$config->task->datatable->fieldList['canceledDate']['required'] = 'no';

$config->task->datatable->fieldList['closedBy']['title']    = 'closedBy';
$config->task->datatable->fieldList['closedBy']['type']     = 'user';
$config->task->datatable->fieldList['closedBy']['width']    = '100';
$config->task->datatable->fieldList['closedBy']['fixed']    = 'no';
$config->task->datatable->fieldList['closedBy']['sortType'] = true;
$config->task->datatable->fieldList['closedBy']['required'] = 'no';

$config->task->datatable->fieldList['closedDate']['title']    = 'closedDate';
$config->task->datatable->fieldList['closedDate']['type']     = 'date';
$config->task->datatable->fieldList['closedDate']['width']    = '115';
$config->task->datatable->fieldList['closedDate']['fixed']    = 'no';
$config->task->datatable->fieldList['closedDate']['sortType'] = true;
$config->task->datatable->fieldList['closedDate']['required'] = 'no';

$config->task->datatable->fieldList['closedReason']['title']    = 'closedReason';
$config->task->datatable->fieldList['closedReason']['type']     = 'category';
$config->task->datatable->fieldList['closedReason']['width']    = '120';
$config->task->datatable->fieldList['closedReason']['fixed']    = 'no';
$config->task->datatable->fieldList['closedReason']['sortType'] = true;
$config->task->datatable->fieldList['closedReason']['required'] = 'no';
$config->task->datatable->fieldList['closedReason']['map']      = $lang->task->reasonList;

$config->task->datatable->fieldList['lastEditedBy']['title']    = 'lastEditedBy';
$config->task->datatable->fieldList['lastEditedBy']['width']    = '95';
$config->task->datatable->fieldList['lastEditedBy']['fixed']    = 'no';
$config->task->datatable->fieldList['lastEditedBy']['sortType'] = true;
$config->task->datatable->fieldList['lastEditedBy']['required'] = 'no';

$config->task->datatable->fieldList['lastEditedDate']['title']    = 'lastEditedDate';
$config->task->datatable->fieldList['lastEditedDate']['width']    = '120';
$config->task->datatable->fieldList['lastEditedDate']['type']     = 'date';
$config->task->datatable->fieldList['lastEditedDate']['fixed']    = 'no';
$config->task->datatable->fieldList['lastEditedDate']['sortType'] = true;
$config->task->datatable->fieldList['lastEditedDate']['required'] = 'no';

$config->task->datatable->fieldList['activatedDate']['title']    = 'activatedDate';
$config->task->datatable->fieldList['activatedDate']['width']    = '90';
$config->task->datatable->fieldList['activatedDate']['type']     = 'date';
$config->task->datatable->fieldList['activatedDate']['fixed']    = 'no';
$config->task->datatable->fieldList['activatedDate']['sortType'] = true;
$config->task->datatable->fieldList['activatedDate']['required'] = 'no';

$config->task->datatable->fieldList['story']['title']      = "storyAB";
$config->task->datatable->fieldList['story']['width']      = '80';
$config->task->datatable->fieldList['story']['fixed']      = 'no';
$config->task->datatable->fieldList['story']['sortType']   = true;
$config->task->datatable->fieldList['story']['required']   = 'no';
$config->task->datatable->fieldList['story']['name']       = $lang->task->story;
$config->task->datatable->fieldList['story']['type']       = 'html';
$config->task->datatable->fieldList['story']['control']    = 'select';
$config->task->datatable->fieldList['story']['dataSource'] = array('module' => 'story', 'method' => 'getExecutionStoryPairs', 'params' => '$executionID&0&all&&&active');

$config->task->datatable->fieldList['mailto']['title']    = 'mailto';
$config->task->datatable->fieldList['mailto']['width']    = '100';
$config->task->datatable->fieldList['mailto']['fixed']    = 'no';
$config->task->datatable->fieldList['mailto']['sortType'] = true;
$config->task->datatable->fieldList['mailto']['required'] = 'no';

$config->task->datatable->fieldList['module']['title']      = 'module';
$config->task->datatable->fieldList['module']['control']    = 'select';
$config->task->datatable->fieldList['module']['dataSource'] = array('module' => 'tree', 'method' => 'getTaskOptionMenu', 'params' => '$executionID');
$config->task->datatable->fieldList['module']['display']    = false;

$config->task->datatable->fieldList['execution']['title']      = 'execution';
$config->task->datatable->fieldList['execution']['control']    = 'hidden';
$config->task->datatable->fieldList['execution']['type']       = 'html';
$config->task->datatable->fieldList['execution']['dataSource'] = array('module' => 'execution', 'method' => 'getPairs');
$config->task->datatable->fieldList['execution']['display']    = false;

$config->task->datatable->fieldList['project']['title']      = 'project';
$config->task->datatable->fieldList['project']['control']    = 'hidden';
$config->task->datatable->fieldList['project']['type']       = 'html';
$config->task->datatable->fieldList['project']['dataSource'] = array('module' => 'project', 'method' => 'getPairs');
$config->task->datatable->fieldList['project']['display']    = false;

$config->task->datatable->fieldList['mode']['title']   = 'mode';
$config->task->datatable->fieldList['mode']['control'] = 'hidden';
$config->task->datatable->fieldList['mode']['display'] = false;

$config->task->datatable->fieldList['desc']['title']   = 'desc';
$config->task->datatable->fieldList['desc']['control'] = 'textarea';
$config->task->datatable->fieldList['desc']['display'] = false;

$config->task->datatable->fieldList['actions']['title']    = 'actions';
$config->task->datatable->fieldList['actions']['type']     = 'html';
$config->task->datatable->fieldList['actions']['fixed']    = 'right';
$config->task->datatable->fieldList['actions']['width']    = '180';
$config->task->datatable->fieldList['actions']['required'] = 'yes';
