<?php
$lang->projectstory->common            = "项目{$lang->SRCommon}";
$lang->projectstory->index             = "{$lang->SRCommon}主页";
$lang->projectstory->view              = "{$lang->SRCommon}详情";
$lang->projectstory->story             = "{$lang->SRCommon}列表";
$lang->projectstory->track             = '矩阵';
$lang->projectstory->linkStory         = '关联' . $lang->SRCommon;
$lang->projectstory->unlinkStory       = '移除' . $lang->SRCommon;
$lang->projectstory->importplanstories = '按计划关联' . $lang->SRCommon;
$lang->projectstory->whyNoStories      = "看起来没有{$lang->SRCommon}可以关联。请检查下项目关联的{$lang->productCommon}中有没有{$lang->SRCommon}，而且要确保它们已经审核通过。";

$lang->projectstory->trackAction = '跟踪矩阵';

global $app;
$app->loadLang('product');
$lang->projectstory->featureBar['story']['allstory'] = $lang->product->allStory;
$lang->projectstory->featureBar['story']['unclosed'] = $lang->product->unclosed;
$lang->projectstory->featureBar['story']['changed']  = $lang->product->changedStory;
$lang->projectstory->featureBar['story']['closed']   = $lang->product->closedStory;
