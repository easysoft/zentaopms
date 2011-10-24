<?php
$config->story->batchCreate = 10;
$config->story->create->requiredFields = 'title';
$config->story->edit->requiredFields   = 'title';
$config->story->change->requiredFields = 'title';
$config->story->close->requiredFields  = 'closedReason';
$config->story->review->requiredFields = 'assignedTo,reviewedBy,result';

$config->story->editor->create = array('id' => 'spec,verify', 'tools' => 'simpleTools');
$config->story->editor->change = array('id' => 'spec,verify', 'tools' => 'simpleTools');

$config->story->list->exportFields = '
    id, product, module, plan, source, title, spec, verify, keywords, 
    pri, estimate, status, stage,
    openedBy, openedDate, assignedTo, assignedDate, mailto, 
    reviewedBy, reviewedDate, 
    closedBy, closedDate, closedReason,
    lastEditedBy, lastEditedDate, 
    childStories, linkStories, duplicateStory, files';
