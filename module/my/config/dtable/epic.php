<?php
$config->my->epic = new stdclass();
$config->my->epic->actionList = array();
$config->my->epic->actionList['change']['icon']        = 'alter';
$config->my->epic->actionList['change']['text']        = $lang->story->change;
$config->my->epic->actionList['change']['hint']        = $lang->story->change;
$config->my->epic->actionList['change']['url']         = array('module' => 'epic', 'method' => 'change', 'params' => 'storyID={id}&from=&storyType=epic');
$config->my->epic->actionList['change']['data-toggle'] = 'modal';

$config->my->epic->actionList['submitReview']['icon']        = 'confirm';
$config->my->epic->actionList['submitReview']['text']        = $lang->story->submitReview;
$config->my->epic->actionList['submitReview']['hint']        = $lang->story->submitReview;
$config->my->epic->actionList['submitReview']['url']         = array('module' => 'epic', 'method' => 'submitReview', 'params' => 'storyID={id}&storyType=epic');
$config->my->epic->actionList['submitReview']['data-toggle'] = 'modal';

$config->my->epic->actionList['review']['icon']        = 'search';
$config->my->epic->actionList['review']['text']        = $lang->story->review;
$config->my->epic->actionList['review']['hint']        = $lang->story->review;
$config->my->epic->actionList['review']['url']         = array('module' => 'epic', 'method' => 'review', 'params' => 'storyID={id}&from=product&storyType=epic');
$config->my->epic->actionList['review']['data-toggle'] = 'modal';

$config->my->epic->actionList['recall']['icon']      = 'undo';
$config->my->epic->actionList['recall']['text']      = $lang->story->recall;
$config->my->epic->actionList['recall']['hint']      = $lang->story->recall;
$config->my->epic->actionList['recall']['url']       = array('module' => 'epic', 'method' => 'recall', 'params' => 'storyID={id}&from=list&confirm=no&storyType=epic');
$config->my->epic->actionList['recall']['className'] = 'ajax-submit';

$config->my->epic->actionList['edit']['icon']        = 'edit';
$config->my->epic->actionList['edit']['text']        = $lang->story->edit;
$config->my->epic->actionList['edit']['hint']        = $lang->story->edit;
$config->my->epic->actionList['edit']['url']         = array('module' => 'epic', 'method' => 'edit', 'params' => 'storyID={id}&from=default&storyType=epic');
$config->my->epic->actionList['edit']['data-toggle'] = 'modal';
$config->my->epic->actionList['edit']['data-size']   = 'lg';

$config->my->epic->actionList['close']['icon']        = 'off';
$config->my->epic->actionList['close']['text']        = $lang->story->close;
$config->my->epic->actionList['close']['hint']        = $lang->story->close;
$config->my->epic->actionList['close']['url']         = array('module' => 'epic', 'method' => 'close', 'params' => 'storyID={id}&from=&storyType=epic');
$config->my->epic->actionList['close']['data-toggle'] = 'modal';

$config->my->epic->dtable = new stdclass();
$config->my->epic->dtable->fieldList['id']['name']     = 'id';
$config->my->epic->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->epic->dtable->fieldList['id']['fixed']    = 'left';
$config->my->epic->dtable->fieldList['id']['required'] = true;
$config->my->epic->dtable->fieldList['id']['type']     = 'id';
$config->my->epic->dtable->fieldList['id']['sortType'] = true;
$config->my->epic->dtable->fieldList['id']['show']     = true;
$config->my->epic->dtable->fieldList['id']['group']    = 1;

$config->my->epic->dtable->fieldList['title']['name']         = 'title';
$config->my->epic->dtable->fieldList['title']['title']        = common::checkNotCN() ? $lang->ERCommon . ' ' . $lang->my->name : $lang->ERCommon . $lang->my->name;
$config->my->epic->dtable->fieldList['title']['type']         = 'title';
$config->my->epic->dtable->fieldList['title']['link']         = array('module' => 'epic', 'method' => 'view', 'params' => 'id={id}&version=0&param=0&storyType=epic');
$config->my->epic->dtable->fieldList['title']['fixed']        = 'left';
$config->my->epic->dtable->fieldList['title']['sortType']     = true;
$config->my->epic->dtable->fieldList['title']['minWidth']     = '342';
$config->my->epic->dtable->fieldList['title']['required']     = true;
$config->my->epic->dtable->fieldList['title']['nestedToggle'] = true;
$config->my->epic->dtable->fieldList['title']['show']         = true;
$config->my->epic->dtable->fieldList['title']['group']        = 1;
$config->my->epic->dtable->fieldList['title']['styleMap']     = array('--color-link' => 'color');

