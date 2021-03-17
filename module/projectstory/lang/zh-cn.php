<?php
$lang->projectstory->common            = "项目{$lang->SRCommon}";
$lang->projectstory->index             = "{$lang->SRCommon}主页";
$lang->projectstory->view              = "{$lang->SRCommon}详情";
$lang->projectstory->story             = "{$lang->SRCommon}列表";
$lang->projectstory->track             = '矩阵';
$lang->projectstory->linkStory         = '关联' . $lang->SRCommon;
$lang->projectstory->unlinkStory       = '移除' . $lang->SRCommon;
$lang->projectstory->importplanstories = '按计划关联' . $lang->SRCommon;

global $app;
$app->loadLang('product');
$lang->projectstory->featureBar['story']['allstory'] = $lang->product->allStory;
$lang->projectstory->featureBar['story']['unclosed'] = $lang->product->unclosed;
$lang->projectstory->featureBar['story']['changed']  = $lang->product->changedStory;
$lang->projectstory->featureBar['story']['closed']   = $lang->product->closedStory;

