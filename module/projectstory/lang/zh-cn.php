<?php
/* Field. */
$lang->projectstory->project = "{$lang->projectCommon}ID";
$lang->projectstory->product = "{$lang->productCommon}ID";
$lang->projectstory->story   = "需求ID";
$lang->projectstory->version = "版本";
$lang->projectstory->order   = "排序";

$lang->projectstory->storyCommon = $lang->projectCommon . '需求';
$lang->projectstory->storyList   = $lang->projectCommon . '需求列表';
$lang->projectstory->storyView   = $lang->projectCommon . '需求详情';

$lang->projectstory->common            = "{$lang->projectCommon}{$lang->SRCommon}";
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
$lang->projectstory->whyNoStories   = "看起来没有{$lang->SRCommon}可以关联。请检查下{$lang->projectCommon}关联的{$lang->productCommon}中有没有{$lang->SRCommon}，而且要确保它们已经审核通过。";
$lang->projectstory->batchUnlinkTip = "其他需求已经移除，如下需求已与该{$lang->projectCommon}下执行相关联，请从执行中移除后再操作。";

$lang->projectstory->featureBar['story']['allstory']          = '全部';
$lang->projectstory->featureBar['story']['unclosed']          = '未关闭';
$lang->projectstory->featureBar['story']['draft']             = '草稿';
$lang->projectstory->featureBar['story']['reviewing']         = '评审中';
$lang->projectstory->featureBar['story']['changing']          = '变更中';
$lang->projectstory->featureBar['story']['closed']            = '已关闭';
$lang->projectstory->featureBar['story']['linkedExecution']   = '已关联' . $lang->execution->common;
$lang->projectstory->featureBar['story']['unlinkedExecution'] = '未关联' . $lang->execution->common;
