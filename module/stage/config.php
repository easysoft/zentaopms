<?php
global $lang;
$config->stage->create      = new stdclass();
$config->stage->batchcreate = new stdclass();
$config->stage->edit        = new stdclass();
if(isset($config->setPercent) && $config->setPercent == 1)
{
    $config->stage->create->requiredFields      = 'name,percent,type';
    $config->stage->batchcreate->requiredFields = 'name,percent,type';
    $config->stage->edit->requiredFields        = 'name,percent,type';
}
else
{
    $config->stage->create->requiredFields      = 'name,type';
    $config->stage->batchcreate->requiredFields = 'name,type';
    $config->stage->edit->requiredFields        = 'name,type';
}

$config->stage->actionList['edit']['icon']        = 'edit';
$config->stage->actionList['edit']['hint']        = $lang->stage->edit;
$config->stage->actionList['edit']['url']         = helper::createLink('stage', 'edit', 'stageID={id}', '', true);
$config->stage->actionList['edit']['data-toggle'] = 'modal';

$config->stage->actionList['delete']['icon']         = 'trash';
$config->stage->actionList['delete']['hint']         = $lang->stage->delete;
$config->stage->actionList['delete']['data-confirm'] = array('message' => $lang->stage->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->stage->actionList['delete']['url']          = helper::createLink('stage', 'delete', 'stageID={id}');
$config->stage->actionList['delete']['ajaxSubmit']   = true;

$config->stage->ipdReviewPoint = new stdclass();
$config->stage->ipdReviewPoint->concept   = array('TR1', 'CDCP');
$config->stage->ipdReviewPoint->plan      = array('TR2', 'TR3', 'PDCP');
$config->stage->ipdReviewPoint->develop   = array('TR4', 'TR4A', 'TR5');
$config->stage->ipdReviewPoint->qualify   = array('TR6', 'ADCP');
$config->stage->ipdReviewPoint->launch    = array();
$config->stage->ipdReviewPoint->lifecycle = array('LDCP');
