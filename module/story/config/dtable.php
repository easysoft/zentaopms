<?php
global $lang, $app;
$config->story->dtable = new stdclass();

$config->story->dtable->defaultField = array('id', 'title', 'pri', 'plan', 'status', 'openedBy', 'estimate', 'reviewedBy', 'stage', 'assignedTo', 'taskCount', 'actions');

$config->story->dtable->fieldList['id']['title']        = 'idAB';
$config->story->dtable->fieldList['id']['fixed']        = 'left';
$config->story->dtable->fieldList['id']['width']        = '80';
$config->story->dtable->fieldList['id']['required']     = 'yes';
$config->story->dtable->fieldList['id']['type']         = 'id';
$config->story->dtable->fieldList['id']['group']        = 1;
$config->story->dtable->fieldList['id']['sortType']     = true;
$config->story->dtable->fieldList['id']['nestedToggle'] = true;
$config->story->dtable->fieldList['id']['checkbox']     = true;

if($app->tab == 'execution')
{
    $config->story->dtable->fieldList['order']['title']    = 'order';
    $config->story->dtable->fieldList['order']['fixed']    = 'left';
    $config->story->dtable->fieldList['order']['width']    = '45';
    $config->story->dtable->fieldList['order']['sort']     = 'no';
    $config->story->dtable->fieldList['order']['required'] = 'no';
    $config->story->dtable->fieldList['order']['name']     = $this->lang->story->order;
}

$config->story->dtable->fieldList['title']['title']        = 'title';
$config->story->dtable->fieldList['title']['type']         = 'link';
$config->story->dtable->fieldList['title']['link']         = 'RAWJS<function(info){const {row, col} = info; return {url:`%s`,target:\'_blank\'};}>RAWJS';
$config->story->dtable->fieldList['title']['fixed']        = 'left';
$config->story->dtable->fieldList['title']['width']        = '342';
$config->story->dtable->fieldList['title']['required']     = 'yes';
$config->story->dtable->fieldList['title']['group']        = 1;
$config->story->dtable->fieldList['title']['sortType']     = true;
$config->story->dtable->fieldList['title']['nestedToggle'] = true;

$config->story->dtable->fieldList['pri']['title']    = 'priAB';
$config->story->dtable->fieldList['pri']['fixed']    = 'left';
$config->story->dtable->fieldList['pri']['width']    = '52';
$config->story->dtable->fieldList['pri']['required'] = 'no';
$config->story->dtable->fieldList['pri']['name']     = $this->lang->story->pri;
$config->story->dtable->fieldList['pri']['type']     = 'pri';
$config->story->dtable->fieldList['pri']['group']    = 2;

$config->story->dtable->fieldList['plan']['title']      = 'planAB';
$config->story->dtable->fieldList['plan']['fixed']      = 'no';
$config->story->dtable->fieldList['plan']['width']      = '136';
$config->story->dtable->fieldList['plan']['required']   = 'no';
$config->story->dtable->fieldList['plan']['control']    = 'select';
$config->story->dtable->fieldList['plan']['dataSource'] = array('module' => 'productplan', 'method' => 'getPairs', 'params' => '$productID');
$config->story->dtable->fieldList['plan']['group']      = 3;

$config->story->dtable->fieldList['category']['title']    = 'category';
$config->story->dtable->fieldList['category']['fixed']    = 'no';
$config->story->dtable->fieldList['category']['width']    = '60';
$config->story->dtable->fieldList['category']['required'] = 'no';
$config->story->dtable->fieldList['category']['group']    = 3;

$config->story->dtable->fieldList['status']['title']     = 'statusAB';
$config->story->dtable->fieldList['status']['fixed']     = 'no';
$config->story->dtable->fieldList['status']['width']     = '80';
$config->story->dtable->fieldList['status']['required']  = 'no';
$config->story->dtable->fieldList['status']['type']      = 'status';
$config->story->dtable->fieldList['status']['statusMap'] = $lang->story->statusList;
$config->story->dtable->fieldList['status']['group']     = 3;

