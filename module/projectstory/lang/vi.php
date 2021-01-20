<?php
$lang->projectstory->common      = 'Yêu cầu dự án';
$lang->projectstory->index       = 'Yêu cầu trang nhà';
$lang->projectstory->story       = $lang->generalSR;
$lang->projectstory->track       = 'Theo dõi ma trận';
$lang->projectstory->linkStory   = 'Linked' . $lang->SRCommon;
$lang->projectstory->unlinkStory = 'Unlinked' . $lang->SRCommon;

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
