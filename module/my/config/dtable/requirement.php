<?php
$config->my->requirement = new stdclass();
$config->my->requirement->actionList = array();
$config->my->requirement->actionList['change']['icon']        = 'alter';
$config->my->requirement->actionList['change']['text']        = $lang->story->change;
$config->my->requirement->actionList['change']['hint']        = $lang->story->change;
$config->my->requirement->actionList['change']['url']         = array('module' => 'requirement', 'method' => 'change', 'params' => 'storyID={id}&from=&storyType=requirement');
$config->my->requirement->actionList['change']['data-toggle'] = 'modal';

$config->my->requirement->actionList['submitReview']['icon']        = 'confirm';
$config->my->requirement->actionList['submitReview']['text']        = $lang->story->submitReview;
$config->my->requirement->actionList['submitReview']['hint']        = $lang->story->submitReview;
$config->my->requirement->actionList['submitReview']['url']         = array('module' => 'requirement', 'method' => 'submitReview', 'params' => 'storyID={id}&storyType=requirement');
$config->my->requirement->actionList['submitReview']['data-toggle'] = 'modal';

$config->my->requirement->actionList['review']['icon']        = 'search';
$config->my->requirement->actionList['review']['text']        = $lang->story->review;
$config->my->requirement->actionList['review']['hint']        = $lang->story->review;
$config->my->requirement->actionList['review']['url']         = array('module' => 'requirement', 'method' => 'review', 'params' => 'storyID={id}&from=product&storyType=requirement');
$config->my->requirement->actionList['review']['data-toggle'] = 'modal';

$config->my->requirement->actionList['recall']['icon']      = 'undo';
$config->my->requirement->actionList['recall']['text']      = $lang->story->recall;
$config->my->requirement->actionList['recall']['hint']      = $lang->story->recall;
$config->my->requirement->actionList['recall']['url']       = array('module' => 'requirement', 'method' => 'recall', 'params' => 'storyID={id}&from=list&confirm=no&storyType=requirement');
$config->my->requirement->actionList['recall']['className'] = 'ajax-submit';

$config->my->requirement->actionList['edit']['icon']        = 'edit';
$config->my->requirement->actionList['edit']['text']        = $lang->story->edit;
$config->my->requirement->actionList['edit']['hint']        = $lang->story->edit;
$config->my->requirement->actionList['edit']['url']         = array('module' => 'requirement', 'method' => 'edit', 'params' => 'storyID={id}&from=default&storyType=requirement');
$config->my->requirement->actionList['edit']['data-toggle'] = 'modal';
$config->my->requirement->actionList['edit']['data-size']   = 'lg';

$config->my->requirement->actionList['close']['icon']        = 'off';
$config->my->requirement->actionList['close']['text']        = $lang->story->close;
$config->my->requirement->actionList['close']['hint']        = $lang->story->close;
$config->my->requirement->actionList['close']['url']         = array('module' => 'requirement', 'method' => 'close', 'params' => 'storyID={id}&from=&storyType=requirement');
$config->my->requirement->actionList['close']['data-toggle'] = 'modal';

$config->my->requirement->dtable = new stdclass();
$config->my->requirement->dtable->fieldList['id']['name']     = 'id';
$config->my->requirement->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->requirement->dtable->fieldList['id']['fixed']    = 'left';
$config->my->requirement->dtable->fieldList['id']['required'] = true;
$config->my->requirement->dtable->fieldList['id']['type']     = 'id';
$config->my->requirement->dtable->fieldList['id']['sortType'] = true;
$config->my->requirement->dtable->fieldList['id']['show']     = true;
$config->my->requirement->dtable->fieldList['id']['group']    = 1;

$config->my->requirement->dtable->fieldList['title']['name']         = 'title';
$config->my->requirement->dtable->fieldList['title']['title']        = common::checkNotCN() ? $lang->URCommon . ' ' . $lang->my->name : $lang->URCommon . $lang->my->name;
$config->my->requirement->dtable->fieldList['title']['type']         = 'title';
$config->my->requirement->dtable->fieldList['title']['link']         = array('module' => 'requirement', 'method' => 'view', 'params' => 'id={id}&version=0&param=0&storyType=requirement');
$config->my->requirement->dtable->fieldList['title']['fixed']        = 'left';
$config->my->requirement->dtable->fieldList['title']['sortType']     = true;
$config->my->requirement->dtable->fieldList['title']['minWidth']     = '342';
$config->my->requirement->dtable->fieldList['title']['required']     = true;
$config->my->requirement->dtable->fieldList['title']['nestedToggle'] = true;
$config->my->requirement->dtable->fieldList['title']['show']         = true;
$config->my->requirement->dtable->fieldList['title']['group']        = 1;
$config->my->requirement->dtable->fieldList['title']['styleMap']     = array('--color-link' => 'color');

