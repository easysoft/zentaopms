<?php
$config->my = new stdclass();
$config->my->editprofile = new stdclass();
$config->my->editprofile->requiredFields = 'account,realname';

$config->my->dynamicCounts = 14;
$config->my->todoCounts    = 10;
$config->my->taskCounts    = 10;
$config->my->bugCounts     = 10;
$config->my->storyCounts   = 10;

$config->my->oaObjectType       = 'attend,leave,makeup,overtime,lieu';
$config->my->noFlowAuditModules = array('story', 'requirement', 'epic', 'testcase', 'feedback', 'oa', 'demand');

$config->mobile = new stdclass();
$config->mobile->todoBar  = array('today', 'yesterday', 'thisWeek', 'lastWeek', 'all');
$config->mobile->taskBar  = array('assignedTo', 'openedBy');
$config->mobile->bugBar   = array('assignedTo', 'openedBy', 'resolvedBy');
$config->mobile->storyBar = array('assignedTo', 'openedBy', 'reviewedBy');

$config->my->notFlowModules = array();

$config->my->openedDateField['product']       = 'createdDate';
$config->my->openedDateField['productplan']   = 'createdDate';
$config->my->openedDateField['release']       = 'createdDate';
$config->my->openedDateField['build']         = 'createdDate';
$config->my->openedDateField['testtask']      = 'createdDate';
$config->my->openedDateField['testsuite']     = 'addedDate';
$config->my->openedDateField['caselib']       = 'addedDate';
$config->my->openedDateField['baseline']      = 'createdDate';
$config->my->openedDateField['projectchange'] = 'createdDate';
