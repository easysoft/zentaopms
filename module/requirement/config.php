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