$config->my->epic->dtable->fieldList['pri']['name']     = 'pri';
$config->my->epic->dtable->fieldList['pri']['title']    = $lang->priAB;
$config->my->epic->dtable->fieldList['pri']['fixed']    = 'left';
$config->my->epic->dtable->fieldList['pri']['sortType'] = true;
$config->my->epic->dtable->fieldList['pri']['type']     = 'pri';
$config->my->epic->dtable->fieldList['pri']['priList']  = $lang->story->priList;
$config->my->epic->dtable->fieldList['pri']['show']     = true;
$config->my->epic->dtable->fieldList['pri']['group']    = 2;
if($isEn) $config->my->epic->dtable->fieldList['pri']['width'] = '80';

$config->my->epic->dtable->fieldList['productTitle']['name']     = 'productTitle';
$config->my->epic->dtable->fieldList['productTitle']['title']    = $lang->story->product;
$config->my->epic->dtable->fieldList['productTitle']['type']     = 'text';
$config->my->epic->dtable->fieldList['productTitle']['sortType'] = true;
$config->my->epic->dtable->fieldList['productTitle']['show']     = true;
$config->my->epic->dtable->fieldList['productTitle']['group']    = 3;

if($config->vision == 'rnd')
{
    $config->my->epic->dtable->fieldList['planTitle']['name']     = 'planTitle';
    $config->my->epic->dtable->fieldList['planTitle']['title']    = $lang->story->planAB;
    $config->my->epic->dtable->fieldList['planTitle']['type']     = 'text';
    $config->my->epic->dtable->fieldList['planTitle']['sortType'] = true;
    $config->my->epic->dtable->fieldList['planTitle']['width']    = '136';
    $config->my->epic->dtable->fieldList['planTitle']['show']     = true;
    $config->my->epic->dtable->fieldList['planTitle']['group']    = 3;
}

$config->my->epic->dtable->fieldList['category']['name']     = 'category';
$config->my->epic->dtable->fieldList['category']['title']    = $lang->story->category;
$config->my->epic->dtable->fieldList['category']['sortType'] = true;
$config->my->epic->dtable->fieldList['category']['type']     = 'category';
$config->my->epic->dtable->fieldList['category']['map']      = $lang->story->categoryList;
$config->my->epic->dtable->fieldList['category']['group']    = 4;

$config->my->epic->dtable->fieldList['status']['name']      = 'status';
$config->my->epic->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->my->epic->dtable->fieldList['status']['sortType']  = true;
$config->my->epic->dtable->fieldList['status']['type']      = 'status';
$config->my->epic->dtable->fieldList['status']['statusMap'] = $lang->story->statusList;
$config->my->epic->dtable->fieldList['status']['show']      = true;
$config->my->epic->dtable->fieldList['status']['group']     = 4;

$config->my->epic->dtable->fieldList['openedBy']['name']     = 'openedBy';
$config->my->epic->dtable->fieldList['openedBy']['title']    = $lang->story->openedByAB;
$config->my->epic->dtable->fieldList['openedBy']['sortType'] = true;
$config->my->epic->dtable->fieldList['openedBy']['type']     = 'user';
$config->my->epic->dtable->fieldList['openedBy']['show']     = true;
$config->my->epic->dtable->fieldList['openedBy']['group']    = 5;

$config->my->epic->dtable->fieldList['openedDate']['name']     = 'openedDate';
$config->my->epic->dtable->fieldList['openedDate']['title']    = $lang->story->openedDate;
$config->my->epic->dtable->fieldList['openedDate']['sortType'] = true;
$config->my->epic->dtable->fieldList['openedDate']['type']     = 'date';
$config->my->epic->dtable->fieldList['openedDate']['group']    = 5;

$config->my->epic->dtable->fieldList['estimate']['name']     = 'estimate';
$config->my->epic->dtable->fieldList['estimate']['title']    = $lang->story->estimateAB;
$config->my->epic->dtable->fieldList['estimate']['sortType'] = true;
$config->my->epic->dtable->fieldList['estimate']['type']     = 'number';
$config->my->epic->dtable->fieldList['estimate']['show']     = true;
$config->my->epic->dtable->fieldList['estimate']['group']    = 5;
if($isEn) $config->my->epic->dtable->fieldList['estimate']['width'] = '90';

