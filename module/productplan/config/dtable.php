<?php
global $app, $lang;
$config->productplan->dtable = new stdclass();

$config->productplan->dtable->fieldList['id']['name']     = 'id';
$config->productplan->dtable->fieldList['id']['title']    = $lang->idAB;
$config->productplan->dtable->fieldList['id']['type']     = 'checkID';
$config->productplan->dtable->fieldList['id']['fixed']    = 'left';
$config->productplan->dtable->fieldList['id']['checkbox'] = true;
$config->productplan->dtable->fieldList['id']['sortType'] = true;
$config->productplan->dtable->fieldList['id']['align']    = 'left';
$config->productplan->dtable->fieldList['id']['group']    = 'g1';

$rawModule = empty($app->rawModule) ? 'productplan' : $app->rawModule;
$config->productplan->dtable->fieldList['title']['name']         = 'title';
$config->productplan->dtable->fieldList['title']['title']        = $lang->productplan->title;
$config->productplan->dtable->fieldList['title']['type']         = 'title';
$config->productplan->dtable->fieldList['title']['link']         = helper::createLink($rawModule, 'view', 'planID={id}');
$config->productplan->dtable->fieldList['title']['fixed']        = 'left';
$config->productplan->dtable->fieldList['title']['sortType']     = true;
$config->productplan->dtable->fieldList['title']['align']        = 'left';
$config->productplan->dtable->fieldList['title']['nestedToggle'] = true;
$config->productplan->dtable->fieldList['title']['group']        = 'g1';

$config->productplan->dtable->fieldList['status']['name']      = 'status';
$config->productplan->dtable->fieldList['status']['title']     = $lang->productplan->status;
$config->productplan->dtable->fieldList['status']['type']      = 'status';
$config->productplan->dtable->fieldList['status']['sortType']  = true;
$config->productplan->dtable->fieldList['status']['align']     = 'left';
$config->productplan->dtable->fieldList['status']['statusMap'] = $lang->productplan->statusList;
$config->productplan->dtable->fieldList['status']['group']     = 'g2';
$config->productplan->dtable->fieldList['status']['show']      = true;

$config->productplan->dtable->fieldList['branchName']['name']     = 'branchName';
$config->productplan->dtable->fieldList['branchName']['title']    = '';
$config->productplan->dtable->fieldList['branchName']['type']     = 'text';
$config->productplan->dtable->fieldList['branchName']['sortType'] = true;
$config->productplan->dtable->fieldList['branchName']['group']    = 'g3';
$config->productplan->dtable->fieldList['branchName']['show']     = true;

$config->productplan->dtable->fieldList['begin']['name']     = 'begin';
$config->productplan->dtable->fieldList['begin']['title']    = $lang->productplan->begin;
$config->productplan->dtable->fieldList['begin']['type']     = 'date';
$config->productplan->dtable->fieldList['begin']['sortType'] = true;
$config->productplan->dtable->fieldList['begin']['group']    = 'g4';
$config->productplan->dtable->fieldList['begin']['show']     = true;

$config->productplan->dtable->fieldList['end']['name']     = 'end';
$config->productplan->dtable->fieldList['end']['title']    = $lang->productplan->end;
$config->productplan->dtable->fieldList['end']['type']     = 'date';
$config->productplan->dtable->fieldList['end']['sortType'] = true;
$config->productplan->dtable->fieldList['end']['group']    = 'g4';
$config->productplan->dtable->fieldList['end']['show']     = true;

$config->productplan->dtable->fieldList['stories']['name']     = 'stories';
$config->productplan->dtable->fieldList['stories']['title']    = $lang->productplan->stories;
$config->productplan->dtable->fieldList['stories']['type']     = 'number';
$config->productplan->dtable->fieldList['stories']['sortType'] = false;
$config->productplan->dtable->fieldList['stories']['width']    = 84;
$config->productplan->dtable->fieldList['stories']['group']    = 'g5';
$config->productplan->dtable->fieldList['stories']['show']     = true;

$config->productplan->dtable->fieldList['bugs']['name']     = 'bugs';
$config->productplan->dtable->fieldList['bugs']['title']    = $lang->productplan->bugs;
$config->productplan->dtable->fieldList['bugs']['type']     = 'number';
$config->productplan->dtable->fieldList['bugs']['sortType'] = false;
$config->productplan->dtable->fieldList['bugs']['group']    = 'g5';
$config->productplan->dtable->fieldList['bugs']['show']     = true;

