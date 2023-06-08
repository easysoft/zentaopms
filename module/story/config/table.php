<?php
global $lang, $app;
$config->story->datatable = new stdclass();

$config->story->datatable->defaultField = array('id', 'title', 'pri', 'plan', 'status', 'openedBy', 'estimate', 'reviewedBy', 'stage', 'assignedTo', 'taskCount', 'actions');

$config->story->datatable->fieldList['id']['title']    = 'idAB';
$config->story->datatable->fieldList['id']['fixed']    = 'left';
$config->story->datatable->fieldList['id']['width']    = '60';
$config->story->datatable->fieldList['id']['required'] = 'yes';
$config->story->datatable->fieldList['id']['type']     = 'id';
$config->story->datatable->fieldList['id']['group']    = 'group1';

if($app->tab == 'execution')
{
    $config->story->datatable->fieldList['order']['title']    = 'order';
    $config->story->datatable->fieldList['order']['fixed']    = 'left';
    $config->story->datatable->fieldList['order']['width']    = '45';
    $config->story->datatable->fieldList['order']['sort']     = 'no';
    $config->story->datatable->fieldList['order']['required'] = 'no';
    $config->story->datatable->fieldList['order']['name']     = $this->lang->story->order;
}

$config->story->datatable->fieldList['title']['title']    = 'title';
$config->story->datatable->fieldList['title']['fixed']    = 'left';
$config->story->datatable->fieldList['title']['width']    = 200;
$config->story->datatable->fieldList['title']['required'] = 'yes';
$config->story->datatable->fieldList['title']['group']    = 'group1';

$config->story->datatable->fieldList['pri']['title']    = 'priAB';
$config->story->datatable->fieldList['pri']['fixed']    = 'left';
$config->story->datatable->fieldList['pri']['width']    = '40';
$config->story->datatable->fieldList['pri']['required'] = 'no';
$config->story->datatable->fieldList['pri']['name']     = $this->lang->story->pri;
$config->story->datatable->fieldList['pri']['type']     = 'pri';
$config->story->datatable->fieldList['pri']['group']    = 'group2';

$config->story->datatable->fieldList['plan']['title']      = 'planAB';
$config->story->datatable->fieldList['plan']['fixed']      = 'no';
$config->story->datatable->fieldList['plan']['width']      = '64';
$config->story->datatable->fieldList['plan']['required']   = 'no';
$config->story->datatable->fieldList['plan']['control']    = 'select';
$config->story->datatable->fieldList['plan']['dataSource'] = array('module' => 'productplan', 'method' => 'getPairs', 'params' => '$productID');
$config->story->datatable->fieldList['plan']['group']      = 'group3';

$config->story->datatable->fieldList['status']['title']     = 'statusAB';
$config->story->datatable->fieldList['status']['fixed']     = 'no';
$config->story->datatable->fieldList['status']['width']     = '60';
$config->story->datatable->fieldList['status']['required']  = 'no';
$config->story->datatable->fieldList['status']['type']      = 'status';
$config->story->datatable->fieldList['status']['statusMap'] = $lang->story->statusList;
$config->story->datatable->fieldList['status']['group']     = 'group3';

$config->story->datatable->fieldList['openedBy']['title']    = 'openedByAB';
$config->story->datatable->fieldList['openedBy']['fixed']    = 'no';
$config->story->datatable->fieldList['openedBy']['width']    = '60';
$config->story->datatable->fieldList['openedBy']['required'] = 'no';
$config->story->datatable->fieldList['openedBy']['group']    = 'group4';

$config->story->datatable->fieldList['estimate']['title']    = 'estimateAB';
$config->story->datatable->fieldList['estimate']['fixed']    = 'no';
$config->story->datatable->fieldList['estimate']['width']    = '50';
$config->story->datatable->fieldList['estimate']['required'] = 'no';
$config->story->datatable->fieldList['estimate']['group']    = 'group4';

$config->story->datatable->fieldList['reviewer']['title']      = 'reviewer';
$config->story->datatable->fieldList['reviewer']['control']    = 'multiple';
$config->story->datatable->fieldList['reviewer']['dataSource'] = array('module' => 'story', 'method' => 'getStoriesReviewer', 'params' => '$productID');
$config->story->datatable->fieldList['reviewer']['type']       = 'user';
$config->story->datatable->fieldList['reviewer']['group']      = 'group4';

