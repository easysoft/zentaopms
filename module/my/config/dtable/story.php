<?php
$config->my->story = new stdclass();
$config->my->story->actionList = array();
$config->my->story->actionList['change']['icon']        = 'alter';
$config->my->story->actionList['change']['text']        = $lang->story->change;
$config->my->story->actionList['change']['hint']        = $lang->story->change;
$config->my->story->actionList['change']['url']         = array('module' => 'story', 'method' => 'change', 'params' => 'storyID={id}');
$config->my->story->actionList['change']['data-toggle'] = 'modal';

$config->my->story->actionList['submitReview']['icon']        = 'confirm';
$config->my->story->actionList['submitReview']['text']        = $lang->story->submitReview;
$config->my->story->actionList['submitReview']['hint']        = $lang->story->submitReview;
$config->my->story->actionList['submitReview']['url']         = array('module' => 'story', 'method' => 'submitReview', 'params' => 'storyID={id}');
$config->my->story->actionList['submitReview']['data-toggle'] = 'modal';

$config->my->story->actionList['review']['icon']        = 'search';
$config->my->story->actionList['review']['text']        = $lang->story->review;
$config->my->story->actionList['review']['hint']        = $lang->story->review;
$config->my->story->actionList['review']['url']         = array('module' => 'story', 'method' => 'review', 'params' => 'storyID={id}');
$config->my->story->actionList['review']['data-toggle'] = 'modal';

$config->my->story->actionList['recall']['icon']      = 'undo';
$config->my->story->actionList['recall']['text']      = $lang->story->recall;
$config->my->story->actionList['recall']['hint']      = $lang->story->recall;
$config->my->story->actionList['recall']['url']       = array('module' => 'story', 'method' => 'recall', 'params' => 'storyID={id}');
$config->my->story->actionList['recall']['className'] = 'ajax-submit';

$config->my->story->actionList['edit']['icon']        = 'edit';
$config->my->story->actionList['edit']['text']        = $lang->story->edit;
$config->my->story->actionList['edit']['hint']        = $lang->story->edit;
$config->my->story->actionList['edit']['url']         = array('module' => 'story', 'method' => 'edit', 'params' => 'storyID={id}');
$config->my->story->actionList['edit']['data-toggle'] = 'modal';
$config->my->story->actionList['edit']['data-size']   = 'lg';

$config->my->story->actionList['create']['icon']        = 'sitemap';
$config->my->story->actionList['create']['text']        = $lang->testcase->create;
$config->my->story->actionList['create']['hint']        = $lang->testcase->create;
$config->my->story->actionList['create']['url']         = array('module' => 'testcase', 'method' => 'create', 'params' => 'productID={product}&branch={branch}&module=0&from=&param=0&storyID={id}');
$config->my->story->actionList['create']['data-toggle'] = 'modal';
$config->my->story->actionList['create']['data-size']   = 'lg';

$config->my->story->actionList['close']['icon']        = 'off';
$config->my->story->actionList['close']['text']        = $lang->story->close;
$config->my->story->actionList['close']['hint']        = $lang->story->close;
$config->my->story->actionList['close']['url']         = array('module' => 'story', 'method' => 'close', 'params' => 'storyID={id}');
$config->my->story->actionList['close']['data-toggle'] = 'modal';

$config->my->story->actionList['processStoryChange']['icon']        = 'ok';
$config->my->story->actionList['processStoryChange']['text']        = $lang->confirm;
$config->my->story->actionList['processStoryChange']['hint']        = $lang->confirm;
$config->my->story->actionList['processStoryChange']['url']         = array('module' => 'story', 'method' => 'processStoryChange', 'params' => 'storyID={id}');
$config->my->story->actionList['processStoryChange']['data-toggle'] = 'modal';

$config->my->story->dtable = new stdclass();
$config->my->story->dtable->fieldList['id']['name']     = 'id';
$config->my->story->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->story->dtable->fieldList['id']['fixed']    = 'left';
$config->my->story->dtable->fieldList['id']['required'] = true;
$config->my->story->dtable->fieldList['id']['type']     = 'id';
$config->my->story->dtable->fieldList['id']['sortType'] = true;
$config->my->story->dtable->fieldList['id']['show']     = true;
$config->my->story->dtable->fieldList['id']['group']    = 1;