$config->productplan->dtable->fieldList['hour']['name']     = 'hour';
$config->productplan->dtable->fieldList['hour']['title']    = $lang->productplan->hour;
$config->productplan->dtable->fieldList['hour']['type']     = 'number';
$config->productplan->dtable->fieldList['hour']['sortType'] = false;
$config->productplan->dtable->fieldList['hour']['group']    = 'g5';
$config->productplan->dtable->fieldList['hour']['show']     = true;

$config->productplan->dtable->fieldList['execution']['name']     = 'execution';
$config->productplan->dtable->fieldList['execution']['title']    = $lang->productplan->execution;
$config->productplan->dtable->fieldList['execution']['type']     = 'number';
$config->productplan->dtable->fieldList['execution']['sortType'] = false;
$config->productplan->dtable->fieldList['execution']['group']    = 'g6';
$config->productplan->dtable->fieldList['execution']['show']     = true;

$config->productplan->dtable->fieldList['desc']['name']     = 'desc';
$config->productplan->dtable->fieldList['desc']['title']    = $lang->productplan->desc;
$config->productplan->dtable->fieldList['desc']['width']    = '160';
$config->productplan->dtable->fieldList['desc']['type']     = 'html';
$config->productplan->dtable->fieldList['desc']['hint']     = true;
$config->productplan->dtable->fieldList['desc']['sortType'] = false;
$config->productplan->dtable->fieldList['desc']['group']    = 'g7';
$config->productplan->dtable->fieldList['desc']['show']     = true;

$config->productplan->dtable->fieldList['actions']['name']     = 'actions';
$config->productplan->dtable->fieldList['actions']['title']    = $lang->actions;
$config->productplan->dtable->fieldList['actions']['fixed']    = 'right';
$config->productplan->dtable->fieldList['actions']['required'] = true;
$config->productplan->dtable->fieldList['actions']['width']    = 'auto';
$config->productplan->dtable->fieldList['actions']['type']     = 'actions';
$config->productplan->dtable->fieldList['actions']['minWidth'] = 200;
$config->productplan->dtable->fieldList['actions']['list']     = $config->productplan->actionList;
$config->productplan->dtable->fieldList['actions']['menu']     = array(array('start|finish|close|activate', 'other' => array('finish', 'close', 'activate')), 'createExecution', 'divider', 'linkStory', 'linkBug', 'edit', 'more' => array('create', 'delete'));

/* view页面需求列表高级表格字段定义。 */
$config->productplan->view = new stdclass();
$config->productplan->view->dtable = new stdclass();

$config->productplan->view->dtable->fieldList['id']['title']    = $lang->idAB;
$config->productplan->view->dtable->fieldList['id']['type']     = 'checkID';
$config->productplan->view->dtable->fieldList['id']['checkbox'] = true;
$config->productplan->view->dtable->fieldList['id']['show']     = true;
$config->productplan->view->dtable->fieldList['id']['sortType'] = true;
$config->productplan->view->dtable->fieldList['id']['group']    = 1;

$config->productplan->view->dtable->fieldList['title']['title']        = $lang->story->title;
$config->productplan->view->dtable->fieldList['title']['type']         = 'title';
$config->productplan->view->dtable->fieldList['title']['nestedToggle'] = true;
$config->productplan->view->dtable->fieldList['title']['link']         = array('url' => helper::createLink('story', 'view', 'storyID={id}'));
$config->productplan->view->dtable->fieldList['title']['sortType']     = true;
$config->productplan->view->dtable->fieldList['title']['minWidth']     = '342';
$config->productplan->view->dtable->fieldList['title']['show']         = true;
$config->productplan->view->dtable->fieldList['title']['group']        = 1;
$config->productplan->view->dtable->fieldList['title']['styleMap']     = array('--color-link' => 'color');

$config->productplan->view->dtable->fieldList['pri']['title']    = $lang->priAB;
$config->productplan->view->dtable->fieldList['pri']['fixed']    = 'left';
$config->productplan->view->dtable->fieldList['pri']['sortType'] = true;
$config->productplan->view->dtable->fieldList['pri']['type']     = 'pri';
$config->productplan->view->dtable->fieldList['pri']['priList']  = $lang->story->priList;
$config->productplan->view->dtable->fieldList['pri']['show']     = true;
$config->productplan->view->dtable->fieldList['pri']['group']    = 2;

