<?php
$config->my = new stdclass();
$config->my->editprofile = new stdclass();
$config->my->editprofile->requiredFields = 'account,realname';

$config->my->dynamicCounts = 14;
$config->my->todoCounts    = 10;
$config->my->taskCounts    = 10;
$config->my->bugCounts     = 10;
$config->my->storyCounts   = 10;

$config->my->oaObjectType = 'attend,leave,makeup,overtime,lieu';

$config->mobile = new stdclass();
$config->mobile->todoBar  = array('today', 'yesterday', 'thisWeek', 'lastWeek', 'all');
$config->mobile->taskBar  = array('assignedTo', 'openedBy');
$config->mobile->bugBar   = array('assignedTo', 'openedBy', 'resolvedBy');
$config->mobile->storyBar = array('assignedTo', 'openedBy', 'reviewedBy');

global $lang,$app;
$config->my->audit = new stdclass();
$config->my->audit->actionList = array();
$config->my->audit->actionList['review']['icon']        = 'glasses';
$config->my->audit->actionList['review']['text']        = $lang->review->common;
$config->my->audit->actionList['review']['hint']        = $lang->review->common;
$config->my->audit->actionList['review']['data-toggle'] = 'modal';