$config->story->dtable->fieldList['branch']['title']      = 'branch';
$config->story->dtable->fieldList['branch']['fixed']      = 'no';
$config->story->dtable->fieldList['branch']['width']      = '100';
$config->story->dtable->fieldList['branch']['required']   = 'no';
$config->story->dtable->fieldList['branch']['control']    = 'select';
$config->story->dtable->fieldList['branch']['dataSource'] = array('module' => 'branch', 'method' => 'getPairs', 'params' => '$productID&active');
$config->story->dtable->fieldList['branch']['group']      = 3;

$config->story->dtable->fieldList['openedBy']['title']    = 'openedByAB';
$config->story->dtable->fieldList['openedBy']['type']     = 'user';
$config->story->dtable->fieldList['openedBy']['fixed']    = 'no';
$config->story->dtable->fieldList['openedBy']['width']    = '80';
$config->story->dtable->fieldList['openedBy']['required'] = 'no';
$config->story->dtable->fieldList['openedBy']['group']    = 4;

$config->story->dtable->fieldList['openedDate']['title']    = 'openedDate';
$config->story->dtable->fieldList['openedDate']['fixed']    = 'no';
$config->story->dtable->fieldList['openedDate']['width']    = '90';
$config->story->dtable->fieldList['openedDate']['required'] = 'no';
$config->story->dtable->fieldList['openedDate']['group']    = 4;

$config->story->dtable->fieldList['estimate']['title']    = 'estimateAB';
$config->story->dtable->fieldList['estimate']['fixed']    = 'no';
$config->story->dtable->fieldList['estimate']['width']    = '59';
$config->story->dtable->fieldList['estimate']['required'] = 'no';
$config->story->dtable->fieldList['estimate']['group']    = 4;

$config->story->dtable->fieldList['reviewer']['title']      = 'reviewer';
$config->story->dtable->fieldList['reviewer']['control']    = 'multiple';
$config->story->dtable->fieldList['reviewer']['dataSource'] = array('module' => 'story', 'method' => 'getStoriesReviewer', 'params' => '$productID');
$config->story->dtable->fieldList['reviewer']['type']       = 'user';
$config->story->dtable->fieldList['reviewer']['group']      = 4;

$config->story->dtable->fieldList['reviewedBy']['title']      = 'reviewedBy';
$config->story->dtable->fieldList['reviewedBy']['fixed']      = 'no';
$config->story->dtable->fieldList['reviewedBy']['width']      = '100';
$config->story->dtable->fieldList['reviewedBy']['required']   = 'no';
$config->story->dtable->fieldList['reviewedBy']['control']    = 'multiple';
$config->story->dtable->fieldList['reviewedBy']['dataSource'] = array('module' => 'story', 'method' => 'getStoriesReviewer', 'params' => '$productID');
$config->story->dtable->fieldList['reviewedBy']['type']       = 'user';
$config->story->dtable->fieldList['reviewedBy']['group']      = 4;

$config->story->dtable->fieldList['reviewedDate']['title']    = 'reviewedDate';
$config->story->dtable->fieldList['reviewedDate']['fixed']    = 'no';
$config->story->dtable->fieldList['reviewedDate']['width']    = '90';
$config->story->dtable->fieldList['reviewedDate']['required'] = 'no';
$config->story->dtable->fieldList['reviewedDate']['group']    = 4;

$config->story->dtable->fieldList['stage']['title']     = 'stageAB';
$config->story->dtable->fieldList['stage']['fixed']     = 'no';
$config->story->dtable->fieldList['stage']['width']     = '85';
$config->story->dtable->fieldList['stage']['required']  = 'no';
$config->story->dtable->fieldList['stage']['type']      = 'status';
$config->story->dtable->fieldList['stage']['statusMap'] = $lang->story->stageList;
$config->story->dtable->fieldList['stage']['group']     = 5;

$config->story->dtable->fieldList['assignedTo']['title']    = 'assignedTo';
$config->story->dtable->fieldList['assignedTo']['fixed']    = 'no';
$config->story->dtable->fieldList['assignedTo']['width']    = '90';
$config->story->dtable->fieldList['assignedTo']['required'] = 'no';
$config->story->dtable->fieldList['assignedTo']['type']     = 'assign';
$config->story->dtable->fieldList['assignedTo']['group']    = 5;