$config->productplan->view->dtable->fieldList['module']['title']    = $lang->story->module;
$config->productplan->view->dtable->fieldList['module']['type']     = 'text';
$config->productplan->view->dtable->fieldList['module']['sortType'] = true;
$config->productplan->view->dtable->fieldList['module']['show']     = true;
$config->productplan->view->dtable->fieldList['module']['group']    = 3;

$config->productplan->view->dtable->fieldList['branch']['title']      = $lang->story->branch;
$config->productplan->view->dtable->fieldList['branch']['sortType']   = true;
$config->productplan->view->dtable->fieldList['branch']['width']      = '100';
$config->productplan->view->dtable->fieldList['branch']['group']      = 3;
$config->productplan->view->dtable->fieldList['branch']['control']    = 'select';

$config->productplan->view->dtable->fieldList['category']['title']    = $lang->story->category;
$config->productplan->view->dtable->fieldList['category']['sortType'] = true;
$config->productplan->view->dtable->fieldList['category']['type']     = 'category';
$config->productplan->view->dtable->fieldList['category']['group']    = 4;

$config->productplan->view->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->productplan->view->dtable->fieldList['status']['sortType']  = true;
$config->productplan->view->dtable->fieldList['status']['type']      = 'status';
$config->productplan->view->dtable->fieldList['status']['statusMap'] = $lang->story->statusList;
$config->productplan->view->dtable->fieldList['status']['show']      = true;
$config->productplan->view->dtable->fieldList['status']['group']     = 4;

$config->productplan->view->dtable->fieldList['openedBy']['title']    = $lang->story->openedByAB;
$config->productplan->view->dtable->fieldList['openedBy']['sortType'] = true;
$config->productplan->view->dtable->fieldList['openedBy']['type']     = 'user';
$config->productplan->view->dtable->fieldList['openedBy']['show']     = true;
$config->productplan->view->dtable->fieldList['openedBy']['group']    = 5;

$config->productplan->view->dtable->fieldList['openedDate']['title']    = $lang->story->openedDate;
$config->productplan->view->dtable->fieldList['openedDate']['sortType'] = true;
$config->productplan->view->dtable->fieldList['openedDate']['type']     = 'date';
$config->productplan->view->dtable->fieldList['openedDate']['group']    = 5;

$config->productplan->view->dtable->fieldList['estimate']['title']    = $lang->story->estimateAB;
$config->productplan->view->dtable->fieldList['estimate']['sortType'] = true;
$config->productplan->view->dtable->fieldList['estimate']['type']     = 'number';
$config->productplan->view->dtable->fieldList['estimate']['show']     = true;
$config->productplan->view->dtable->fieldList['estimate']['group']    = 5;

$config->productplan->view->dtable->fieldList['reviewedBy']['title']    = $lang->story->reviewer;
$config->productplan->view->dtable->fieldList['reviewedBy']['type']     = 'text';
$config->productplan->view->dtable->fieldList['reviewedBy']['width']    = '100';
$config->productplan->view->dtable->fieldList['reviewedBy']['sortType'] = false;
$config->productplan->view->dtable->fieldList['reviewedBy']['group']    = 5;

$config->productplan->view->dtable->fieldList['reviewedDate']['title']    = $lang->story->reviewedDate;
$config->productplan->view->dtable->fieldList['reviewedDate']['sortType'] = true;
$config->productplan->view->dtable->fieldList['reviewedDate']['type']     = 'date';
$config->productplan->view->dtable->fieldList['reviewedDate']['group']    = 5;

$config->productplan->view->dtable->fieldList['stage']['title']     = $lang->story->stageAB;
$config->productplan->view->dtable->fieldList['stage']['sortType']  = true;
$config->productplan->view->dtable->fieldList['stage']['type']      = 'status';
$app->loadLang('requirement');
$config->productplan->view->dtable->fieldList['stage']['statusMap'] = $lang->story->stageList + $lang->requirement->stageList;
$config->productplan->view->dtable->fieldList['stage']['show']      = true;
$config->productplan->view->dtable->fieldList['stage']['group']     = 6;

$config->productplan->view->dtable->fieldList['assignedTo']['title']       = $lang->story->assignedTo;
$config->productplan->view->dtable->fieldList['assignedTo']['sortType']    = true;
$config->productplan->view->dtable->fieldList['assignedTo']['currentUser'] = $app->user->account;
$config->productplan->view->dtable->fieldList['assignedTo']['assignLink']  = array('module' => 'story', 'method' => 'assignTo', 'params' => 'storyID={id}');
$config->productplan->view->dtable->fieldList['assignedTo']['type']        = 'assign';
$config->productplan->view->dtable->fieldList['assignedTo']['show']        = true;
$config->productplan->view->dtable->fieldList['assignedTo']['group']       = 6;

