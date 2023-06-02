<?php
$config->story = new stdclass();

$config->story->batchCreate      = 10;
$config->story->affectedFixedNum = 7;
$config->story->needReview       = 1;
$config->story->removeFields     = 'objectTypeList,productList,executionList,execution';
$config->story->feedbackSource   = array('customer', 'user', 'market', 'service', 'operation', 'support', 'forum');

$config->story->batchClose = new stdclass();
$config->story->batchClose->columns = 10;
$config->story->create = new stdclass();
$config->story->edit   = new stdclass();
$config->story->change = new stdclass();
$config->story->close  = new stdclass();
$config->story->review = new stdclass();
$config->story->create->requiredFields = 'title';
$config->story->change->requiredFields = 'title';
$config->story->close->requiredFields  = 'closedReason';
$config->story->review->requiredFields = '';

$config->story->editor = new stdclass();
$config->story->editor->create   = array('id' => 'spec,verify', 'tools' => 'simpleTools');
$config->story->editor->change   = array('id' => 'spec,verify,comment', 'tools' => 'simpleTools');
$config->story->editor->edit     = array('id' => 'spec,verify,comment', 'tools' => 'simpleTools');
$config->story->editor->view     = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');
$config->story->editor->close    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->story->editor->review   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->story->editor->activate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->story->editor->assignto = array('id' => 'comment', 'tools' => 'simpleTools');

$config->story->list = new stdclass();
$config->story->exportFields = '
    id, product, branch, module, plan, source, sourceNote, title, spec, verify, keywords,
    pri, estimate, status, stage, category, taskCountAB, bugCountAB, caseCountAB,
    openedBy, openedDate, assignedTo, assignedDate, mailto,
    reviewedBy, reviewedDate,
    closedBy, closedDate, closedReason,
    lastEditedBy, lastEditedDate,
    childStories, linkStories, duplicateStory, files';

$config->story->list->customCreateFields      = 'source,verify,pri,estimate,mailto,keywords';
$config->story->list->customBatchCreateFields = 'plan,spec,source,verify,pri,estimate,review,keywords';
$config->story->list->customBatchEditFields   = 'branch,plan,estimate,pri,assignedTo,source,stage,closedBy,closedReason,keywords';

$config->story->list->actionsOpratedParentStory = ',edit,batchcreate,change,review,recall,submitreview,processstorychange,';

$config->story->custom = new stdclass();
$config->story->custom->createFields      = $config->story->list->customCreateFields;
$config->story->custom->batchCreateFields = 'module,plan,spec,pri,estimate,review,%s';
$config->story->custom->batchEditFields   = 'branch,module,plan,estimate,pri,source,stage,closedBy,closedReason';

$config->story->excludeCheckFileds = ',uploadImage,category,reviewer,reviewDitto,lanes,regions,branch,pri,';

global $lang, $app;
$config->story->datatable = new stdclass();

$config->story->datatable->defaultField = array('id', 'title', 'pri', 'plan', 'status', 'estimate', 'reviewedBy', 'stage', 'assignedTo', 'actions');

$config->story->datatable->fieldList['id']['title']    = 'idAB';
$config->story->datatable->fieldList['id']['fixed']    = 'left';
$config->story->datatable->fieldList['id']['width']    = '60';
$config->story->datatable->fieldList['id']['required'] = 'yes';

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
$config->story->datatable->fieldList['title']['width']    = 'auto';
$config->story->datatable->fieldList['title']['required'] = 'yes';

$config->story->datatable->fieldList['pri']['title']    = 'priAB';
$config->story->datatable->fieldList['pri']['fixed']    = 'left';
$config->story->datatable->fieldList['pri']['width']    = '40';
$config->story->datatable->fieldList['pri']['required'] = 'no';
$config->story->datatable->fieldList['pri']['name']     = $this->lang->story->pri;

$config->story->datatable->fieldList['plan']['title']      = 'planAB';
$config->story->datatable->fieldList['plan']['fixed']      = 'no';
$config->story->datatable->fieldList['plan']['width']      = '90';
$config->story->datatable->fieldList['plan']['required']   = 'no';
$config->story->datatable->fieldList['plan']['control']    = 'select';
$config->story->datatable->fieldList['plan']['dataSource'] = array('module' => 'productplan', 'method' => 'getPairs', 'params' => '$productID');

$config->story->datatable->fieldList['status']['title']    = 'statusAB';
$config->story->datatable->fieldList['status']['fixed']    = 'no';
$config->story->datatable->fieldList['status']['width']    = '60';
$config->story->datatable->fieldList['status']['required'] = 'no';

$config->story->datatable->fieldList['openedBy']['title']    = 'openedByAB';
$config->story->datatable->fieldList['openedBy']['fixed']    = 'no';
$config->story->datatable->fieldList['openedBy']['width']    = '60';
$config->story->datatable->fieldList['openedBy']['required'] = 'no';

$config->story->datatable->fieldList['estimate']['title']    = 'estimateAB';
$config->story->datatable->fieldList['estimate']['fixed']    = 'no';
$config->story->datatable->fieldList['estimate']['width']    = '50';
$config->story->datatable->fieldList['estimate']['required'] = 'no';

$config->story->datatable->fieldList['reviewer']['title']      = 'reviewedBy';
$config->story->datatable->fieldList['reviewer']['control']    = 'multiple';
$config->story->datatable->fieldList['reviewer']['dataSource'] = array('module' => 'story', 'method' => 'getStoriesReviewer', 'params' => '$productID');

$config->story->datatable->fieldList['reviewedBy']['title']      = 'reviewedBy';
$config->story->datatable->fieldList['reviewedBy']['fixed']      = 'no';
$config->story->datatable->fieldList['reviewedBy']['width']      = '100';
$config->story->datatable->fieldList['reviewedBy']['required']   = 'no';
$config->story->datatable->fieldList['reviewedBy']['control']    = 'multiple';
$config->story->datatable->fieldList['reviewedBy']['dataSource'] = array('module' => 'story', 'method' => 'getStoriesReviewer', 'params' => '$productID');


$config->story->datatable->fieldList['reviewedDate']['title']    = 'reviewedDate';
$config->story->datatable->fieldList['reviewedDate']['fixed']    = 'no';
$config->story->datatable->fieldList['reviewedDate']['width']    = '90';
$config->story->datatable->fieldList['reviewedDate']['required'] = 'no';

$config->story->datatable->fieldList['stage']['title']    = 'stageAB';
$config->story->datatable->fieldList['stage']['fixed']    = 'no';
$config->story->datatable->fieldList['stage']['width']    = '85';
$config->story->datatable->fieldList['stage']['required'] = 'no';

$config->story->datatable->fieldList['assignedTo']['title']    = 'assignedTo';
$config->story->datatable->fieldList['assignedTo']['fixed']    = 'no';
$config->story->datatable->fieldList['assignedTo']['width']    = '90';
$config->story->datatable->fieldList['assignedTo']['required'] = 'no';

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
$config->story->datatable->fieldList['actions']['width']    = $app->tab == 'project' ? '250' : '230';
$config->story->datatable->fieldList['actions']['required'] = 'yes';
