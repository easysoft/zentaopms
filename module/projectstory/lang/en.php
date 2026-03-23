<?php
/* Field. */
$lang->projectstory->project = "{$lang->projectCommon}ID";
$lang->projectstory->product = "{$lang->productCommon} ID";
$lang->projectstory->story   = "Story ID";
$lang->projectstory->version = "Version";
$lang->projectstory->order   = "Sort";

$lang->projectstory->storyCommon = $lang->projectCommon . ' Story';
$lang->projectstory->storyList   = $lang->projectCommon . ' Story List';
$lang->projectstory->storyView   = $lang->projectCommon . ' Story Details';

$lang->projectstory->common            = "{$lang->projectCommon} Story";
$lang->projectstory->index             = "Story Homepage";
$lang->projectstory->view              = "Story Details";
$lang->projectstory->story             = "Story List";
$lang->projectstory->track             = 'Traceability Matrix';
$lang->projectstory->linkStory         = 'Link Story';
$lang->projectstory->unlinkStory       = 'Unlink Story';
$lang->projectstory->report            = 'Story Report';
$lang->projectstory->export            = 'Export Stories';
$lang->projectstory->batchReview       = 'Batch Review Stories';
$lang->projectstory->batchClose        = 'Batch Close Stories';
$lang->projectstory->batchChangePlan   = 'Batch Change Plans';
$lang->projectstory->batchAssignTo     = 'Batch Assign Stories';
$lang->projectstory->batchEdit         = 'Batch Edit Stories';
$lang->projectstory->importToLib       = 'Import To Story Library';
$lang->projectstory->batchImportToLib  = 'Batch Import To Story Library';
$lang->projectstory->importCase        = 'Import Stories';
$lang->projectstory->exportTemplate    = 'Export Template';
$lang->projectstory->batchUnlinkStory  = 'Batch Unlink Stories';
$lang->projectstory->importplanstories = 'Link Stories by Plan';
$lang->projectstory->trackAction       = 'Traceability Matrix';
$lang->projectstory->confirm           = 'Confirm';

/* Notice. */
$lang->projectstory->whyNoStories   = "No story can be linked. Please check whether there is any story in {$lang->projectCommon} which is linked to {$lang->productCommon} and make sure it has been reviewed.";
$lang->projectstory->batchUnlinkTip = "All other stories have been removed. The following ones are linked to executions under this {$lang->projectCommon}. Remove them before proceeding.";

$lang->projectstory->featureBar['story']['allstory']  = 'All';
$lang->projectstory->featureBar['story']['unclosed']  = 'Open';
$lang->projectstory->featureBar['story']['draft']     = 'Draft';
$lang->projectstory->featureBar['story']['reviewing'] = 'Reviewing';
$lang->projectstory->featureBar['story']['changing']  = 'Changing';
$lang->projectstory->featureBar['story']['more']      = $lang->more;

$lang->projectstory->moreSelects['story']['more']['closed']            = 'Closed';
$lang->projectstory->moreSelects['story']['more']['linkedexecution']   = 'Linked'   . $lang->execution->common;
$lang->projectstory->moreSelects['story']['more']['unlinkedexecution'] = 'Unlinked' . $lang->execution->common;
