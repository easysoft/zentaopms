<?php
$config->story->exportFields = '
    id, module, title, spec, keywords,
    pri, estimate, status,
    openedBy, openedDate, assignedTo, assignedDate, mailto,
    reviewedBy, reviewedDate,
    closedBy, closedDate, closedReason,
    lastEditedBy, lastEditedDate,
    childStories, linkStories, duplicateStory, files';

$config->story->datatable->defaultField = array('id', 'pri', 'title', 'openedBy', 'assignedTo', 'estimate', 'status', 'actions');

unset($config->story->datatable->fieldList['plan']);
unset($config->story->datatable->fieldList['source']);
unset($config->story->datatable->fieldList['sourceNote']);
unset($config->story->datatable->fieldList['stage']);
unset($config->story->datatable->fieldList['category']);
unset($config->story->datatable->fieldList['feedbackBy']);
unset($config->story->datatable->fieldList['notifyEmail']);
unset($config->story->datatable->fieldList['bugCount']);
unset($config->story->datatable->fieldList['caseCount']);
