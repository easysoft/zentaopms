<?php
$config->requirement = new stdclass();
$config->requirement->create = new stdclass();
$config->requirement->edit   = new stdclass();
$config->requirement->change = new stdclass();
$config->requirement->close  = new stdclass();
$config->requirement->review = new stdclass();

$config->requirement->create->requiredFields = 'title';
$config->requirement->edit->requiredFields   = 'title';
$config->requirement->change->requiredFields = 'title';
$config->requirement->close->requiredFields  = 'closedReason';
$config->requirement->review->requiredFields = '';

$config->requirement->needReview = 1;

$config->requirement->list = new stdclass();
$config->requirement->list->customCreateFields      = '';
$config->requirement->list->customBatchCreateFields = 'plan,assignedTo,spec,source,verify,pri,estimate,keywords,mailto';
$config->requirement->list->customBatchEditFields   = 'branch,plan,estimate,pri,assignedTo,source,stage,closedBy,closedReason,keywords';

$config->requirement->custom = new stdclass();
$config->requirement->custom->createFields      = $config->requirement->list->customCreateFields;
$config->requirement->custom->batchCreateFields = 'module,plan,spec,pri,estimate,review,%s';
$config->requirement->custom->batchEditFields   = 'branch,module,plan,estimate,pri,source,stage,closedBy,closedReason';
