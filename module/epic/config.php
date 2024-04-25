<?php
$config->epic = new stdclass();
$config->epic->create = new stdclass();
$config->epic->edit   = new stdclass();
$config->epic->change = new stdclass();
$config->epic->close  = new stdclass();
$config->epic->review = new stdclass();

$config->epic->create->requiredFields = 'title';
$config->epic->edit->requiredFields   = 'title';
$config->epic->change->requiredFields = 'title';
$config->epic->close->requiredFields  = 'closedReason';
$config->epic->review->requiredFields = '';

$config->epic->needReview = 1;

$config->epic->list = new stdclass();
$config->epic->list->customCreateFields      = '';
$config->epic->list->customBatchCreateFields = 'plan,assignedTo,spec,source,verify,pri,estimate,keywords,mailto';
$config->epic->list->customBatchEditFields   = 'branch,plan,estimate,pri,assignedTo,source,stage,closedBy,closedReason,keywords';

$config->epic->custom = new stdclass();
$config->epic->custom->createFields      = $config->epic->list->customCreateFields;
$config->epic->custom->batchCreateFields = 'module,plan,spec,pri,estimate,review,%s';
$config->epic->custom->batchEditFields   = 'branch,module,plan,estimate,pri,source,stage,closedBy,closedReason';