$config->story->dtable->fieldList['assignedDate']['title']    = 'assignedDate';
$config->story->dtable->fieldList['assignedDate']['fixed']    = 'no';
$config->story->dtable->fieldList['assignedDate']['width']    = '90';
$config->story->dtable->fieldList['assignedDate']['required'] = 'no';
$config->story->dtable->fieldList['assignedDate']['group']    = 5;

$config->story->dtable->fieldList['product']['title']      = 'product';
$config->story->dtable->fieldList['product']['control']    = 'hidden';
$config->story->dtable->fieldList['product']['dataSource'] = array('module' => 'transfer', 'method' => 'getRelatedObjects', 'params' => 'story&product&id,name');

$config->story->dtable->fieldList['module']['title']      = 'module';
$config->story->dtable->fieldList['module']['control']    = 'select';
$config->story->dtable->fieldList['module']['dataSource'] = array('module' => 'tree', 'method' => 'getOptionMenu', 'params' => '$productID&story&0&all');

$config->story->dtable->fieldList['needReview']['title']      = 'needReview';
$config->story->dtable->fieldList['needReview']['control']    = 'select';
$config->story->dtable->fieldList['needReview']['dataSource'] = array('lang' => 'reviewList');

$config->story->dtable->fieldList['taskCount']['title']    = 'T';
$config->story->dtable->fieldList['taskCount']['fixed']    = 'no';
$config->story->dtable->fieldList['taskCount']['width']    = '30';
$config->story->dtable->fieldList['taskCount']['required'] = 'no';
$config->story->dtable->fieldList['taskCount']['sort']     = 'no';
$config->story->dtable->fieldList['taskCount']['name']     = $lang->story->taskCount;
$config->story->dtable->fieldList['taskCount']['group']    = 6;

$config->story->dtable->fieldList['bugCount']['title']    = 'B';
$config->story->dtable->fieldList['bugCount']['fixed']    = 'no';
$config->story->dtable->fieldList['bugCount']['width']    = '30';
$config->story->dtable->fieldList['bugCount']['required'] = 'no';
$config->story->dtable->fieldList['bugCount']['sort']     = 'no';
$config->story->dtable->fieldList['bugCount']['name']     = $lang->story->bugCount;
$config->story->dtable->fieldList['bugCount']['group']    = 6;

$config->story->dtable->fieldList['caseCount']['title']    = 'C';
$config->story->dtable->fieldList['caseCount']['fixed']    = 'no';
$config->story->dtable->fieldList['caseCount']['width']    = '30';
$config->story->dtable->fieldList['caseCount']['required'] = 'no';
$config->story->dtable->fieldList['caseCount']['sort']     = 'no';
$config->story->dtable->fieldList['caseCount']['name']     = $lang->story->caseCount;
$config->story->dtable->fieldList['caseCount']['group']    = 6;

$config->story->dtable->fieldList['URS']['title']    = 'UR';
$config->story->dtable->fieldList['URS']['fixed']    = 'no';
$config->story->dtable->fieldList['URS']['width']    = '30';
$config->story->dtable->fieldList['URS']['required'] = 'no';
$config->story->dtable->fieldList['URS']['sort']     = 'no';
$config->story->dtable->fieldList['URS']['name']     = $lang->URCommon;
$config->story->dtable->fieldList['URS']['group']    = 6;


$config->story->dtable->fieldList['closedBy']['title']    = 'closedBy';
$config->story->dtable->fieldList['closedBy']['fixed']    = 'no';
$config->story->dtable->fieldList['closedBy']['width']    = '80';
$config->story->dtable->fieldList['closedBy']['required'] = 'no';
$config->story->dtable->fieldList['closedBy']['group']    = 7;

$config->story->dtable->fieldList['closedDate']['title']    = 'closedDate';
$config->story->dtable->fieldList['closedDate']['fixed']    = 'no';
$config->story->dtable->fieldList['closedDate']['width']    = '90';
$config->story->dtable->fieldList['closedDate']['required'] = 'no';
$config->story->dtable->fieldList['closedDate']['group']    = 7;