$config->my->story->dtable->fieldList['title']['name']         = 'title';
$config->my->story->dtable->fieldList['title']['title']        = $lang->story->title;
$config->my->story->dtable->fieldList['title']['type']         = 'title';
$config->my->story->dtable->fieldList['title']['link']         = array('module' => 'story', 'method' => 'view', 'params' => 'id={id}');
$config->my->story->dtable->fieldList['title']['fixed']        = 'left';
$config->my->story->dtable->fieldList['title']['sortType']     = true;
$config->my->story->dtable->fieldList['title']['minWidth']     = '342';
$config->my->story->dtable->fieldList['title']['required']     = true;
$config->my->story->dtable->fieldList['title']['nestedToggle'] = true;
$config->my->story->dtable->fieldList['title']['show']         = true;
$config->my->story->dtable->fieldList['title']['group']        = 1;
$config->my->story->dtable->fieldList['title']['styleMap']     = array('--color-link' => 'color');

$config->my->story->dtable->fieldList['pri']['name']     = 'pri';
$config->my->story->dtable->fieldList['pri']['title']    = $lang->priAB;
$config->my->story->dtable->fieldList['pri']['fixed']    = 'left';
$config->my->story->dtable->fieldList['pri']['sortType'] = true;
$config->my->story->dtable->fieldList['pri']['type']     = 'pri';
$config->my->story->dtable->fieldList['pri']['priList']  = $lang->story->priList;
$config->my->story->dtable->fieldList['pri']['show']     = true;
$config->my->story->dtable->fieldList['pri']['group']    = 2;
if($isEn) $config->my->story->dtable->fieldList['pri']['width'] = '80';

$config->my->story->dtable->fieldList['productTitle']['name']     = 'productTitle';
$config->my->story->dtable->fieldList['productTitle']['title']    = $lang->story->product;
$config->my->story->dtable->fieldList['productTitle']['type']     = 'text';
$config->my->story->dtable->fieldList['productTitle']['sortType'] = true;
$config->my->story->dtable->fieldList['productTitle']['show']     = true;
$config->my->story->dtable->fieldList['productTitle']['group']    = 3;

if($config->vision == 'rnd')
{
    $config->my->story->dtable->fieldList['planTitle']['name']     = 'planTitle';
    $config->my->story->dtable->fieldList['planTitle']['title']    = $lang->story->planAB;
    $config->my->story->dtable->fieldList['planTitle']['type']     = 'text';
    $config->my->story->dtable->fieldList['planTitle']['sortType'] = true;
    $config->my->story->dtable->fieldList['planTitle']['width']    = '136';
    $config->my->story->dtable->fieldList['planTitle']['show']     = true;
    $config->my->story->dtable->fieldList['planTitle']['group']    = 3;
}

$config->my->story->dtable->fieldList['category']['name']     = 'category';
$config->my->story->dtable->fieldList['category']['title']    = $lang->story->category;
$config->my->story->dtable->fieldList['category']['sortType'] = true;
$config->my->story->dtable->fieldList['category']['type']     = 'category';
$config->my->story->dtable->fieldList['category']['map']      = $lang->story->categoryList;
$config->my->story->dtable->fieldList['category']['group']    = 4;

$config->my->story->dtable->fieldList['status']['name']      = 'status';
$config->my->story->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->my->story->dtable->fieldList['status']['sortType']  = true;
$config->my->story->dtable->fieldList['status']['type']      = 'status';
$config->my->story->dtable->fieldList['status']['statusMap'] = $lang->story->statusList;
$config->my->story->dtable->fieldList['status']['show']      = true;
$config->my->story->dtable->fieldList['status']['group']     = 4;

$config->my->story->dtable->fieldList['openedBy']['name']     = 'openedBy';
$config->my->story->dtable->fieldList['openedBy']['title']    = $lang->story->openedByAB;
$config->my->story->dtable->fieldList['openedBy']['sortType'] = true;
$config->my->story->dtable->fieldList['openedBy']['type']     = 'user';
$config->my->story->dtable->fieldList['openedBy']['show']     = true;
$config->my->story->dtable->fieldList['openedBy']['group']    = 5;

$config->my->story->dtable->fieldList['openedDate']['name']     = 'openedDate';
$config->my->story->dtable->fieldList['openedDate']['title']    = $lang->story->openedDate;
$config->my->story->dtable->fieldList['openedDate']['sortType'] = true;
$config->my->story->dtable->fieldList['openedDate']['type']     = 'date';
$config->my->story->dtable->fieldList['openedDate']['group']    = 5;

$config->my->story->dtable->fieldList['estimate']['name']     = 'estimate';
$config->my->story->dtable->fieldList['estimate']['title']    = $lang->story->estimateAB;
$config->my->story->dtable->fieldList['estimate']['sortType'] = true;
$config->my->story->dtable->fieldList['estimate']['type']     = 'number';
$config->my->story->dtable->fieldList['estimate']['show']     = true;
$config->my->story->dtable->fieldList['estimate']['group']    = 5;
if($isEn) $config->my->story->dtable->fieldList['estimate']['width'] = '90';

