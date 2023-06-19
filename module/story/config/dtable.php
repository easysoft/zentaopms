<?php
global $lang, $app;
$config->story->dtable = new stdclass();

$config->story->dtable->defaultField = array('id', 'title', 'pri', 'plan', 'status', 'openedBy', 'estimate', 'reviewedBy', 'stage', 'assignedTo', 'taskCount', 'actions');

$config->story->dtable->fieldList['id']['name']     = 'id';
$config->story->dtable->fieldList['id']['title']    = $lang->idAB;
$config->story->dtable->fieldList['id']['fixed']    = 'left';
$config->story->dtable->fieldList['id']['required'] = 'yes';
$config->story->dtable->fieldList['id']['type']     = 'checkID';
$config->story->dtable->fieldList['id']['checkbox'] = true;
$config->story->dtable->fieldList['id']['show']     = true;
$config->story->dtable->fieldList['id']['group']    = 1;

if($app->tab == 'execution')
{
    $config->story->dtable->fieldList['order']['name']     = 'order';
    $config->story->dtable->fieldList['order']['title']    = $lang->story->order;
    $config->story->dtable->fieldList['order']['fixed']    = 'left';
    $config->story->dtable->fieldList['order']['width']    = '45';
}

$config->story->dtable->fieldList['title']['name']         = 'title';
$config->story->dtable->fieldList['title']['title']        = $lang->story->title;
$config->story->dtable->fieldList['title']['type']         = 'text';
$config->story->dtable->fieldList['title']['link']         = helper::createLink('story', 'view', 'storyID={id}');
$config->story->dtable->fieldList['title']['fixed']        = 'left';
$config->story->dtable->fieldList['title']['minWidth']     = '342';
$config->story->dtable->fieldList['title']['required']     = 'yes';
$config->story->dtable->fieldList['title']['nestedToggle'] = true;
$config->story->dtable->fieldList['title']['show']         = true;
$config->story->dtable->fieldList['title']['group']        = 1;

$config->story->dtable->fieldList['pri']['name']     = 'pri';
$config->story->dtable->fieldList['pri']['title']    = $lang->priAB;
$config->story->dtable->fieldList['pri']['fixed']    = 'left';
$config->story->dtable->fieldList['pri']['type']     = 'pri';
$config->story->dtable->fieldList['pri']['show']     = true;
$config->story->dtable->fieldList['pri']['group']    = 2;

$config->story->dtable->fieldList['branch']['name']  = 'branch';
$config->story->dtable->fieldList['branch']['title'] = $lang->story->branch;
$config->story->dtable->fieldList['branch']['width'] = '100';
$config->story->dtable->fieldList['branch']['group'] = 3;

$config->story->dtable->fieldList['plan']['name']  = 'plan';
$config->story->dtable->fieldList['plan']['title'] = $lang->story->planAB;
$config->story->dtable->fieldList['plan']['width'] = '136';
$config->story->dtable->fieldList['plan']['show']  = true;
$config->story->dtable->fieldList['plan']['group'] = 4;

$config->story->dtable->fieldList['category']['name']  = 'category';
$config->story->dtable->fieldList['category']['title'] = $lang->story->category;
$config->story->dtable->fieldList['category']['type']  = 'category';
$config->story->dtable->fieldList['category']['group'] = 4;

$config->story->dtable->fieldList['status']['name']      = 'status';
$config->story->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->story->dtable->fieldList['status']['type']      = 'status';
$config->story->dtable->fieldList['status']['statusMap'] = $lang->story->statusList;
$config->story->dtable->fieldList['status']['show']      = true;
$config->story->dtable->fieldList['status']['group']     = 4;

$config->story->dtable->fieldList['openedBy']['name']  = 'openedBy';
$config->story->dtable->fieldList['openedBy']['title'] = $lang->story->openedByAB;
$config->story->dtable->fieldList['openedBy']['type']  = 'user';
$config->story->dtable->fieldList['openedBy']['show']  = true;
$config->story->dtable->fieldList['openedBy']['group'] = 5;

$config->story->dtable->fieldList['openedDate']['name']  = 'openedDate';
$config->story->dtable->fieldList['openedDate']['title'] = $lang->story->openedDate;
$config->story->dtable->fieldList['openedDate']['type']  = 'date';
$config->story->dtable->fieldList['openedDate']['group'] = 5;

