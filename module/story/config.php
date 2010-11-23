<?php
$config->story->create->requiredFields = 'estimate,title';
$config->story->edit->requiredFields   = 'estimate,title';
$config->story->change->requiredFields = 'title';
$config->story->close->requiredFields  = 'closedReason';
$config->story->review->requiredFields = 'assignedTo,reviewedBy,result';

$config->story->editor->create = array('id' => 'spec', 'tools' => 'simpleTools');
$config->story->editor->change = array('id' => 'spec', 'tools' => 'simpleTools');