$config->story->datatable->fieldList['reviewedBy']['title']      = 'reviewedBy';
$config->story->datatable->fieldList['reviewedBy']['fixed']      = 'no';
$config->story->datatable->fieldList['reviewedBy']['width']      = '100';
$config->story->datatable->fieldList['reviewedBy']['required']   = 'no';
$config->story->datatable->fieldList['reviewedBy']['control']    = 'multiple';
$config->story->datatable->fieldList['reviewedBy']['dataSource'] = array('module' => 'story', 'method' => 'getStoriesReviewer', 'params' => '$productID');
$config->story->datatable->fieldList['reviewedBy']['type']       = 'user';
$config->story->datatable->fieldList['reviewedBy']['group']      = 'group4';

$config->story->datatable->fieldList['reviewedDate']['title']    = 'reviewedDate';
$config->story->datatable->fieldList['reviewedDate']['fixed']    = 'no';
$config->story->datatable->fieldList['reviewedDate']['width']    = '90';
$config->story->datatable->fieldList['reviewedDate']['required'] = 'no';

$config->story->datatable->fieldList['stage']['title']     = 'stageAB';
$config->story->datatable->fieldList['stage']['fixed']     = 'no';
$config->story->datatable->fieldList['stage']['width']     = '85';
$config->story->datatable->fieldList['stage']['required']  = 'no';
$config->story->datatable->fieldList['stage']['type']      = 'status';
$config->story->datatable->fieldList['stage']['statusMap'] = $lang->story->stageList;
$config->story->datatable->fieldList['stage']['group']     = 'group5';

$config->story->datatable->fieldList['assignedTo']['title']    = 'assignedTo';
$config->story->datatable->fieldList['assignedTo']['fixed']    = 'no';
$config->story->datatable->fieldList['assignedTo']['width']    = '90';
$config->story->datatable->fieldList['assignedTo']['required'] = 'no';
$config->story->datatable->fieldList['assignedTo']['type']     = 'assign';
$config->story->datatable->fieldList['assignedTo']['group']    = 'group5';

$config->story->datatable->fieldList['assignedDate']['title']    = 'assignedDate';
$config->story->datatable->fieldList['assignedDate']['fixed']    = 'no';
$config->story->datatable->fieldList['assignedDate']['width']    = '90';
$config->story->datatable->fieldList['assignedDate']['required'] = 'no';

$config->story->datatable->fieldList['product']['title']      = 'product';
$config->story->datatable->fieldList['product']['control']    = 'hidden';
$config->story->datatable->fieldList['product']['dataSource'] = array('module' => 'transfer', 'method' => 'getRelatedObjects', 'params' => 'story&product&id,name');

$config->story->datatable->fieldList['branch']['title']      = 'branch';
$config->story->datatable->fieldList['branch']['fixed']      = 'no';
$config->story->datatable->fieldList['branch']['width']      = '100';
$config->story->datatable->fieldList['branch']['required']   = 'no';
$config->story->datatable->fieldList['branch']['control']    = 'select';
$config->story->datatable->fieldList['branch']['dataSource'] = array('module' => 'branch', 'method' => 'getPairs', 'params' => '$productID&active');

$config->story->datatable->fieldList['module']['title']      = 'module';
$config->story->datatable->fieldList['module']['control']    = 'select';
$config->story->datatable->fieldList['module']['dataSource'] = array('module' => 'tree', 'method' => 'getOptionMenu', 'params' => '$productID&story&0&all');

$config->story->datatable->fieldList['keywords']['title']    = 'keywords';
$config->story->datatable->fieldList['keywords']['fixed']    = 'no';
$config->story->datatable->fieldList['keywords']['width']    = '100';
$config->story->datatable->fieldList['keywords']['required'] = 'no';

$config->story->datatable->fieldList['source']['title']    = 'source';
$config->story->datatable->fieldList['source']['fixed']    = 'no';
$config->story->datatable->fieldList['source']['width']    = '90';
$config->story->datatable->fieldList['source']['required'] = 'no';

$config->story->datatable->fieldList['sourceNote']['title']    = 'sourceNote';
$config->story->datatable->fieldList['sourceNote']['fixed']    = 'no';
$config->story->datatable->fieldList['sourceNote']['width']    = '90';
$config->story->datatable->fieldList['sourceNote']['required'] = 'no';

