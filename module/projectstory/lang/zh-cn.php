<?php
/* Field. */
$lang->projectstory->project = "项目ID";
$lang->projectstory->product = "产品ID";
$lang->projectstory->story   = "需求ID";
$lang->projectstory->version = "版本";
$lang->projectstory->order   = "排序";

$lang->projectstory->common            = "项目{$lang->SRCommon}";
$lang->projectstory->index             = "{$lang->SRCommon}主页";
$lang->projectstory->view              = "{$lang->SRCommon}详情";
$lang->projectstory->story             = "{$lang->SRCommon}列表";
$lang->projectstory->track             = '矩阵';
$lang->projectstory->linkStory         = '关联' . $lang->SRCommon;
$lang->projectstory->unlinkStory       = '移除' . $lang->SRCommon;
$lang->projectstory->batchUnlinkStory  = '批量移除' . $lang->SRCommon;
$lang->projectstory->importplanstories = '按计划关联' . $lang->SRCommon;
$lang->projectstory->trackAction       = '跟踪矩阵';
$lang->projectstory->confirm           = '确定';

/* Notice. */
$lang->projectstory->whyNoStories   = "看起来没有{$lang->SRCommon}可以关联。请检查下项目关联的{$lang->productCommon}中有没有{$lang->SRCommon}，而且要确保它们已经审核通过。";
$lang->projectstory->batchUnlinkTip = '其他需求已经移除，如下需求已与该项目下执行相关联，请从执行中移除后再操作。';

global $app;
$app->loadLang('product');
$lang->projectstory->featureBar['story']['allstory']          = '全部';
$lang->projectstory->featureBar['story']['unclosed']          = $lang->product->unclosed;
$lang->projectstory->featureBar['story']['draft']             = $lang->product->draftStory;
$lang->projectstory->featureBar['story']['reviewing']         = $lang->product->reviewingStory;
$lang->projectstory->featureBar['story']['changing']          = $lang->product->changingStory;
$lang->projectstory->featureBar['story']['closed']            = $lang->product->closedStory;
$lang->projectstory->featureBar['story']['linkedExecution']   = '已关联' . $lang->execution->common;
$lang->projectstory->featureBar['story']['unlinkedExecution'] = '未关联' . $lang->execution->common;
