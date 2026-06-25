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

$config->productplan->view->dtable->fieldList['actions']['title']    = $lang->actions;
$config->productplan->view->dtable->fieldList['actions']['width']    = 'auto';
$config->productplan->view->dtable->fieldList['actions']['type']     = 'actions';
