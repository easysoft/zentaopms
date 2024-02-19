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