$config->story->dtable->fieldList['closedReason']['title']    = 'closedReason';
$config->story->dtable->fieldList['closedReason']['fixed']    = 'no';
$config->story->dtable->fieldList['closedReason']['width']    = '90';
$config->story->dtable->fieldList['closedReason']['required'] = 'no';
$config->story->dtable->fieldList['closedReason']['group']    = 7;

$config->story->dtable->fieldList['lastEditedBy']['title']    = 'lastEditedBy';
$config->story->dtable->fieldList['lastEditedBy']['fixed']    = 'no';
$config->story->dtable->fieldList['lastEditedBy']['width']    = '80';
$config->story->dtable->fieldList['lastEditedBy']['required'] = 'no';
$config->story->dtable->fieldList['lastEditedBy']['group']    = 8;

$config->story->dtable->fieldList['lastEditedDate']['title']    = 'lastEditedDate';
$config->story->dtable->fieldList['lastEditedDate']['fixed']    = 'no';
$config->story->dtable->fieldList['lastEditedDate']['width']    = '90';
$config->story->dtable->fieldList['lastEditedDate']['required'] = 'no';
$config->story->dtable->fieldList['lastEditedDate']['group']    = 8;

$config->story->dtable->fieldList['keywords']['title']    = 'keywords';
$config->story->dtable->fieldList['keywords']['fixed']    = 'no';
$config->story->dtable->fieldList['keywords']['width']    = '100';
$config->story->dtable->fieldList['keywords']['required'] = 'no';
$config->story->dtable->fieldList['keywords']['group']    = 9;

$config->story->dtable->fieldList['source']['title']    = 'source';
$config->story->dtable->fieldList['source']['fixed']    = 'no';
$config->story->dtable->fieldList['source']['width']    = '90';
$config->story->dtable->fieldList['source']['required'] = 'no';
$config->story->dtable->fieldList['source']['group']    = 9;

$config->story->dtable->fieldList['sourceNote']['title']    = 'sourceNote';
$config->story->dtable->fieldList['sourceNote']['fixed']    = 'no';
$config->story->dtable->fieldList['sourceNote']['width']    = '90';
$config->story->dtable->fieldList['sourceNote']['required'] = 'no';
$config->story->dtable->fieldList['sourceNote']['group']    = 9;

$config->story->dtable->fieldList['feedbackBy']['title']    = 'feedbackBy';
$config->story->dtable->fieldList['feedbackBy']['fixed']    = 'no';
$config->story->dtable->fieldList['feedbackBy']['width']    = '100';
$config->story->dtable->fieldList['feedbackBy']['required'] = 'no';
$config->story->dtable->fieldList['feedbackBy']['group']    = 9;

$config->story->dtable->fieldList['activatedDate']['title']    = 'activatedDate';
$config->story->dtable->fieldList['activatedDate']['fixed']    = 'no';
$config->story->dtable->fieldList['activatedDate']['width']    = '90';
$config->story->dtable->fieldList['activatedDate']['required'] = 'no';
$config->story->dtable->fieldList['activatedDate']['group']    = 10;

$config->story->dtable->fieldList['notifyEmail']['title']    = 'notifyEmail';
$config->story->dtable->fieldList['notifyEmail']['fixed']    = 'no';
$config->story->dtable->fieldList['notifyEmail']['width']    = '100';
$config->story->dtable->fieldList['notifyEmail']['required'] = 'no';
$config->story->dtable->fieldList['notifyEmail']['group']    = 10;

$config->story->dtable->fieldList['mailto']['title']    = 'mailto';
$config->story->dtable->fieldList['mailto']['fixed']    = 'no';
$config->story->dtable->fieldList['mailto']['width']    = '100';
$config->story->dtable->fieldList['mailto']['required'] = 'no';
$config->story->dtable->fieldList['mailto']['group']    = 10;

$config->story->dtable->fieldList['version']['title']    = 'version';
$config->story->dtable->fieldList['version']['fixed']    = 'no';
$config->story->dtable->fieldList['version']['width']    = '60';
$config->story->dtable->fieldList['version']['required'] = 'no';
$config->story->dtable->fieldList['version']['group']    = 10;

$config->story->dtable->fieldList['actions']['title']    = 'actions';
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
