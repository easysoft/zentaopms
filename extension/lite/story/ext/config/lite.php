<?php
$config->story->exportFields = '
    id, module, title, spec, keywords,
    pri, estimate, status,
    openedBy, openedDate, assignedTo, assignedDate, mailto,
    reviewedBy, reviewedDate,
    closedBy, closedDate, closedReason,
    lastEditedBy, lastEditedDate,
    childStories, linkStories, duplicateStory, files';

$config->story->dtable->defaultField = array('id', 'pri', 'title', 'openedBy', 'assignedTo', 'estimate', 'status', 'actions');

unset($config->story->dtable->fieldList['plan']);
unset($config->story->dtable->fieldList['source']);
unset($config->story->dtable->fieldList['sourceNote']);
unset($config->story->dtable->fieldList['stage']);
unset($config->story->dtable->fieldList['category']);
unset($config->story->dtable->fieldList['feedbackBy']);
unset($config->story->dtable->fieldList['notifyEmail']);
unset($config->story->dtable->fieldList['bugCount']);
unset($config->story->dtable->fieldList['caseCount']);