$config->my->requirement->dtable->fieldList['pri']['name']     = 'pri';
$config->my->requirement->dtable->fieldList['pri']['title']    = $lang->priAB;
$config->my->requirement->dtable->fieldList['pri']['fixed']    = 'left';
$config->my->requirement->dtable->fieldList['pri']['sortType'] = true;
$config->my->requirement->dtable->fieldList['pri']['type']     = 'pri';
$config->my->requirement->dtable->fieldList['pri']['priList']  = $lang->story->priList;
$config->my->requirement->dtable->fieldList['pri']['show']     = true;
$config->my->requirement->dtable->fieldList['pri']['group']    = 2;
if($isEn) $config->my->requirement->dtable->fieldList['pri']['width'] = '80';

$config->my->requirement->dtable->fieldList['productTitle']['name']     = 'productTitle';
$config->my->requirement->dtable->fieldList['productTitle']['title']    = $lang->story->product;
$config->my->requirement->dtable->fieldList['productTitle']['type']     = 'text';
$config->my->requirement->dtable->fieldList['productTitle']['sortType'] = true;
$config->my->requirement->dtable->fieldList['productTitle']['show']     = true;
$config->my->requirement->dtable->fieldList['productTitle']['group']    = 3;

if($config->vision == 'rnd')
{
    $config->my->requirement->dtable->fieldList['planTitle']['name']     = 'planTitle';
    $config->my->requirement->dtable->fieldList['planTitle']['title']    = $lang->story->planAB;
    $config->my->requirement->dtable->fieldList['planTitle']['type']     = 'text';
    $config->my->requirement->dtable->fieldList['planTitle']['sortType'] = true;
    $config->my->requirement->dtable->fieldList['planTitle']['width']    = '136';
    $config->my->requirement->dtable->fieldList['planTitle']['show']     = true;
    $config->my->requirement->dtable->fieldList['planTitle']['group']    = 3;
}

$config->my->requirement->dtable->fieldList['category']['name']     = 'category';
$config->my->requirement->dtable->fieldList['category']['title']    = $lang->story->category;
$config->my->requirement->dtable->fieldList['category']['sortType'] = true;
$config->my->requirement->dtable->fieldList['category']['type']     = 'category';
$config->my->requirement->dtable->fieldList['category']['map']      = $lang->story->categoryList;
$config->my->requirement->dtable->fieldList['category']['group']    = 4;

$config->my->requirement->dtable->fieldList['status']['name']      = 'status';
$config->my->requirement->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->my->requirement->dtable->fieldList['status']['sortType']  = true;
$config->my->requirement->dtable->fieldList['status']['type']      = 'status';
$config->my->requirement->dtable->fieldList['status']['statusMap'] = $lang->story->statusList;
$config->my->requirement->dtable->fieldList['status']['show']      = true;
$config->my->requirement->dtable->fieldList['status']['group']     = 4;

$config->my->requirement->dtable->fieldList['openedBy']['name']     = 'openedBy';
$config->my->requirement->dtable->fieldList['openedBy']['title']    = $lang->story->openedByAB;
$config->my->requirement->dtable->fieldList['openedBy']['sortType'] = true;
$config->my->requirement->dtable->fieldList['openedBy']['type']     = 'user';
$config->my->requirement->dtable->fieldList['openedBy']['show']     = true;
$config->my->requirement->dtable->fieldList['openedBy']['group']    = 5;

$config->my->requirement->dtable->fieldList['openedDate']['name']     = 'openedDate';
$config->my->requirement->dtable->fieldList['openedDate']['title']    = $lang->story->openedDate;
$config->my->requirement->dtable->fieldList['openedDate']['sortType'] = true;
$config->my->requirement->dtable->fieldList['openedDate']['type']     = 'date';
$config->my->requirement->dtable->fieldList['openedDate']['group']    = 5;

$config->my->requirement->dtable->fieldList['estimate']['name']     = 'estimate';
$config->my->requirement->dtable->fieldList['estimate']['title']    = $lang->story->estimateAB;
$config->my->requirement->dtable->fieldList['estimate']['sortType'] = true;
$config->my->requirement->dtable->fieldList['estimate']['type']     = 'number';
$config->my->requirement->dtable->fieldList['estimate']['show']     = true;
$config->my->requirement->dtable->fieldList['estimate']['group']    = 5;
if($isEn) $config->my->requirement->dtable->fieldList['estimate']['width'] = '90';