$config->story->dtable->fieldList['estimate']['name']  = 'estimate';
$config->story->dtable->fieldList['estimate']['title'] = $lang->story->estimateAB;
$config->story->dtable->fieldList['estimate']['type']  = 'number';
$config->story->dtable->fieldList['estimate']['show']  = true;
$config->story->dtable->fieldList['estimate']['group'] = 5;

$config->story->dtable->fieldList['reviewer']['name']  = 'reviewer';
$config->story->dtable->fieldList['reviewer']['title'] = $lang->story->reviewer;
$config->story->dtable->fieldList['reviewer']['type']  = 'user';
$config->story->dtable->fieldList['reviewer']['show']  = true;
$config->story->dtable->fieldList['reviewer']['group'] = 5;

$config->story->dtable->fieldList['reviewedDate']['name']  = 'reviewedDate';
$config->story->dtable->fieldList['reviewedDate']['title'] = $lang->story->reviewedDate;
$config->story->dtable->fieldList['reviewedDate']['type']  = 'date';
$config->story->dtable->fieldList['reviewedDate']['group'] = 5;

$config->story->dtable->fieldList['stage']['name']      = 'stage';
$config->story->dtable->fieldList['stage']['title']     = $lang->story->stageAB;
$config->story->dtable->fieldList['stage']['type']      = 'status';
$config->story->dtable->fieldList['stage']['statusMap'] = $lang->story->stageList;
$config->story->dtable->fieldList['stage']['show']      = true;
$config->story->dtable->fieldList['stage']['group']     = 6;

$config->story->dtable->fieldList['assignedTo']['name']        = 'assignedTo';
$config->story->dtable->fieldList['assignedTo']['title']       = $lang->story->assignedTo;
$config->story->dtable->fieldList['assignedTo']['currentUser'] = $app->user->account;
$config->story->dtable->fieldList['assignedTo']['assignLink']  = array('module' => 'story', 'method' => 'assignTo', 'params' => 'storyID={id}');
$config->story->dtable->fieldList['assignedTo']['type']        = 'assign';
$config->story->dtable->fieldList['assignedTo']['show']        = true;
$config->story->dtable->fieldList['assignedTo']['group']       = 6;

$config->story->dtable->fieldList['assignedDate']['name']  = 'assignedDate';
$config->story->dtable->fieldList['assignedDate']['title'] = $lang->story->assignedDate;
$config->story->dtable->fieldList['assignedDate']['type']  = 'date';
$config->story->dtable->fieldList['assignedDate']['group'] = 6;

$config->story->dtable->fieldList['product']['name']       = 'product';
$config->story->dtable->fieldList['product']['title']      = $lang->story->product;
$config->story->dtable->fieldList['product']['type']       = 'text';

$config->story->dtable->fieldList['module']['name']       = 'module';
$config->story->dtable->fieldList['module']['title']      = $lang->story->module;
$config->story->dtable->fieldList['module']['type']       = 'text';

$config->story->dtable->fieldList['taskCount']['name']  = 'taskCount';
$config->story->dtable->fieldList['taskCount']['title'] = 'T';
$config->story->dtable->fieldList['taskCount']['width'] = '30';
$config->story->dtable->fieldList['taskCount']['show']  = true;
$config->story->dtable->fieldList['taskCount']['group'] = 7;

$config->story->dtable->fieldList['bugCount']['name']  = 'bugCount';
$config->story->dtable->fieldList['bugCount']['title'] = 'B';
$config->story->dtable->fieldList['bugCount']['width'] = '30';
$config->story->dtable->fieldList['bugCount']['group'] = 7;

$config->story->dtable->fieldList['caseCount']['name']  = 'caseCount';
$config->story->dtable->fieldList['caseCount']['title'] = 'C';
$config->story->dtable->fieldList['caseCount']['width'] = '30';
$config->story->dtable->fieldList['caseCount']['group'] = 7;

$config->story->dtable->fieldList['URS']['name']  = 'URS';
$config->story->dtable->fieldList['URS']['title'] = 'UR';
$config->story->dtable->fieldList['URS']['width'] = '30';
$config->story->dtable->fieldList['URS']['group'] = 6;

