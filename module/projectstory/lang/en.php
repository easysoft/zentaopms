<?php
$lang->projectstory->common            = "Project {$lang->SRCommon}";
$lang->projectstory->index             = "{$lang->SRCommon} Home";
$lang->projectstory->view              = "{$lang->SRCommon} Detail";
$lang->projectstory->story             = "{$lang->SRCommon} List";
$lang->projectstory->track             = 'Tracking Matrix';
$lang->projectstory->linkStory         = 'Linked' . $lang->SRCommon;
$lang->projectstory->unlinkStory       = 'Unlinked' . $lang->SRCommon;
$lang->projectstory->importplanstories = 'Linked by plan' . $lang->SRCommon;

global $app;
$app->loadLang('product');
$lang->projectstory->featureBarList['allstory']               = $lang->product->allStory;
$lang->projectstory->featureBarList['unclosed']               = $lang->product->unclosed;
$lang->projectstory->featureBarList['willclose']              = $lang->product->willClose;
$lang->projectstory->featureBarList['status']['draftstory']   = $lang->product->draftStory;
$lang->projectstory->featureBarList['status']['activestory']  = $lang->product->activeStory;
$lang->projectstory->featureBarList['status']['changedstory'] = $lang->product->changedStory;
$lang->projectstory->featureBarList['status']['closedstory']  = $lang->product->closedStory;
$lang->projectstory->featureBarList['other']['openedbyme']    = $lang->product->openedByMe;
$lang->projectstory->featureBarList['other']['assignedtome']  = $lang->product->assignedToMe;
$lang->projectstory->featureBarList['other']['reviewedbyme']  = $lang->product->reviewedByMe;
$lang->projectstory->featureBarList['other']['closedbyme']    = $lang->product->closedByMe;
