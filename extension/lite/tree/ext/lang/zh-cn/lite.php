<?php

$lang->tree->all             = '所有目录';
$lang->tree->allMenu         = $lang->tree->all;
$lang->tree->manageMenu      = '维护目录';
$lang->tree->manage          = '维护目录';

global $app;
if ($app->rawModule == 'tree' and $app->rawMethod == 'browsetask') 
{
    $lang->tree->common          = '目录维护';
    $lang->tree->manageExecution = "维护{$lang->executionCommon}视图目录";
    $lang->tree->manageTaskChild = "维护{$lang->executionCommon}子目录";
    $lang->tree->name            = '目录名称';
    $lang->tree->child           = '子目录';
    $lang->tree->edit            = '编辑目录';
    $lang->tree->delete          = '删除目录';
}

if($app->rawModule == 'tree' and $app->rawMethod == 'browse')
{
    $lang->tree->edit             = '编辑目录';
    $lang->tree->delete           = '删除目录';
    $lang->tree->child            = '子目录';
    $lang->tree->manageStoryChild = '维护子目录';
    $lang->tree->name             = '目录名称';
    $lang->tree->syncFromProduct  = '复制目录';
}
if($app->rawModule == 'story') $lang->tree->manage = $lang->tree->manageMenu;