$config->my->story->dtable->fieldList['reviewer']['name']     = 'reviewer';
$config->my->story->dtable->fieldList['reviewer']['title']    = $lang->story->reviewer;
$config->my->story->dtable->fieldList['reviewer']['type']     = 'text';
$config->my->story->dtable->fieldList['reviewer']['width']    = '100';
$config->my->story->dtable->fieldList['reviewer']['sortType'] = false;
$config->my->story->dtable->fieldList['reviewer']['show']     = true;
$config->my->story->dtable->fieldList['reviewer']['group']    = 5;
if($isEn) $config->my->story->dtable->fieldList['reviewer']['width'] = '120';

$config->my->story->dtable->fieldList['reviewedDate']['name']     = 'reviewedDate';
$config->my->story->dtable->fieldList['reviewedDate']['title']    = $lang->story->reviewedDate;
$config->my->story->dtable->fieldList['reviewedDate']['sortType'] = true;
$config->my->story->dtable->fieldList['reviewedDate']['type']     = 'date';
$config->my->story->dtable->fieldList['reviewedDate']['group']    = 5;

$config->my->story->dtable->fieldList['stage']['name']      = 'stage';
$config->my->story->dtable->fieldList['stage']['title']     = $lang->story->stageAB;
$config->my->story->dtable->fieldList['stage']['sortType']  = true;
$config->my->story->dtable->fieldList['stage']['type']      = 'status';
$config->my->story->dtable->fieldList['stage']['statusMap']  = $lang->story->stageList;
$config->my->story->dtable->fieldList['stage']['show']      = true;
$config->my->story->dtable->fieldList['stage']['group']     = 6;
if($isEn) $config->my->story->dtable->fieldList['stage']['width'] = '120';

$config->my->story->dtable->fieldList['assignedTo']['name']        = 'assignedTo';
$config->my->story->dtable->fieldList['assignedTo']['title']       = $lang->story->assignedTo;
$config->my->story->dtable->fieldList['assignedTo']['sortType']    = true;
$config->my->story->dtable->fieldList['assignedTo']['type']        = 'user';
$config->my->story->dtable->fieldList['assignedTo']['show']        = true;
$config->my->story->dtable->fieldList['assignedTo']['group']       = 6;
if($isEn) $config->my->story->dtable->fieldList['assignedTo']['width'] = '120';

$config->my->story->dtable->fieldList['assignedDate']['name']     = 'assignedDate';
$config->my->story->dtable->fieldList['assignedDate']['title']    = $lang->story->assignedDate;
$config->my->story->dtable->fieldList['assignedDate']['sortType'] = true;
$config->my->story->dtable->fieldList['assignedDate']['type']     = 'date';
$config->my->story->dtable->fieldList['assignedDate']['group']    = 6;

$config->my->story->dtable->fieldList['closedBy']['name']     = 'closedBy';
$config->my->story->dtable->fieldList['closedBy']['title']    = $lang->story->closedBy;
$config->my->story->dtable->fieldList['closedBy']['sortType'] = true;
$config->my->story->dtable->fieldList['closedBy']['type']     = 'user';
$config->my->story->dtable->fieldList['closedBy']['group']    = 8;

$config->my->story->dtable->fieldList['closedDate']['name']     = 'closedDate';
$config->my->story->dtable->fieldList['closedDate']['title']    = $lang->story->closedDate;
$config->my->story->dtable->fieldList['closedDate']['sortType'] = true;
$config->my->story->dtable->fieldList['closedDate']['type']     = 'date';
$config->my->story->dtable->fieldList['closedDate']['group']    = 8;

$config->my->story->dtable->fieldList['closedReason']['name']     = 'closedReason';
$config->my->story->dtable->fieldList['closedReason']['title']    = $lang->story->closedReason;
$config->my->story->dtable->fieldList['closedReason']['sortType'] = true;
$config->my->story->dtable->fieldList['closedReason']['width']    = '90';
$config->my->story->dtable->fieldList['closedReason']['type']     = 'category';
$config->my->story->dtable->fieldList['closedReason']['map']      = $lang->story->reasonList;
$config->my->story->dtable->fieldList['closedReason']['group']    = 8;

$config->my->story->dtable->fieldList['lastEditedBy']['name']     = 'lastEditedBy';
$config->my->story->dtable->fieldList['lastEditedBy']['title']    = $lang->story->lastEditedBy;
$config->my->story->dtable->fieldList['lastEditedBy']['sortType'] = true;
$config->my->story->dtable->fieldList['lastEditedBy']['type']     = 'user';
$config->my->story->dtable->fieldList['lastEditedBy']['group']    = 9;