$config->my->epic->dtable->fieldList['reviewer']['name']     = 'reviewer';
$config->my->epic->dtable->fieldList['reviewer']['title']    = $lang->story->reviewer;
$config->my->epic->dtable->fieldList['reviewer']['type']     = 'text';
$config->my->epic->dtable->fieldList['reviewer']['width']    = '100';
$config->my->epic->dtable->fieldList['reviewer']['sortType'] = false;
$config->my->epic->dtable->fieldList['reviewer']['show']     = true;
$config->my->epic->dtable->fieldList['reviewer']['group']    = 5;
if($isEn) $config->my->epic->dtable->fieldList['reviewer']['width'] = '120';

$config->my->epic->dtable->fieldList['reviewedDate']['name']     = 'reviewedDate';
$config->my->epic->dtable->fieldList['reviewedDate']['title']    = $lang->story->reviewedDate;
$config->my->epic->dtable->fieldList['reviewedDate']['sortType'] = true;
$config->my->epic->dtable->fieldList['reviewedDate']['type']     = 'date';
$config->my->epic->dtable->fieldList['reviewedDate']['group']    = 5;

$config->my->epic->dtable->fieldList['stage']['name']      = 'stage';
$config->my->epic->dtable->fieldList['stage']['title']     = $lang->story->stageAB;
$config->my->epic->dtable->fieldList['stage']['sortType']  = true;
$config->my->epic->dtable->fieldList['stage']['type']      = 'status';
$config->my->epic->dtable->fieldList['stage']['statusMap']  = $lang->story->stageList + $lang->epic->stageList;
$config->my->epic->dtable->fieldList['stage']['show']      = true;
$config->my->epic->dtable->fieldList['stage']['group']     = 6;
if($isEn) $config->my->epic->dtable->fieldList['stage']['width'] = '120';

$config->my->epic->dtable->fieldList['assignedTo']['name']        = 'assignedTo';
$config->my->epic->dtable->fieldList['assignedTo']['title']       = $lang->story->assignedTo;
$config->my->epic->dtable->fieldList['assignedTo']['sortType']    = true;
$config->my->epic->dtable->fieldList['assignedTo']['type']        = 'user';
$config->my->epic->dtable->fieldList['assignedTo']['show']        = true;
$config->my->epic->dtable->fieldList['assignedTo']['group']       = 6;
if($isEn) $config->my->epic->dtable->fieldList['assignedTo']['width'] = '120';

$config->my->epic->dtable->fieldList['assignedDate']['name']     = 'assignedDate';
$config->my->epic->dtable->fieldList['assignedDate']['title']    = $lang->story->assignedDate;
$config->my->epic->dtable->fieldList['assignedDate']['sortType'] = true;
$config->my->epic->dtable->fieldList['assignedDate']['type']     = 'date';
$config->my->epic->dtable->fieldList['assignedDate']['group']    = 6;

$config->my->epic->dtable->fieldList['closedBy']['name']     = 'closedBy';
$config->my->epic->dtable->fieldList['closedBy']['title']    = $lang->story->closedBy;
$config->my->epic->dtable->fieldList['closedBy']['sortType'] = true;
$config->my->epic->dtable->fieldList['closedBy']['type']     = 'user';
$config->my->epic->dtable->fieldList['closedBy']['group']    = 8;

$config->my->epic->dtable->fieldList['closedDate']['name']     = 'closedDate';
$config->my->epic->dtable->fieldList['closedDate']['title']    = $lang->story->closedDate;
$config->my->epic->dtable->fieldList['closedDate']['sortType'] = true;
$config->my->epic->dtable->fieldList['closedDate']['type']     = 'date';
$config->my->epic->dtable->fieldList['closedDate']['group']    = 8;

$config->my->epic->dtable->fieldList['closedReason']['name']     = 'closedReason';
$config->my->epic->dtable->fieldList['closedReason']['title']    = $lang->story->closedReason;
$config->my->epic->dtable->fieldList['closedReason']['sortType'] = true;
$config->my->epic->dtable->fieldList['closedReason']['width']    = '90';
$config->my->epic->dtable->fieldList['closedReason']['type']     = 'category';
$config->my->epic->dtable->fieldList['closedReason']['map']      = $lang->story->reasonList;
$config->my->epic->dtable->fieldList['closedReason']['group']    = 8;

$config->my->epic->dtable->fieldList['lastEditedBy']['name']     = 'lastEditedBy';
$config->my->epic->dtable->fieldList['lastEditedBy']['title']    = $lang->story->lastEditedBy;
$config->my->epic->dtable->fieldList['lastEditedBy']['sortType'] = true;
$config->my->epic->dtable->fieldList['lastEditedBy']['type']     = 'user';
$config->my->epic->dtable->fieldList['lastEditedBy']['group']    = 9;

