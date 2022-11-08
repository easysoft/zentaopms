<?php
/* Field. */
$lang->projectstory->project = "項目ID";
$lang->projectstory->product = "產品ID";
$lang->projectstory->story   = "需求ID";
$lang->projectstory->version = "版本";
$lang->projectstory->order   = "排序";

$lang->projectstory->storyCommon = '项目需求';
$lang->projectstory->storyList   = '项目需求列表';
$lang->projectstory->storyView   = '项目需求详情';

$lang->projectstory->common            = "項目{$lang->SRCommon}";
$lang->projectstory->index             = "{$lang->SRCommon}主頁";
$lang->projectstory->view              = "{$lang->SRCommon}詳情";
$lang->projectstory->story             = "{$lang->SRCommon}列表";
$lang->projectstory->track             = '矩陣';
$lang->projectstory->linkStory         = '關聯' . $lang->SRCommon;
$lang->projectstory->unlinkStory       = '移除' . $lang->SRCommon;
$lang->projectstory->batchUnlinkStory  = '批量移除' . $lang->SRCommon;
$lang->projectstory->importplanstories = '按計劃關聯' . $lang->SRCommon;
$lang->projectstory->trackAction       = '跟蹤矩陣';
$lang->projectstory->confirm           = '確定';

/* Notice. */
$lang->projectstory->whyNoStories   = "看起來沒有{$lang->SRCommon}可以關聯。請檢查下項目關聯的{$lang->productCommon}中有沒有{$lang->SRCommon}，而且要確保它們已經審核通過。";
$lang->projectstory->batchUnlinkTip = '其他需求已經移除，如下需求已與該項目下執行相關聯，請從執行中移除後再操作。';

global $app;
$app->loadLang('product');
$lang->projectstory->featureBar['story']['allstory']          = $lang->product->allStory;
$lang->projectstory->featureBar['story']['unclosed']          = $lang->product->unclosed;
$lang->projectstory->featureBar['story']['draft']             = $lang->product->draftStory;
$lang->projectstory->featureBar['story']['reviewing']         = $lang->product->reviewingStory;
$lang->projectstory->featureBar['story']['changing']          = $lang->product->changingStory;
$lang->projectstory->featureBar['story']['closed']            = $lang->product->closedStory;
$lang->projectstory->featureBar['story']['linkedExecution']   = '已關聯' . $lang->execution->common;
$lang->projectstory->featureBar['story']['unlinkedExecution'] = '未關聯' . $lang->execution->common;