$config->my->requirement->dtable->fieldList['reviewer']['name']     = 'reviewer';
$config->my->requirement->dtable->fieldList['reviewer']['title']    = $lang->story->reviewer;
$config->my->requirement->dtable->fieldList['reviewer']['type']     = 'text';
$config->my->requirement->dtable->fieldList['reviewer']['width']    = '100';
$config->my->requirement->dtable->fieldList['reviewer']['sortType'] = false;
$config->my->requirement->dtable->fieldList['reviewer']['show']     = true;
$config->my->requirement->dtable->fieldList['reviewer']['group']    = 5;
if($isEn) $config->my->requirement->dtable->fieldList['reviewer']['width'] = '120';

$config->my->requirement->dtable->fieldList['reviewedDate']['name']     = 'reviewedDate';
$config->my->requirement->dtable->fieldList['reviewedDate']['title']    = $lang->story->reviewedDate;
$config->my->requirement->dtable->fieldList['reviewedDate']['sortType'] = true;
$config->my->requirement->dtable->fieldList['reviewedDate']['type']     = 'date';
$config->my->requirement->dtable->fieldList['reviewedDate']['group']    = 5;

$config->my->requirement->dtable->fieldList['stage']['name']      = 'stage';
$config->my->requirement->dtable->fieldList['stage']['title']     = $lang->story->stageAB;
$config->my->requirement->dtable->fieldList['stage']['sortType']  = true;
$config->my->requirement->dtable->fieldList['stage']['type']      = 'status';
$config->my->requirement->dtable->fieldList['stage']['statusMap']  = $lang->story->stageList + $lang->requirement->stageList;
$config->my->requirement->dtable->fieldList['stage']['show']      = true;
$config->my->requirement->dtable->fieldList['stage']['group']     = 6;
if($isEn) $config->my->requirement->dtable->fieldList['stage']['width'] = '120';

$config->my->requirement->dtable->fieldList['assignedTo']['name']        = 'assignedTo';
$config->my->requirement->dtable->fieldList['assignedTo']['title']       = $lang->story->assignedTo;
$config->my->requirement->dtable->fieldList['assignedTo']['sortType']    = true;
$config->my->requirement->dtable->fieldList['assignedTo']['type']        = 'user';
$config->my->requirement->dtable->fieldList['assignedTo']['show']        = true;
$config->my->requirement->dtable->fieldList['assignedTo']['group']       = 6;
if($isEn) $config->my->requirement->dtable->fieldList['assignedTo']['width'] = '120';

$config->my->requirement->dtable->fieldList['assignedDate']['name']     = 'assignedDate';
$config->my->requirement->dtable->fieldList['assignedDate']['title']    = $lang->story->assignedDate;
$config->my->requirement->dtable->fieldList['assignedDate']['sortType'] = true;
$config->my->requirement->dtable->fieldList['assignedDate']['type']     = 'date';
$config->my->requirement->dtable->fieldList['assignedDate']['group']    = 6;

$config->my->requirement->dtable->fieldList['closedBy']['name']     = 'closedBy';
$config->my->requirement->dtable->fieldList['closedBy']['title']    = $lang->story->closedBy;
$config->my->requirement->dtable->fieldList['closedBy']['sortType'] = true;
$config->my->requirement->dtable->fieldList['closedBy']['type']     = 'user';
$config->my->requirement->dtable->fieldList['closedBy']['group']    = 8;

$config->my->requirement->dtable->fieldList['closedDate']['name']     = 'closedDate';
$config->my->requirement->dtable->fieldList['closedDate']['title']    = $lang->story->closedDate;
$config->my->requirement->dtable->fieldList['closedDate']['sortType'] = true;
$config->my->requirement->dtable->fieldList['closedDate']['type']     = 'date';
$config->my->requirement->dtable->fieldList['closedDate']['group']    = 8;

$config->my->requirement->dtable->fieldList['closedReason']['name']     = 'closedReason';
$config->my->requirement->dtable->fieldList['closedReason']['title']    = $lang->story->closedReason;
$config->my->requirement->dtable->fieldList['closedReason']['sortType'] = true;
$config->my->requirement->dtable->fieldList['closedReason']['width']    = '90';
$config->my->requirement->dtable->fieldList['closedReason']['type']     = 'category';
$config->my->requirement->dtable->fieldList['closedReason']['map']      = $lang->story->reasonList;
$config->my->requirement->dtable->fieldList['closedReason']['group']    = 8;

$config->my->requirement->dtable->fieldList['lastEditedBy']['name']     = 'lastEditedBy';
$config->my->requirement->dtable->fieldList['lastEditedBy']['title']    = $lang->story->lastEditedBy;
$config->my->requirement->dtable->fieldList['lastEditedBy']['sortType'] = true;
$config->my->requirement->dtable->fieldList['lastEditedBy']['type']     = 'user';
$config->my->requirement->dtable->fieldList['lastEditedBy']['group']    = 9;

