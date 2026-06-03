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

$config->my->requirement->actionList['close']['icon']        = 'off';
$config->my->requirement->actionList['close']['text']        = $lang->story->close;
$config->my->requirement->actionList['close']['hint']        = $lang->story->close;
$config->my->requirement->actionList['close']['url']         = array('module' => 'requirement', 'method' => 'close', 'params' => 'storyID={id}&from=&storyType=requirement');
$config->my->requirement->actionList['close']['data-toggle'] = 'modal';

$config->my->requirement->dtable = new stdclass();
$config->my->requirement->dtable->fieldList['id']['name']     = 'id';
$config->my->requirement->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->requirement->dtable->fieldList['id']['type']     = 'id';
$config->my->requirement->dtable->fieldList['id']['sortType'] = true;

$config->my->requirement->dtable->fieldList['title']['name']         = 'title';
$config->my->requirement->dtable->fieldList['title']['title']        = common::checkNotCN() ? $lang->URCommon . ' ' . $lang->my->name : $lang->URCommon . $lang->my->name;
$config->my->requirement->dtable->fieldList['title']['type']         = 'title';
$config->my->requirement->dtable->fieldList['title']['link']         = array('module' => 'requirement', 'method' => 'view', 'params' => 'id={id}&version=0&param=0&storyType=requirement');
$config->my->requirement->dtable->fieldList['title']['fixed']        = 'left';
$config->my->requirement->dtable->fieldList['title']['sortType']     = true;
$config->my->requirement->dtable->fieldList['title']['nestedToggle'] = true;

$config->my->requirement->dtable->fieldList['pri']['name']     = 'pri';
$config->my->requirement->dtable->fieldList['pri']['title']    = $lang->priAB;
$config->my->requirement->dtable->fieldList['pri']['type']     = 'pri';
$config->my->requirement->dtable->fieldList['pri']['group']    = 'pri';
$config->my->requirement->dtable->fieldList['pri']['sortType'] = true;

$config->my->requirement->dtable->fieldList['product']['name']     = 'productTitle';
$config->my->requirement->dtable->fieldList['product']['title']    = $lang->story->product;
$config->my->requirement->dtable->fieldList['product']['type']     = 'text';
$config->my->requirement->dtable->fieldList['product']['group']    = 'pri';
$config->my->requirement->dtable->fieldList['product']['sortType'] = true;

$config->my->requirement->dtable->fieldList['status']['name']      = 'status';
$config->my->requirement->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->my->requirement->dtable->fieldList['status']['type']      = 'status';
$config->my->requirement->dtable->fieldList['status']['statusMap'] = $lang->story->statusList;
$config->my->requirement->dtable->fieldList['status']['group']     = 'pri';
$config->my->requirement->dtable->fieldList['status']['sortType']  = true;

$config->my->requirement->dtable->fieldList['openedBy']['name']     = 'openedBy';
$config->my->requirement->dtable->fieldList['openedBy']['title']    = $lang->story->openedByAB;
$config->my->requirement->dtable->fieldList['openedBy']['type']     = 'user';
$config->my->requirement->dtable->fieldList['openedBy']['group']    = 'openedBy';
$config->my->requirement->dtable->fieldList['openedBy']['sortType'] = true;

$config->my->requirement->dtable->fieldList['estimate']['name']     = 'estimate';
$config->my->requirement->dtable->fieldList['estimate']['title']    = $lang->story->estimateAB;
$config->my->requirement->dtable->fieldList['estimate']['type']     = 'count';
$config->my->requirement->dtable->fieldList['estimate']['group']    = 'openedBy';
$config->my->requirement->dtable->fieldList['estimate']['sortType'] = true;

$config->my->requirement->dtable->fieldList['actions']['name']     = 'actions';
$config->my->requirement->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->requirement->dtable->fieldList['actions']['type']     = 'actions';
$config->my->requirement->dtable->fieldList['actions']['sortType'] = false;
$config->my->requirement->dtable->fieldList['actions']['list']     = $config->my->requirement->actionList;
$config->my->requirement->dtable->fieldList['actions']['menu']     = array('change', 'review|submitReview', 'recall', 'edit', 'close');