$config->story->datatable->fieldList['category']['title']    = 'category';
$config->story->datatable->fieldList['category']['fixed']    = 'no';
$config->story->datatable->fieldList['category']['width']    = '60';
$config->story->datatable->fieldList['category']['required'] = 'no';

$config->story->datatable->fieldList['openedDate']['title']    = 'openedDate';
$config->story->datatable->fieldList['openedDate']['fixed']    = 'no';
$config->story->datatable->fieldList['openedDate']['width']    = '90';
$config->story->datatable->fieldList['openedDate']['required'] = 'no';

$config->story->datatable->fieldList['needReview']['title']      = 'needReview';
$config->story->datatable->fieldList['needReview']['control']    = 'select';
$config->story->datatable->fieldList['needReview']['dataSource'] = array('lang' => 'reviewList');

$config->story->datatable->fieldList['closedBy']['title']    = 'closedBy';
$config->story->datatable->fieldList['closedBy']['fixed']    = 'no';
$config->story->datatable->fieldList['closedBy']['width']    = '80';
$config->story->datatable->fieldList['closedBy']['required'] = 'no';

$config->story->datatable->fieldList['closedDate']['title']    = 'closedDate';
$config->story->datatable->fieldList['closedDate']['fixed']    = 'no';
$config->story->datatable->fieldList['closedDate']['width']    = '90';
$config->story->datatable->fieldList['closedDate']['required'] = 'no';

$config->story->datatable->fieldList['closedReason']['title']    = 'closedReason';
$config->story->datatable->fieldList['closedReason']['fixed']    = 'no';
$config->story->datatable->fieldList['closedReason']['width']    = '90';
$config->story->datatable->fieldList['closedReason']['required'] = 'no';

$config->story->datatable->fieldList['lastEditedBy']['title']    = 'lastEditedBy';
$config->story->datatable->fieldList['lastEditedBy']['fixed']    = 'no';
$config->story->datatable->fieldList['lastEditedBy']['width']    = '80';
$config->story->datatable->fieldList['lastEditedBy']['required'] = 'no';

$config->story->datatable->fieldList['lastEditedDate']['title']    = 'lastEditedDate';
$config->story->datatable->fieldList['lastEditedDate']['fixed']    = 'no';
$config->story->datatable->fieldList['lastEditedDate']['width']    = '90';
$config->story->datatable->fieldList['lastEditedDate']['required'] = 'no';

$config->story->datatable->fieldList['activatedDate']['title']    = 'activatedDate';
$config->story->datatable->fieldList['activatedDate']['fixed']    = 'no';
$config->story->datatable->fieldList['activatedDate']['width']    = '90';
$config->story->datatable->fieldList['activatedDate']['required'] = 'no';

$config->story->datatable->fieldList['feedbackBy']['title']    = 'feedbackBy';
$config->story->datatable->fieldList['feedbackBy']['fixed']    = 'no';
$config->story->datatable->fieldList['feedbackBy']['width']    = '100';
$config->story->datatable->fieldList['feedbackBy']['required'] = 'no';

$config->story->datatable->fieldList['notifyEmail']['title']    = 'notifyEmail';
$config->story->datatable->fieldList['notifyEmail']['fixed']    = 'no';
$config->story->datatable->fieldList['notifyEmail']['width']    = '100';
$config->story->datatable->fieldList['notifyEmail']['required'] = 'no';

$config->story->datatable->fieldList['mailto']['title']    = 'mailto';
$config->story->datatable->fieldList['mailto']['fixed']    = 'no';
$config->story->datatable->fieldList['mailto']['width']    = '100';
$config->story->datatable->fieldList['mailto']['required'] = 'no';

$config->story->datatable->fieldList['version']['title']    = 'version';
$config->story->datatable->fieldList['version']['fixed']    = 'no';
$config->story->datatable->fieldList['version']['width']    = '60';
$config->story->datatable->fieldList['version']['required'] = 'no';

$config->story->datatable->fieldList['taskCount']['title']    = 'T';
$config->story->datatable->fieldList['taskCount']['fixed']    = 'no';
$config->story->datatable->fieldList['taskCount']['width']    = '30';
$config->story->datatable->fieldList['taskCount']['required'] = 'no';
$config->story->datatable->fieldList['taskCount']['sort']     = 'no';
$config->story->datatable->fieldList['taskCount']['name']     = $lang->story->taskCount;
$config->story->datatable->fieldList['taskCount']['group']    = 'group5';

