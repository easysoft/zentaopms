<?php

$lang->tree->all             = 'All';
$lang->tree->allMenu         = $lang->tree->all;
$lang->tree->manageMenu      = 'Manage Category';
$lang->tree->manage          = 'Manage Category';
$lang->tree->common          = 'Manage Category';
$lang->tree->manageExecution = "Manage {$lang->executionCommon} Category";
$lang->tree->manageTaskChild = "Manage {$lang->executionCommon} Subcategory";
$lang->tree->name            = 'Category Name';

global $app;
if($app->rawModule == 'tree' and $app->rawMethod == 'browse')
{
    $lang->tree->edit             = 'Edit Category';
    $lang->tree->delete           = 'Delete Category';
    $lang->tree->child            = 'Subcategory';
    $lang->tree->manageStoryChild = 'Manage Subcategory';
    $lang->tree->name             = 'Category Name';
    $lang->tree->syncFromProduct  = 'Copy Category';
}
if($app->rawModule == 'story') $lang->tree->manage = $lang->tree->manageMenu;