$config->my->epic->dtable->fieldList['lastEditedDate']['name']     = 'lastEditedDate';
$config->my->epic->dtable->fieldList['lastEditedDate']['title']    = $lang->story->lastEditedDate;
$config->my->epic->dtable->fieldList['lastEditedDate']['sortType'] = true;
$config->my->epic->dtable->fieldList['lastEditedDate']['type']     = 'date';
$config->my->epic->dtable->fieldList['lastEditedDate']['width']    = '120';
$config->my->epic->dtable->fieldList['lastEditedDate']['group']    = 9;

$config->my->epic->dtable->fieldList['keywords']['name']     = 'keywords';
$config->my->epic->dtable->fieldList['keywords']['title']    = $lang->story->keywords;
$config->my->epic->dtable->fieldList['keywords']['sortType'] = true;
$config->my->epic->dtable->fieldList['keywords']['width']    = '100';
$config->my->epic->dtable->fieldList['keywords']['group']    = 10;

$config->my->epic->dtable->fieldList['source']['name']     = 'source';
$config->my->epic->dtable->fieldList['source']['title']    = $lang->story->source;
$config->my->epic->dtable->fieldList['source']['sortType'] = true;
$config->my->epic->dtable->fieldList['source']['width']    = '90';
$config->my->epic->dtable->fieldList['source']['type']     = 'category';
$config->my->epic->dtable->fieldList['source']['map']      = $lang->story->sourceList;
$config->my->epic->dtable->fieldList['source']['group']    = 10;

$config->my->epic->dtable->fieldList['sourceNote']['name']     = 'sourceNote';
$config->my->epic->dtable->fieldList['sourceNote']['title']    = $lang->story->sourceNote;
$config->my->epic->dtable->fieldList['sourceNote']['width']    = '90';
$config->my->epic->dtable->fieldList['sourceNote']['sortType'] = true;
$config->my->epic->dtable->fieldList['sourceNote']['group']    = 10;

$config->my->epic->dtable->fieldList['feedbackBy']['name']     = 'feedbackBy';
$config->my->epic->dtable->fieldList['feedbackBy']['title']    = $lang->story->feedbackBy;
$config->my->epic->dtable->fieldList['feedbackBy']['sortType'] = true;
$config->my->epic->dtable->fieldList['feedbackBy']['width']    = '90';
$config->my->epic->dtable->fieldList['feedbackBy']['group']    = 10;

$config->my->epic->dtable->fieldList['activatedDate']['name']     = 'activatedDate';
$config->my->epic->dtable->fieldList['activatedDate']['title']    = $lang->story->activatedDate;
$config->my->epic->dtable->fieldList['activatedDate']['sortType'] = true;
$config->my->epic->dtable->fieldList['activatedDate']['type']     = 'date';
$config->my->epic->dtable->fieldList['activatedDate']['group']    = 11;

$config->my->epic->dtable->fieldList['notifyEmail']['name']     = 'notifyEmail';
$config->my->epic->dtable->fieldList['notifyEmail']['title']    = $lang->story->notifyEmail;
$config->my->epic->dtable->fieldList['notifyEmail']['width']    = '100';
$config->my->epic->dtable->fieldList['notifyEmail']['sortType'] = true;
$config->my->epic->dtable->fieldList['notifyEmail']['group']    = 11;

$config->my->epic->dtable->fieldList['mailto']['name']     = 'mailto';
$config->my->epic->dtable->fieldList['mailto']['title']    = $lang->story->mailto;
$config->my->epic->dtable->fieldList['mailto']['width']    = '100';
$config->my->epic->dtable->fieldList['mailto']['sortType'] = true;
$config->my->epic->dtable->fieldList['mailto']['group']    = 11;

$config->my->epic->dtable->fieldList['version']['name']     = 'version';
$config->my->epic->dtable->fieldList['version']['title']    = $lang->story->version;
$config->my->epic->dtable->fieldList['version']['type']     = 'number';
$config->my->epic->dtable->fieldList['version']['sortType'] = true;
$config->my->epic->dtable->fieldList['version']['group']    = 11;

$config->my->epic->dtable->fieldList['actions']['name']     = 'actions';
$config->my->epic->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->epic->dtable->fieldList['actions']['fixed']    = 'right';
$config->my->epic->dtable->fieldList['actions']['required'] = true;
$config->my->epic->dtable->fieldList['actions']['type']     = 'actions';
$config->my->epic->dtable->fieldList['actions']['width']    = 140;
$config->my->epic->dtable->fieldList['actions']['sortType'] = false;
$config->my->epic->dtable->fieldList['actions']['list']     = $config->my->epic->actionList;
$config->my->epic->dtable->fieldList['actions']['menu']     = array('change', 'review|submitReview', 'recall', 'edit', 'close');
