<?php
$lang->projectstory->common            = "項目{$lang->SRCommon}";
$lang->projectstory->index             = "{$lang->SRCommon}主頁";
$lang->projectstory->view              = "{$lang->SRCommon}詳情";
$lang->projectstory->story             = "{$lang->SRCommon}列表";
$lang->projectstory->track             = '矩陣';
$lang->projectstory->linkStory         = '關聯' . $lang->SRCommon;
$lang->projectstory->unlinkStory       = '移除' . $lang->SRCommon;
$lang->projectstory->importplanstories = '按計劃關聯' . $lang->SRCommon;
$lang->projectstory->whyNoStories      = "看起來沒有{$lang->SRCommon}可以關聯。請檢查下項目關聯的{$lang->productCommon}中有沒有{$lang->SRCommon}，而且要確保它們已經審核通過。";

$lang->projectstory->trackAction = '跟蹤矩陣';

global $app;
$app->loadLang('product');
$lang->projectstory->featureBar['story']['allstory'] = $lang->product->allStory;
$lang->projectstory->featureBar['story']['unclosed'] = $lang->product->unclosed;
$lang->projectstory->featureBar['story']['changed']  = $lang->product->changedStory;
$lang->projectstory->featureBar['story']['closed']   = $lang->product->closedStory;