$config->story->dtable->fieldList['closedBy']['name']  = 'closedBy';
$config->story->dtable->fieldList['closedBy']['title'] = $lang->story->closedBy;
$config->story->dtable->fieldList['closedBy']['type']  = 'user';
$config->story->dtable->fieldList['closedBy']['group'] = 8;

$config->story->dtable->fieldList['closedReason']['name']  = 'closedReason';
$config->story->dtable->fieldList['closedReason']['title'] = $lang->story->closedReason;
$config->story->dtable->fieldList['closedReason']['width'] = '90';
$config->story->dtable->fieldList['closedReason']['group'] = 8;

$config->story->dtable->fieldList['closedDate']['name']  = 'closedDate';
$config->story->dtable->fieldList['closedDate']['title'] = $lang->story->closedDate;
$config->story->dtable->fieldList['closedDate']['type']  = 'date';
$config->story->dtable->fieldList['closedDate']['group'] = 8;

$config->story->dtable->fieldList['activatedDate']['name']  = 'activatedDate';
$config->story->dtable->fieldList['activatedDate']['title'] = $lang->story->activatedDate;
$config->story->dtable->fieldList['activatedDate']['type']  = 'date';
$config->story->dtable->fieldList['activatedDate']['group'] = 8;

$config->story->dtable->fieldList['lastEditedBy']['name']  = 'lastEditedBy';
$config->story->dtable->fieldList['lastEditedBy']['title'] = $lang->story->lastEditedBy;
$config->story->dtable->fieldList['lastEditedBy']['type']  = 'user';
$config->story->dtable->fieldList['lastEditedBy']['group'] = 9;

$config->story->dtable->fieldList['lastEditedDate']['name']  = 'lastEditedDate';
$config->story->dtable->fieldList['lastEditedDate']['title'] = $lang->story->lastEditedDate;
$config->story->dtable->fieldList['lastEditedDate']['type']  = 'date';
$config->story->dtable->fieldList['lastEditedDate']['group'] = 9;

$config->story->dtable->fieldList['keywords']['name']  = 'keywords';
$config->story->dtable->fieldList['keywords']['title'] = $lang->story->keywords;
$config->story->dtable->fieldList['keywords']['width'] = '100';
$config->story->dtable->fieldList['keywords']['group'] = 10;

$config->story->dtable->fieldList['source']['name']  = 'source';
$config->story->dtable->fieldList['source']['title'] = $lang->story->source;
$config->story->dtable->fieldList['source']['width'] = '90';
$config->story->dtable->fieldList['source']['group'] = 10;

$config->story->dtable->fieldList['sourceNote']['name']  = 'sourceNote';
$config->story->dtable->fieldList['sourceNote']['title'] = $lang->story->sourceNote;
$config->story->dtable->fieldList['sourceNote']['width'] = '90';
$config->story->dtable->fieldList['sourceNote']['group'] = 10;

$config->story->dtable->fieldList['feedbackBy']['name']  = 'feedbackBy';
$config->story->dtable->fieldList['feedbackBy']['title'] = $lang->story->feedbackBy;
$config->story->dtable->fieldList['feedbackBy']['type']  = 'user';
$config->story->dtable->fieldList['feedbackBy']['group'] = 10;

$config->story->dtable->fieldList['notifyEmail']['name']  = 'notifyEmail';
$config->story->dtable->fieldList['notifyEmail']['title'] = $lang->story->notifyEmail;
$config->story->dtable->fieldList['notifyEmail']['width'] = '100';
$config->story->dtable->fieldList['notifyEmail']['group'] = 11;

$config->story->dtable->fieldList['mailto']['name']  = 'mailto';
$config->story->dtable->fieldList['mailto']['title'] = $lang->story->mailto;
$config->story->dtable->fieldList['mailto']['width'] = '100';
$config->story->dtable->fieldList['mailto']['group'] = 11;

$config->story->dtable->fieldList['version']['name']  = 'version';
$config->story->dtable->fieldList['version']['title'] = $lang->story->version;
$config->story->dtable->fieldList['version']['type']  = 'number';
$config->story->dtable->fieldList['version']['group'] = 11;

