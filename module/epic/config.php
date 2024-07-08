<?php
global $app;
$app->loadConfig('story');
$config->epic = clone $config->story;

$config->epic->needReview = 1;

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

$config->epic->custom = new stdclass();
$config->epic->custom->createFields      = '';
$config->epic->custom->batchCreateFields = 'module,plan,spec,pri,estimate,review,%s';
$config->epic->custom->batchEditFields   = 'branch,module,plan,estimate,pri,source,stage,closedBy,closedReason';