$config->productplan->view->dtable->fieldList['assignedDate']['title']    = $lang->story->assignedDate;
$config->productplan->view->dtable->fieldList['assignedDate']['sortType'] = true;
$config->productplan->view->dtable->fieldList['assignedDate']['type']     = 'date';
$config->productplan->view->dtable->fieldList['assignedDate']['group']    = 6;

$config->productplan->view->dtable->fieldList['closedBy']['title']    = $lang->story->closedBy;
$config->productplan->view->dtable->fieldList['closedBy']['sortType'] = true;
$config->productplan->view->dtable->fieldList['closedBy']['type']     = 'user';
$config->productplan->view->dtable->fieldList['closedBy']['group']    = 8;

$config->productplan->view->dtable->fieldList['closedDate']['title']    = $lang->story->closedDate;
$config->productplan->view->dtable->fieldList['closedDate']['sortType'] = true;
$config->productplan->view->dtable->fieldList['closedDate']['type']     = 'date';
$config->productplan->view->dtable->fieldList['closedDate']['group']    = 8;

$config->productplan->view->dtable->fieldList['closedReason']['title']      = $lang->story->closedReason;
$config->productplan->view->dtable->fieldList['closedReason']['sortType']   = true;
$config->productplan->view->dtable->fieldList['closedReason']['width']      = '90';
$config->productplan->view->dtable->fieldList['closedReason']['group']      = 8;
$config->productplan->view->dtable->fieldList['closedReason']['dataSource'] = array('lang' => 'reasonList');

$config->productplan->view->dtable->fieldList['lastEditedBy']['title']    = $lang->story->lastEditedBy;
$config->productplan->view->dtable->fieldList['lastEditedBy']['sortType'] = true;
$config->productplan->view->dtable->fieldList['lastEditedBy']['type']     = 'user';
$config->productplan->view->dtable->fieldList['lastEditedBy']['group']    = 9;

$config->productplan->view->dtable->fieldList['lastEditedDate']['title']    = $lang->story->lastEditedDate;
$config->productplan->view->dtable->fieldList['lastEditedDate']['sortType'] = true;
$config->productplan->view->dtable->fieldList['lastEditedDate']['type']     = 'date';
$config->productplan->view->dtable->fieldList['lastEditedDate']['group']    = 9;

$config->productplan->view->dtable->fieldList['source']['title']    = $lang->story->source;
$config->productplan->view->dtable->fieldList['source']['sortType'] = true;
$config->productplan->view->dtable->fieldList['source']['width']    = '90';
$config->productplan->view->dtable->fieldList['source']['group']    = 10;

$config->productplan->view->dtable->fieldList['keywords']['title']    = $lang->story->keywords;
$config->productplan->view->dtable->fieldList['keywords']['sortType'] = true;
$config->productplan->view->dtable->fieldList['keywords']['width']    = '100';
$config->productplan->view->dtable->fieldList['keywords']['group']    = 10;

$config->productplan->view->dtable->fieldList['taskCount']['title']       = 'T';
$config->productplan->view->dtable->fieldList['taskCount']['sortType']    = false;
$config->productplan->view->dtable->fieldList['taskCount']['width']       = '30';
$config->productplan->view->dtable->fieldList['taskCount']['type']        = 'text';
$config->productplan->view->dtable->fieldList['taskCount']['link']        = array('module' => 'story', 'method' => 'tasks', 'params' => 'storyID={id}');
$config->productplan->view->dtable->fieldList['taskCount']['data-toggle'] = 'modal';
$config->productplan->view->dtable->fieldList['taskCount']['group']       = 7;

$config->productplan->view->dtable->fieldList['bugCount']['title']       = 'B';
$config->productplan->view->dtable->fieldList['bugCount']['sortType']    = false;
$config->productplan->view->dtable->fieldList['bugCount']['width']       = '30';
$config->productplan->view->dtable->fieldList['bugCount']['type']        = 'text';
$config->productplan->view->dtable->fieldList['bugCount']['link']        = array('module' => 'story', 'method' => 'bugs', 'params' => 'storyID={id}');
$config->productplan->view->dtable->fieldList['bugCount']['data-toggle'] = 'modal';
$config->productplan->view->dtable->fieldList['bugCount']['group']       = 7;

