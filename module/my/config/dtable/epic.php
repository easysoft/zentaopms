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

$config->my->epic->actionList['close']['icon']        = 'off';
$config->my->epic->actionList['close']['text']        = $lang->story->close;
$config->my->epic->actionList['close']['hint']        = $lang->story->close;
$config->my->epic->actionList['close']['url']         = array('module' => 'epic', 'method' => 'close', 'params' => 'storyID={id}&from=&storyType=epic');
$config->my->epic->actionList['close']['data-toggle'] = 'modal';

$config->my->epic->dtable = new stdclass();
$config->my->epic->dtable->fieldList['id']['name']     = 'id';
$config->my->epic->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->epic->dtable->fieldList['id']['type']     = 'id';
$config->my->epic->dtable->fieldList['id']['sortType'] = true;

$config->my->epic->dtable->fieldList['title']['name']         = 'title';
$config->my->epic->dtable->fieldList['title']['title']        = $lang->ERCommon . $space . $lang->my->name;
$config->my->epic->dtable->fieldList['title']['type']         = 'title';
$config->my->epic->dtable->fieldList['title']['link']         = array('module' => 'epic', 'method' => 'view', 'params' => 'id={id}&version=0&param=0&storyType=epic');
$config->my->epic->dtable->fieldList['title']['fixed']        = 'left';
$config->my->epic->dtable->fieldList['title']['sortType']     = true;
$config->my->epic->dtable->fieldList['title']['nestedToggle'] = true;

$config->my->epic->dtable->fieldList['pri']['name']     = 'pri';
$config->my->epic->dtable->fieldList['pri']['title']    = $lang->priAB;
$config->my->epic->dtable->fieldList['pri']['type']     = 'pri';
$config->my->epic->dtable->fieldList['pri']['group']    = 'pri';
$config->my->epic->dtable->fieldList['pri']['sortType'] = true;

$config->my->epic->dtable->fieldList['product']['name']     = 'productTitle';
$config->my->epic->dtable->fieldList['product']['title']    = $lang->story->product;
$config->my->epic->dtable->fieldList['product']['type']     = 'text';
$config->my->epic->dtable->fieldList['product']['group']    = 'pri';
$config->my->epic->dtable->fieldList['product']['sortType'] = true;

$config->my->epic->dtable->fieldList['status']['name']      = 'status';
$config->my->epic->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->my->epic->dtable->fieldList['status']['type']      = 'status';
$config->my->epic->dtable->fieldList['status']['statusMap'] = $lang->story->statusList;
$config->my->epic->dtable->fieldList['status']['group']     = 'pri';
$config->my->epic->dtable->fieldList['status']['sortType']  = true;

$config->my->epic->dtable->fieldList['openedBy']['name']     = 'openedBy';
$config->my->epic->dtable->fieldList['openedBy']['title']    = $lang->story->openedByAB;
$config->my->epic->dtable->fieldList['openedBy']['type']     = 'user';
$config->my->epic->dtable->fieldList['openedBy']['group']    = 'openedBy';
$config->my->epic->dtable->fieldList['openedBy']['sortType'] = true;

$config->my->epic->dtable->fieldList['estimate']['name']     = 'estimate';
$config->my->epic->dtable->fieldList['estimate']['title']    = $lang->story->estimateAB;
$config->my->epic->dtable->fieldList['estimate']['type']     = 'count';
$config->my->epic->dtable->fieldList['estimate']['group']    = 'openedBy';
$config->my->epic->dtable->fieldList['estimate']['sortType'] = true;

$config->my->epic->dtable->fieldList['actions']['name']     = 'actions';
$config->my->epic->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->epic->dtable->fieldList['actions']['type']     = 'actions';
$config->my->epic->dtable->fieldList['actions']['sortType'] = false;
$config->my->epic->dtable->fieldList['actions']['list']     = $config->my->epic->actionList;
$config->my->epic->dtable->fieldList['actions']['menu']     = array('change', 'review|submitReview', 'recall', 'edit', 'close');
