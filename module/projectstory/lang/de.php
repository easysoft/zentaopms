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

$lang->projectstory->featureBar['story']['allstory'] = $lang->product->allStory;
$lang->projectstory->featureBar['story']['unclosed'] = $lang->product->unclosed;
$lang->projectstory->featureBar['story']['changed']  = $lang->product->changedStory;
$lang->projectstory->featureBar['story']['closed']   = $lang->product->closedStory;