$config->productplan->view->dtable->fieldList['caseCount']['title']       = 'C';
$config->productplan->view->dtable->fieldList['caseCount']['sortType']    = false;
$config->productplan->view->dtable->fieldList['caseCount']['width']       = '30';
$config->productplan->view->dtable->fieldList['caseCount']['type']        = 'text';
$config->productplan->view->dtable->fieldList['caseCount']['link']        = array('module' => 'story', 'method' => 'cases', 'params' => 'storyID={id}');
$config->productplan->view->dtable->fieldList['caseCount']['data-toggle'] = 'modal';
$config->productplan->view->dtable->fieldList['caseCount']['group']       = 7;

$config->productplan->view->dtable->fieldList['sourceNote']['title']    = $lang->story->sourceNote;
$config->productplan->view->dtable->fieldList['sourceNote']['sortType'] = true;
$config->productplan->view->dtable->fieldList['sourceNote']['width']    = '90';
$config->productplan->view->dtable->fieldList['sourceNote']['group']    = 10;

$config->productplan->view->dtable->fieldList['feedbackBy']['title']    = $lang->story->feedbackBy;
$config->productplan->view->dtable->fieldList['feedbackBy']['sortType'] = true;
$config->productplan->view->dtable->fieldList['feedbackBy']['width']    = '90';
$config->productplan->view->dtable->fieldList['feedbackBy']['group']    = 11;

$config->productplan->view->dtable->fieldList['notifyEmail']['title']    = $lang->story->notifyEmail;
$config->productplan->view->dtable->fieldList['notifyEmail']['sortType'] = true;
$config->productplan->view->dtable->fieldList['notifyEmail']['width']    = '100';
$config->productplan->view->dtable->fieldList['notifyEmail']['group']    = 11;

$config->productplan->view->dtable->fieldList['mailto']['title']    = $lang->story->mailto;
$config->productplan->view->dtable->fieldList['mailto']['sortType'] = true;
$config->productplan->view->dtable->fieldList['mailto']['width']    = '100';
$config->productplan->view->dtable->fieldList['mailto']['group']    = 11;

$config->productplan->view->dtable->fieldList['version']['title']    = $lang->story->version;
$config->productplan->view->dtable->fieldList['version']['sortType'] = true;
$config->productplan->view->dtable->fieldList['version']['type']     = 'number';
$config->productplan->view->dtable->fieldList['version']['group']    = 11;

$config->productplan->view->dtable->fieldList['childItem']['title']    = $lang->story->childItem;
$config->productplan->view->dtable->fieldList['childItem']['sortType'] = false;
$config->productplan->view->dtable->fieldList['childItem']['type']     = 'text';
$config->productplan->view->dtable->fieldList['childItem']['group']    = 6;

$config->productplan->view->dtable->fieldList['activatedDate']['title']    = $lang->story->activatedDate;
$config->productplan->view->dtable->fieldList['activatedDate']['sortType'] = true;
$config->productplan->view->dtable->fieldList['activatedDate']['type']     = 'date';
$config->productplan->view->dtable->fieldList['activatedDate']['group']    = 12;

$config->productplan->view->dtable->fieldList['actions']['title']    = $lang->actions;
$config->productplan->view->dtable->fieldList['actions']['width']    = 'auto';
$config->productplan->view->dtable->fieldList['actions']['type']     = 'actions';

/* view页面Bug列表高级表格字段定义。 */
$app->loadLang('bug');
$config->productplan->viewBug = new stdclass();
$config->productplan->viewBug->dtable = new stdclass();

$config->productplan->viewBug->dtable->fieldList['id']['title']    = $lang->idAB;
$config->productplan->viewBug->dtable->fieldList['id']['type']     = 'checkID';
$config->productplan->viewBug->dtable->fieldList['id']['checkbox'] = true;
$config->productplan->viewBug->dtable->fieldList['id']['show']     = true;
$config->productplan->viewBug->dtable->fieldList['id']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['module']['title']    = $lang->bug->module;
$config->productplan->viewBug->dtable->fieldList['module']['type']     = 'text';
$config->productplan->viewBug->dtable->fieldList['module']['show']     = false;
$config->productplan->viewBug->dtable->fieldList['module']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['title']['title']    = $lang->bug->title;
$config->productplan->viewBug->dtable->fieldList['title']['type']     = 'title';
$config->productplan->viewBug->dtable->fieldList['title']['link']     = array('module' => 'bug', 'method' => 'view', 'params' => 'bugID={id}');
$config->productplan->viewBug->dtable->fieldList['title']['sortType'] = true;
$config->productplan->viewBug->dtable->fieldList['title']['minWidth'] = '342';
$config->productplan->viewBug->dtable->fieldList['title']['show']     = true;

