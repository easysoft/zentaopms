<?php
/* Field. */
$lang->projectstory->project = "{$lang->projectCommon} ID";
$lang->projectstory->product = "{$lang->productCommon} ID";
$lang->projectstory->story   = "Story ID";
$lang->projectstory->version = "Version";
$lang->projectstory->order   = "Order";

$lang->projectstory->storyCommon = $lang->projectCommon . ' Story';
$lang->projectstory->storyList   = $lang->projectCommon . ' Story List';
$lang->projectstory->storyView   = $lang->projectCommon . ' Story Detail';

$lang->projectstory->common            = "{$lang->projectCommon} Requirement";
$lang->projectstory->index             = "Requirement Home";
$lang->projectstory->view              = "Requirement Detail";
$lang->projectstory->story             = "Requirement List";
$lang->projectstory->track             = 'Tracking Matrix';
$lang->projectstory->linkStory         = 'Linked Requirement';
$lang->projectstory->unlinkStory       = 'Unlinked Requirement';
$lang->projectstory->report            = 'Requirement Report';
$lang->projectstory->export            = 'Export Requirement';
$lang->projectstory->batchReview       = 'Batch Review Requirement';
$lang->projectstory->batchClose        = 'Batch Close Requirement';
$lang->projectstory->batchChangePlan   = 'Batch Change Plan';
$lang->projectstory->batchAssignTo     = 'Batch Assign Requirement';
$lang->projectstory->batchEdit         = 'Batch Edit Requirement';
$lang->projectstory->batchUnlinkStory  = 'Batch Unlink Requirement';
$lang->projectstory->importplanstories = 'Import requirements by plan';
$lang->projectstory->trackAction       = 'Matrix';
$lang->projectstory->confirm           = 'Confirm';

/* Notice. */
$lang->projectstory->whyNoStories   = "No story can be linked. Please check whether there is any story in {$lang->projectCommon} which is linked to {$lang->productCommon} and make sure it has been reviewed.";
$lang->projectstory->batchUnlinkTip = "Other requirements are removed. The following requirements are linked to the execution of this {$lang->projectCommon}. Please remove them from the execution first.";

$lang->projectstory->featureBar['story']['allstory']          = 'All';
$lang->projectstory->featureBar['story']['unclosed']          = 'Open';
$lang->projectstory->featureBar['story']['draft']             = 'Draft';
$lang->projectstory->featureBar['story']['reviewing']         = 'Reviewing';
$lang->projectstory->featureBar['story']['changing']          = 'Changing';
$lang->projectstory->featureBar['story']['closed']            = 'Closed';
$lang->projectstory->featureBar['story']['linkedexecution']   = 'Linked ' . $lang->execution->common;
$lang->projectstory->featureBar['story']['unlinkedexecution'] = 'Unlinked ' . $lang->execution->common;