$config->story->datatable->fieldList['bugCount']['title']    = 'B';
$config->story->datatable->fieldList['bugCount']['fixed']    = 'no';
$config->story->datatable->fieldList['bugCount']['width']    = '30';
$config->story->datatable->fieldList['bugCount']['required'] = 'no';
$config->story->datatable->fieldList['bugCount']['sort']     = 'no';
$config->story->datatable->fieldList['bugCount']['name']     = $lang->story->bugCount;

$config->story->datatable->fieldList['caseCount']['title']    = 'C';
$config->story->datatable->fieldList['caseCount']['fixed']    = 'no';
$config->story->datatable->fieldList['caseCount']['width']    = '30';
$config->story->datatable->fieldList['caseCount']['required'] = 'no';
$config->story->datatable->fieldList['caseCount']['sort']     = 'no';
$config->story->datatable->fieldList['caseCount']['name']     = $lang->story->caseCount;

$config->story->datatable->fieldList['actions']['title']    = 'actions';
$config->story->datatable->fieldList['actions']['fixed']    = 'right';
$config->story->datatable->fieldList['actions']['required'] = 'yes';
$config->story->datatable->fieldList['actions']['width']    = 'auto';
$config->story->datatable->fieldList['actions']['minWidth'] = $app->tab == 'project' ? 250 : 180;
$config->story->datatable->fieldList['actions']['type']     = 'actions';

$config->story->datatable->fieldList['actions']['actionsMap']['assigned']['icon'] = 'hand-right';
$config->story->datatable->fieldList['actions']['actionsMap']['assigned']['hint'] = $lang->story->operateList['assigned'];

$config->story->datatable->fieldList['actions']['actionsMap']['close']['icon'] = 'off';
$config->story->datatable->fieldList['actions']['actionsMap']['close']['hint'] = $lang->story->operateList['closed'];

$config->story->datatable->fieldList['actions']['actionsMap']['activate']['icon'] = 'active';
$config->story->datatable->fieldList['actions']['actionsMap']['activate']['hint'] = $lang->story->operateList['activated'];

$config->story->datatable->fieldList['actions']['actionsMap']['change']['icon'] = 'change';
$config->story->datatable->fieldList['actions']['actionsMap']['change']['hint'] = $lang->story->operateList['changed'];

$config->story->datatable->fieldList['actions']['actionsMap']['review']['icon'] = 'search';
$config->story->datatable->fieldList['actions']['actionsMap']['review']['hint'] = $lang->story->operateList['reviewed'];

$config->story->datatable->fieldList['actions']['actionsMap']['edit']['icon'] = 'edit';
$config->story->datatable->fieldList['actions']['actionsMap']['edit']['hint'] = $lang->story->operateList['edited'];

$config->story->datatable->fieldList['actions']['actionsMap']['submitreview']['icon'] = 'sub-review';
$config->story->datatable->fieldList['actions']['actionsMap']['submitreview']['hint'] = $lang->story->operateList['submitreview'];

$config->story->datatable->fieldList['actions']['actionsMap']['recalledchange']['icon'] = 'undo';
$config->story->datatable->fieldList['actions']['actionsMap']['recalledchange']['hint'] = $lang->story->operateList['recalledchange'];

$config->story->datatable->fieldList['actions']['actionsMap']['recall']['icon'] = 'undo';
$config->story->datatable->fieldList['actions']['actionsMap']['recall']['hint'] = $lang->story->operateList['recalled'];

$app->loadLang('testcase');
$config->story->datatable->fieldList['actions']['actionsMap']['testcase']['icon'] = 'testcase';
$config->story->datatable->fieldList['actions']['actionsMap']['testcase']['hint'] = $lang->testcase->create;

$config->story->datatable->fieldList['actions']['actionsMap']['subdivide']['icon'] = 'split';
$config->story->datatable->fieldList['actions']['actionsMap']['subdivide']['hint'] = $lang->story->subdivide;

$config->story->datatable->fieldList['actions']['actionsMap']['processStoryChange']['icon'] = 'ok';
$config->story->datatable->fieldList['actions']['actionsMap']['processStoryChange']['hint'] = $lang->confirm;

$config->story->datatable->fieldList['actions']['actionsMap']['batchCreate']['icon'] = 'split';
$config->story->datatable->fieldList['actions']['actionsMap']['batchCreate']['hint'] = $lang->story->subdivide;