$config->productplan->viewBug->dtable->fieldList['severity']['title']        = $lang->bug->severity;
$config->productplan->viewBug->dtable->fieldList['severity']['type']         = 'severity';
$config->productplan->viewBug->dtable->fieldList['severity']['sortType']     = true;
$config->productplan->viewBug->dtable->fieldList['severity']['severityList'] = $lang->bug->severityList;

$config->productplan->viewBug->dtable->fieldList['pri']['title']    = $lang->bug->pri;
$config->productplan->viewBug->dtable->fieldList['pri']['fixed']    = 'left';
$config->productplan->viewBug->dtable->fieldList['pri']['sortType'] = true;
$config->productplan->viewBug->dtable->fieldList['pri']['type']     = 'pri';
$config->productplan->viewBug->dtable->fieldList['pri']['priList']  = $lang->bug->priList;
$config->productplan->viewBug->dtable->fieldList['pri']['show']     = true;

$config->productplan->viewBug->dtable->fieldList['status']['title']     = $lang->bug->abbr->status;
$config->productplan->viewBug->dtable->fieldList['status']['type']      = 'status';
$config->productplan->viewBug->dtable->fieldList['status']['statusMap'] = $lang->bug->statusList;
$config->productplan->viewBug->dtable->fieldList['status']['show']      = true;
$config->productplan->viewBug->dtable->fieldList['status']['sortType']  = true;

$config->productplan->viewBug->dtable->fieldList['type']['title']    = $lang->bug->type;
$config->productplan->viewBug->dtable->fieldList['type']['type']     = 'category';
$config->productplan->viewBug->dtable->fieldList['type']['map']      = $lang->bug->typeList;
$config->productplan->viewBug->dtable->fieldList['type']['flex']     = false;
$config->productplan->viewBug->dtable->fieldList['type']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['product']['title']   = $lang->bug->product;
$config->productplan->viewBug->dtable->fieldList['product']['display'] = false;

$config->productplan->viewBug->dtable->fieldList['branch']['title']    = $lang->bug->branch;
$config->productplan->viewBug->dtable->fieldList['branch']['type']     = 'text';
$config->productplan->viewBug->dtable->fieldList['branch']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['project']['title']    = $lang->bug->project;
$config->productplan->viewBug->dtable->fieldList['project']['type']     = 'text';
$config->productplan->viewBug->dtable->fieldList['project']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['execution']['title']    = $lang->bug->execution;
$config->productplan->viewBug->dtable->fieldList['execution']['type']     = 'text';
$config->productplan->viewBug->dtable->fieldList['execution']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['openedBuild']['title']    = $lang->bug->openedBuild;
$config->productplan->viewBug->dtable->fieldList['openedBuild']['type']     = 'text';
$config->productplan->viewBug->dtable->fieldList['openedBuild']['control']  = 'multiple';
$config->productplan->viewBug->dtable->fieldList['openedBuild']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['openedBy']['title']    = $lang->bug->abbr->openedBy;
$config->productplan->viewBug->dtable->fieldList['openedBy']['type']     = 'user';
$config->productplan->viewBug->dtable->fieldList['openedBy']['show']     = true;
$config->productplan->viewBug->dtable->fieldList['openedBy']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['openedDate']['title']    = $lang->bug->abbr->openedDate;
$config->productplan->viewBug->dtable->fieldList['openedDate']['type']     = 'date';
$config->productplan->viewBug->dtable->fieldList['openedDate']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['confirmed']['title']    = $lang->bug->confirmed;
$config->productplan->viewBug->dtable->fieldList['confirmed']['type']     = 'category';
$config->productplan->viewBug->dtable->fieldList['confirmed']['map']      = $lang->bug->confirmedList;
$config->productplan->viewBug->dtable->fieldList['confirmed']['flex']     = false;
$config->productplan->viewBug->dtable->fieldList['confirmed']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['assignedTo']['title']       = $lang->bug->assignedTo;
$config->productplan->viewBug->dtable->fieldList['assignedTo']['sortType']    = true;
$config->productplan->viewBug->dtable->fieldList['assignedTo']['currentUser'] = $app->user->account;
$config->productplan->viewBug->dtable->fieldList['assignedTo']['assignLink']  = array('module' => 'bug', 'method' => 'assignTo', 'params' => 'bugID={id}');
$config->productplan->viewBug->dtable->fieldList['assignedTo']['type']        = 'assign';
$config->productplan->viewBug->dtable->fieldList['assignedTo']['show']        = true;