$config->my->story->dtable->fieldList['lastEditedDate']['name']     = 'lastEditedDate';
$config->my->story->dtable->fieldList['lastEditedDate']['title']    = $lang->story->lastEditedDate;
$config->my->story->dtable->fieldList['lastEditedDate']['sortType'] = true;
$config->my->story->dtable->fieldList['lastEditedDate']['type']     = 'date';
$config->my->story->dtable->fieldList['lastEditedDate']['width']    = '120';
$config->my->story->dtable->fieldList['lastEditedDate']['group']    = 9;

$config->my->story->dtable->fieldList['keywords']['name']     = 'keywords';
$config->my->story->dtable->fieldList['keywords']['title']    = $lang->story->keywords;
$config->my->story->dtable->fieldList['keywords']['sortType'] = true;
$config->my->story->dtable->fieldList['keywords']['width']    = '100';
$config->my->story->dtable->fieldList['keywords']['group']    = 10;

$config->my->story->dtable->fieldList['source']['name']     = 'source';
$config->my->story->dtable->fieldList['source']['title']    = $lang->story->source;
$config->my->story->dtable->fieldList['source']['sortType'] = true;
$config->my->story->dtable->fieldList['source']['width']    = '90';
$config->my->story->dtable->fieldList['source']['type']     = 'category';
$config->my->story->dtable->fieldList['source']['map']      = $lang->story->sourceList;
$config->my->story->dtable->fieldList['source']['group']    = 10;

$config->my->story->dtable->fieldList['sourceNote']['name']     = 'sourceNote';
$config->my->story->dtable->fieldList['sourceNote']['title']    = $lang->story->sourceNote;
$config->my->story->dtable->fieldList['sourceNote']['width']    = '90';
$config->my->story->dtable->fieldList['sourceNote']['sortType'] = true;
$config->my->story->dtable->fieldList['sourceNote']['group']    = 10;

$config->my->story->dtable->fieldList['feedbackBy']['name']     = 'feedbackBy';
$config->my->story->dtable->fieldList['feedbackBy']['title']    = $lang->story->feedbackBy;
$config->my->story->dtable->fieldList['feedbackBy']['sortType'] = true;
$config->my->story->dtable->fieldList['feedbackBy']['width']    = '90';
$config->my->story->dtable->fieldList['feedbackBy']['group']    = 10;

$config->my->story->dtable->fieldList['activatedDate']['name']     = 'activatedDate';
$config->my->story->dtable->fieldList['activatedDate']['title']    = $lang->story->activatedDate;
$config->my->story->dtable->fieldList['activatedDate']['sortType'] = true;
$config->my->story->dtable->fieldList['activatedDate']['type']     = 'date';
$config->my->story->dtable->fieldList['activatedDate']['group']    = 11;

$config->my->story->dtable->fieldList['notifyEmail']['name']     = 'notifyEmail';
$config->my->story->dtable->fieldList['notifyEmail']['title']    = $lang->story->notifyEmail;
$config->my->story->dtable->fieldList['notifyEmail']['width']    = '100';
$config->my->story->dtable->fieldList['notifyEmail']['sortType'] = true;
$config->my->story->dtable->fieldList['notifyEmail']['group']    = 11;

$config->my->story->dtable->fieldList['mailto']['name']     = 'mailto';
$config->my->story->dtable->fieldList['mailto']['title']    = $lang->story->mailto;
$config->my->story->dtable->fieldList['mailto']['width']    = '100';
$config->my->story->dtable->fieldList['mailto']['sortType'] = true;
$config->my->story->dtable->fieldList['mailto']['group']    = 11;

$config->my->story->dtable->fieldList['version']['name']     = 'version';
$config->my->story->dtable->fieldList['version']['title']    = $lang->story->version;
$config->my->story->dtable->fieldList['version']['type']     = 'number';
$config->my->story->dtable->fieldList['version']['sortType'] = true;
$config->my->story->dtable->fieldList['version']['group']    = 11;

$config->my->story->dtable->fieldList['actions']['name']     = 'actions';
$config->my->story->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->story->dtable->fieldList['actions']['fixed']    = 'right';
$config->my->story->dtable->fieldList['actions']['required'] = true;
$config->my->story->dtable->fieldList['actions']['type']     = 'actions';
$config->my->story->dtable->fieldList['actions']['width']    = 140;
$config->my->story->dtable->fieldList['actions']['sortType'] = false;
$config->my->story->dtable->fieldList['actions']['list']     = $config->my->story->actionList;
$config->my->story->dtable->fieldList['actions']['menu']     = array(array('processStoryChange'), array('change', 'review|submitReview', 'recall', 'edit', 'create', 'close'));
