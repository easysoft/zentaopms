<?php
/* Field. */
$lang->projectstory->project = "Project ID";
$lang->projectstory->product = "Product ID";
$lang->projectstory->story   = "Story ID";
$lang->projectstory->version = "Version";
$lang->projectstory->order   = "Order";

$lang->projectstory->storyCommon = 'Project Story';
$lang->projectstory->storyList   = 'Project Story List';
$lang->projectstory->storyView   = 'Project Story Detail';

$lang->projectstory->common            = "Project {$lang->SRCommon}";
$lang->projectstory->index             = "{$lang->SRCommon} Home";
$lang->projectstory->view              = "{$lang->SRCommon} Detail";
$lang->projectstory->story             = "{$lang->SRCommon} List";
$lang->projectstory->track             = 'Tracking Matrix';
$lang->projectstory->linkStory         = 'Linked' . $lang->SRCommon;
$lang->projectstory->unlinkStory       = 'Unlinked' . $lang->SRCommon;
$lang->projectstory->batchUnlinkStory  = 'Batch Unlink ' . $lang->SRCommon;
$lang->projectstory->importplanstories = 'Linked by plan' . $lang->SRCommon;
$lang->projectstory->trackAction       = 'Matrix';
$lang->projectstory->confirm           = 'Confirm';

/* Notice. */
$lang->projectstory->whyNoStories   = "No story can be linked. Please check whether there is any story in project which is linked to {$lang->productCommon} and make sure it has been reviewed.";
$lang->projectstory->batchUnlinkTip = 'Other requirements are removed. The following requirements are linked to the execution of this project. Please remove them from the execution first.';

global $app;
$app->loadLang('product');
$lang->projectstory->featureBar['story']['allstory']          = $lang->product->allStory;
$lang->projectstory->featureBar['story']['unclosed']          = $lang->product->unclosed;
$lang->projectstory->featureBar['story']['draft']             = $lang->product->draftStory;
$lang->projectstory->featureBar['story']['reviewing']         = $lang->product->reviewingStory;
$lang->projectstory->featureBar['story']['changing']          = $lang->product->changingStory;
$lang->projectstory->featureBar['story']['closed']            = $lang->product->closedStory;
$lang->projectstory->featureBar['story']['linkedExecution']   = 'Linked ' . $lang->execution->common;
$lang->projectstory->featureBar['story']['unlinkedExecution'] = 'Unlinked ' . $lang->execution->common;