$config->productplan->viewBug->dtable->fieldList['assignedDate']['title']    = $lang->bug->assignedDate;
$config->productplan->viewBug->dtable->fieldList['assignedDate']['type']     = 'date';
$config->productplan->viewBug->dtable->fieldList['assignedDate']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['deadline']['title']    = $lang->bug->deadline;
$config->productplan->viewBug->dtable->fieldList['deadline']['type']     = 'date';
$config->productplan->viewBug->dtable->fieldList['deadline']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['resolvedBy']['title']    = $lang->bug->resolvedBy;
$config->productplan->viewBug->dtable->fieldList['resolvedBy']['type']     = 'user';
$config->productplan->viewBug->dtable->fieldList['resolvedBy']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['resolution']['title']    = $lang->bug->resolution;
$config->productplan->viewBug->dtable->fieldList['resolution']['type']     = 'category';
$config->productplan->viewBug->dtable->fieldList['resolution']['map']      = $lang->bug->resolutionList;
$config->productplan->viewBug->dtable->fieldList['resolution']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['toTask']['title']    = $lang->bug->toTask;
$config->productplan->viewBug->dtable->fieldList['toTask']['type']     = 'text';
$config->productplan->viewBug->dtable->fieldList['toTask']['link']     = array('module' => 'task', 'method' => 'view', 'params' => 'taskID={toTask}');
$config->productplan->viewBug->dtable->fieldList['toTask']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['resolvedDate']['title']    = $lang->bug->abbr->resolvedDate;
$config->productplan->viewBug->dtable->fieldList['resolvedDate']['type']     = 'date';
$config->productplan->viewBug->dtable->fieldList['resolvedDate']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['resolvedBuild']['title']    = $lang->bug->resolvedBuild;
$config->productplan->viewBug->dtable->fieldList['resolvedBuild']['type']     = 'text';
$config->productplan->viewBug->dtable->fieldList['resolvedBuild']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['os']['title']    = $lang->bug->os;
$config->productplan->viewBug->dtable->fieldList['os']['type']     = 'category';
$config->productplan->viewBug->dtable->fieldList['os']['map']      = $lang->bug->osList;
$config->productplan->viewBug->dtable->fieldList['os']['control']  = 'multiple';
$config->productplan->viewBug->dtable->fieldList['os']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['browser']['title']    = $lang->bug->browser;
$config->productplan->viewBug->dtable->fieldList['browser']['type']     = 'category';
$config->productplan->viewBug->dtable->fieldList['browser']['map']      = $lang->bug->browserList;
$config->productplan->viewBug->dtable->fieldList['browser']['control']  = 'multiple';
$config->productplan->viewBug->dtable->fieldList['browser']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['activatedCount']['title']    = $lang->bug->abbr->activatedCount;
$config->productplan->viewBug->dtable->fieldList['activatedCount']['type']     = 'count';
$config->productplan->viewBug->dtable->fieldList['activatedCount']['sortType'] = true;

if($config->edition != 'open')
{
    $app->loadLang('custom');
    $config->productplan->viewBug->dtable->fieldList['relatedObject']['name']        = 'relatedObject';
    $config->productplan->viewBug->dtable->fieldList['relatedObject']['title']       = $lang->custom->relateObject;
    $config->productplan->viewBug->dtable->fieldList['relatedObject']['sortType']    = false;
    $config->productplan->viewBug->dtable->fieldList['relatedObject']['width']       = '70';
    $config->productplan->viewBug->dtable->fieldList['relatedObject']['type']        = 'text';
    $config->productplan->viewBug->dtable->fieldList['relatedObject']['link']        = common::hasPriv('custom', 'showRelationGraph') ? "RAWJS<function(info){ if(info.row.data.relatedObject == 0) return 0; else return '" . helper::createLink('custom', 'showRelationGraph', 'objectID={id}&objectType=bug') . "'; }>RAWJS" : null;
    $config->productplan->viewBug->dtable->fieldList['relatedObject']['data-toggle'] = 'modal';
    $config->productplan->viewBug->dtable->fieldList['relatedObject']['data-size']   = 'lg';
    $config->productplan->viewBug->dtable->fieldList['relatedObject']['flex']        = false;
    $config->productplan->viewBug->dtable->fieldList['relatedObject']['align']       = 'center';
}