$config->story->dtable->fieldList['actions']['name']     = 'actions';
$config->story->dtable->fieldList['actions']['title']    = $lang->actions;
$config->story->dtable->fieldList['actions']['fixed']    = 'right';
$config->story->dtable->fieldList['actions']['required'] = 'yes';
$config->story->dtable->fieldList['actions']['width']    = 'auto';
$config->story->dtable->fieldList['actions']['minWidth'] = $app->tab == 'project' ? 250 : 200;
$config->story->dtable->fieldList['actions']['type']     = 'actions';

$config->story->dtable->fieldList['actions']['actionsMap']['assigned']['icon'] = 'hand-right';
$config->story->dtable->fieldList['actions']['actionsMap']['assigned']['hint'] = $lang->story->operateList['assigned'];

$config->story->dtable->fieldList['actions']['actionsMap']['close']['icon'] = 'off';
$config->story->dtable->fieldList['actions']['actionsMap']['close']['hint'] = $lang->story->operateList['closed'];

$config->story->dtable->fieldList['actions']['actionsMap']['activate']['icon'] = 'active';
$config->story->dtable->fieldList['actions']['actionsMap']['activate']['hint'] = $lang->story->operateList['activated'];

$config->story->dtable->fieldList['actions']['actionsMap']['change']['icon'] = 'change';
$config->story->dtable->fieldList['actions']['actionsMap']['change']['hint'] = $lang->story->operateList['changed'];

$config->story->dtable->fieldList['actions']['actionsMap']['review']['icon'] = 'search';
$config->story->dtable->fieldList['actions']['actionsMap']['review']['hint'] = $lang->story->operateList['reviewed'];

$config->story->dtable->fieldList['actions']['actionsMap']['edit']['icon'] = 'edit';
$config->story->dtable->fieldList['actions']['actionsMap']['edit']['hint'] = $lang->story->operateList['edited'];

$config->story->dtable->fieldList['actions']['actionsMap']['submitreview']['icon'] = 'sub-review';
$config->story->dtable->fieldList['actions']['actionsMap']['submitreview']['hint'] = $lang->story->operateList['submitreview'];

$config->story->dtable->fieldList['actions']['actionsMap']['recalledchange']['icon'] = 'undo';
$config->story->dtable->fieldList['actions']['actionsMap']['recalledchange']['hint'] = $lang->story->operateList['recalledchange'];

$config->story->dtable->fieldList['actions']['actionsMap']['recall']['icon'] = 'undo';
$config->story->dtable->fieldList['actions']['actionsMap']['recall']['hint'] = $lang->story->operateList['recalled'];

$app->loadLang('testcase');
$config->story->dtable->fieldList['actions']['actionsMap']['testcase']['icon'] = 'testcase';
$config->story->dtable->fieldList['actions']['actionsMap']['testcase']['hint'] = $lang->testcase->create;

$config->story->dtable->fieldList['actions']['actionsMap']['subdivide']['icon'] = 'split';
$config->story->dtable->fieldList['actions']['actionsMap']['subdivide']['hint'] = $lang->story->subdivide;

$config->story->dtable->fieldList['actions']['actionsMap']['processStoryChange']['icon'] = 'ok';
$config->story->dtable->fieldList['actions']['actionsMap']['processStoryChange']['hint'] = $lang->confirm;

$config->story->dtable->fieldList['actions']['actionsMap']['batchCreate']['icon'] = 'split';
$config->story->dtable->fieldList['actions']['actionsMap']['batchCreate']['hint'] = $lang->story->subdivide;

$app->loadLang('task');
$config->story->dtable->fieldList['actions']['actionsMap']['createTask']['icon'] = 'plus';
$config->story->dtable->fieldList['actions']['actionsMap']['createTask']['hint'] = $lang->task->create;

$config->story->dtable->fieldList['actions']['actionsMap']['batchCreateTask']['icon'] = 'pluses';
$config->story->dtable->fieldList['actions']['actionsMap']['batchCreateTask']['hint'] = $lang->task->batchCreate;

$config->story->dtable->fieldList['actions']['actionsMap']['storyEstimate']['icon'] = 'estimate';
$config->story->dtable->fieldList['actions']['actionsMap']['storyEstimate']['hint'] = $lang->task->batchCreate;

$app->loadLang('execution');
$config->story->dtable->fieldList['actions']['actionsMap']['unlink']['icon'] = 'unlink';
$config->story->dtable->fieldList['actions']['actionsMap']['unlink']['hint'] = $lang->execution->unlinkStory;