$config->my->requirement->dtable->fieldList['lastEditedDate']['name']     = 'lastEditedDate';
$config->my->requirement->dtable->fieldList['lastEditedDate']['title']    = $lang->story->lastEditedDate;
$config->my->requirement->dtable->fieldList['lastEditedDate']['sortType'] = true;
$config->my->requirement->dtable->fieldList['lastEditedDate']['type']     = 'date';
$config->my->requirement->dtable->fieldList['lastEditedDate']['width']    = '120';
$config->my->requirement->dtable->fieldList['lastEditedDate']['group']    = 9;

$config->my->requirement->dtable->fieldList['keywords']['name']     = 'keywords';
$config->my->requirement->dtable->fieldList['keywords']['title']    = $lang->story->keywords;
$config->my->requirement->dtable->fieldList['keywords']['sortType'] = true;
$config->my->requirement->dtable->fieldList['keywords']['width']    = '100';
$config->my->requirement->dtable->fieldList['keywords']['group']    = 10;

$config->my->requirement->dtable->fieldList['source']['name']     = 'source';
$config->my->requirement->dtable->fieldList['source']['title']    = $lang->story->source;
$config->my->requirement->dtable->fieldList['source']['sortType'] = true;
$config->my->requirement->dtable->fieldList['source']['width']    = '90';
$config->my->requirement->dtable->fieldList['source']['type']     = 'category';
$config->my->requirement->dtable->fieldList['source']['map']      = $lang->story->sourceList;
$config->my->requirement->dtable->fieldList['source']['group']    = 10;

$config->my->requirement->dtable->fieldList['sourceNote']['name']     = 'sourceNote';
$config->my->requirement->dtable->fieldList['sourceNote']['title']    = $lang->story->sourceNote;
$config->my->requirement->dtable->fieldList['sourceNote']['width']    = '90';
$config->my->requirement->dtable->fieldList['sourceNote']['sortType'] = true;
$config->my->requirement->dtable->fieldList['sourceNote']['group']    = 10;

$config->my->requirement->dtable->fieldList['feedbackBy']['name']     = 'feedbackBy';
$config->my->requirement->dtable->fieldList['feedbackBy']['title']    = $lang->story->feedbackBy;
$config->my->requirement->dtable->fieldList['feedbackBy']['sortType'] = true;
$config->my->requirement->dtable->fieldList['feedbackBy']['width']    = '90';
$config->my->requirement->dtable->fieldList['feedbackBy']['group']    = 10;

$config->my->requirement->dtable->fieldList['activatedDate']['name']     = 'activatedDate';
$config->my->requirement->dtable->fieldList['activatedDate']['title']    = $lang->story->activatedDate;
$config->my->requirement->dtable->fieldList['activatedDate']['sortType'] = true;
$config->my->requirement->dtable->fieldList['activatedDate']['type']     = 'date';
$config->my->requirement->dtable->fieldList['activatedDate']['group']    = 11;

$config->my->requirement->dtable->fieldList['notifyEmail']['name']     = 'notifyEmail';
$config->my->requirement->dtable->fieldList['notifyEmail']['title']    = $lang->story->notifyEmail;
$config->my->requirement->dtable->fieldList['notifyEmail']['width']    = '100';
$config->my->requirement->dtable->fieldList['notifyEmail']['sortType'] = true;
$config->my->requirement->dtable->fieldList['notifyEmail']['group']    = 11;

$config->my->requirement->dtable->fieldList['mailto']['name']     = 'mailto';
$config->my->requirement->dtable->fieldList['mailto']['title']    = $lang->story->mailto;
$config->my->requirement->dtable->fieldList['mailto']['width']    = '100';
$config->my->requirement->dtable->fieldList['mailto']['sortType'] = true;
$config->my->requirement->dtable->fieldList['mailto']['group']    = 11;

$config->my->requirement->dtable->fieldList['version']['name']     = 'version';
$config->my->requirement->dtable->fieldList['version']['title']    = $lang->story->version;
$config->my->requirement->dtable->fieldList['version']['type']     = 'number';
$config->my->requirement->dtable->fieldList['version']['sortType'] = true;
$config->my->requirement->dtable->fieldList['version']['group']    = 11;

$config->my->requirement->dtable->fieldList['actions']['name']     = 'actions';
$config->my->requirement->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->requirement->dtable->fieldList['actions']['fixed']    = 'right';
$config->my->requirement->dtable->fieldList['actions']['required'] = true;
$config->my->requirement->dtable->fieldList['actions']['type']     = 'actions';
$config->my->requirement->dtable->fieldList['actions']['width']    = 140;
$config->my->requirement->dtable->fieldList['actions']['sortType'] = false;
$config->my->requirement->dtable->fieldList['actions']['list']     = $config->my->requirement->actionList;
$config->my->requirement->dtable->fieldList['actions']['menu']     = array('change', 'review|submitReview', 'recall', 'edit', 'close');
