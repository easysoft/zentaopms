<?php
$lang->projectstory->common      = '項目' . $lang->SRCommon;
$lang->projectstory->index       = $lang->SRCommon . '主頁';
$lang->projectstory->view        = $lang->SRCommon . '詳情';
$lang->projectstory->track       = '矩陣';
$lang->projectstory->linkStory   = '關聯' . $lang->SRCommon;
$lang->projectstory->unlinkStory = '移除' . $lang->SRCommon;

global $app;
$app->loadLang('product');
$lang->projectstory->featureBar['story']['allstory'] = $lang->product->allStory;
$lang->projectstory->featureBar['story']['unclosed'] = $lang->product->unclosed;
$lang->projectstory->featureBar['story']['changed']  = $lang->product->changedStory;
$lang->projectstory->featureBar['story']['closed']   = $lang->product->closedStory;