$config->productplan->viewBug->dtable->fieldList['activatedDate']['title']    = $lang->bug->activatedDate;
$config->productplan->viewBug->dtable->fieldList['activatedDate']['type']     = 'date';
$config->productplan->viewBug->dtable->fieldList['activatedDate']['sortType'] = 'date';

$config->productplan->viewBug->dtable->fieldList['story']['title']    = $lang->bug->story;
$config->productplan->viewBug->dtable->fieldList['story']['type']     = 'text';
$config->productplan->viewBug->dtable->fieldList['story']['link']     = array('module' => 'story', 'method' => 'view', 'params' => 'storyID={story}');
$config->productplan->viewBug->dtable->fieldList['story']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['task']['title']    = $lang->bug->task;
$config->productplan->viewBug->dtable->fieldList['task']['type']     = 'text';
$config->productplan->viewBug->dtable->fieldList['task']['link']     = array('module' => 'task', 'method' => 'view', 'params' => 'taskID={task}');
$config->productplan->viewBug->dtable->fieldList['task']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['mailto']['title']     = $lang->bug->mailto;
$config->productplan->viewBug->dtable->fieldList['mailto']['type']      = 'text';
$config->productplan->viewBug->dtable->fieldList['mailto']['sortType']  = true;
$config->productplan->viewBug->dtable->fieldList['mailto']['delimiter'] = ',';

if(in_array($config->edition, array('max', 'ipd')))
{
    $config->productplan->viewBug->dtable->fieldList['injection']['title']   = $lang->bug->injection;
    $config->productplan->viewBug->dtable->fieldList['injection']['control'] = 'picker';
    $config->productplan->viewBug->dtable->fieldList['injection']['type']    = 'text';
    $config->productplan->viewBug->dtable->fieldList['injection']['map']     = $lang->bug->injectionList;

    $config->productplan->viewBug->dtable->fieldList['identify']['title']   = $lang->bug->identify;
    $config->productplan->viewBug->dtable->fieldList['identify']['control'] = 'picker';
    $config->productplan->viewBug->dtable->fieldList['identify']['type']    = 'text';
    $config->productplan->viewBug->dtable->fieldList['identify']['map']     = $lang->bug->identifyList;
}

$config->productplan->viewBug->dtable->fieldList['keywords']['title']    = $lang->bug->keywords;
$config->productplan->viewBug->dtable->fieldList['keywords']['type']     = 'text';
$config->productplan->viewBug->dtable->fieldList['keywords']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['lastEditedBy']['title']    = $lang->bug->lastEditedBy;
$config->productplan->viewBug->dtable->fieldList['lastEditedBy']['type']     = 'user';
$config->productplan->viewBug->dtable->fieldList['lastEditedBy']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['lastEditedDate']['title']    = $lang->bug->abbr->lastEditedDate;
$config->productplan->viewBug->dtable->fieldList['lastEditedDate']['type']     = 'date';
$config->productplan->viewBug->dtable->fieldList['lastEditedDate']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['closedBy']['title']    = $lang->bug->closedBy;
$config->productplan->viewBug->dtable->fieldList['closedBy']['type']     = 'user';
$config->productplan->viewBug->dtable->fieldList['closedBy']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['closedDate']['title']    = $lang->bug->closedDate;
$config->productplan->viewBug->dtable->fieldList['closedDate']['type']     = 'date';
$config->productplan->viewBug->dtable->fieldList['closedDate']['sortType'] = true;

$config->productplan->viewBug->dtable->fieldList['steps']['title']   = 'steps';
$config->productplan->viewBug->dtable->fieldList['steps']['control'] = 'textarea';
$config->productplan->viewBug->dtable->fieldList['steps']['display'] = false;

$config->productplan->viewBug->dtable->fieldList['case']['title']   = 'case';
$config->productplan->viewBug->dtable->fieldList['case']['display'] = false;

$config->productplan->viewBug->dtable->fieldList['actions']['title']    = $lang->actions;
$config->productplan->viewBug->dtable->fieldList['actions']['width']    = 'auto';
$config->productplan->viewBug->dtable->fieldList['actions']['type']     = 'actions';
$config->productplan->viewBug->dtable->fieldList['actions']['fixed']    = 'right';
$config->productplan->viewBug->dtable->fieldList['actions']['sortType'] = false;
