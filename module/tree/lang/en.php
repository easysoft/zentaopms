<?php
/**
 * The tree module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: en.php 5045 2013-07-06 07:04:40Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->tree = new stdclass();
$lang->tree->common                 = 'Module Management';
$lang->tree->create                 = 'Create Module';
$lang->tree->edit                   = 'Edit Modules';
$lang->tree->delete                 = 'Delete Modules';
$lang->tree->browse                 = 'General Modules Management';
$lang->tree->browseTask             = 'Task Modules Management';
$lang->tree->manage                 = 'Manage Modules';
$lang->tree->fix                    = 'Fix Modules';
$lang->tree->manageProduct          = "Manage {$lang->productCommon} Modules";
$lang->tree->manageProject          = "Manage {$lang->projectCommon} Modules";
$lang->tree->manageExecution        = "Manage {$lang->execution->common} Modules";
$lang->tree->manageLine             = "Manage Product Lines";
$lang->tree->manageBug              = 'Manage Test Views';
$lang->tree->manageCase             = 'Manage Case Views';
$lang->tree->manageCaselib          = 'Manage Case Libraries';
$lang->tree->manageCustomDoc        = 'Manage Document Categories';
$lang->tree->manageApiChild         = 'Manage API Directory';
$lang->tree->manageGroup            = 'Manage groups';
$lang->tree->updateOrder            = 'Update Order';
$lang->tree->manageChild            = 'Manage Sub-modules';
$lang->tree->manageStoryChild       = 'Manage Sub-modules';
$lang->tree->manageLineChild        = "Manage Product Lines";
$lang->tree->manageBugChild         = 'Manage Bug Sub-modules';
$lang->tree->manageCaseChild        = 'Manage Case Sub-modules';
$lang->tree->manageCaselibChild     = 'Manage Case Libraries Sub-modules';
$lang->tree->manageDashboard        = 'Manage Dashboard Modules';
$lang->tree->manageDashboardChild   = 'Manage Dashboard Sub-modules';
$lang->tree->manageProjectChild     = "Manage {$lang->projectCommon} Sub-modules";
$lang->tree->manageTaskChild        = "Manage {$lang->execution->common} Sub-modules";
$lang->tree->manageDeliverableChild = "Manage Deliverable Category";
$lang->tree->syncFromProduct        = "Duplicate Modules";
$lang->tree->dragAndSort            = "Drag to order";
$lang->tree->sort                   = "Order";
$lang->tree->addChild               = "Add Sub-modules";
$lang->tree->confirmDelete          = 'This module and its sub-modules will be deleted. Are you sure you want to delete them?';
$lang->tree->confirmDeleteMenu      = 'This directory and its sub-directories will be deleted. Are you sure you want to delete them?';
$lang->tree->confirmDelCategory     = 'This category and its sub-categories will be deleted. Are you sure you want to delete them?';
$lang->tree->confirmDeleteLine      = "Are you sure you want to delete this product line?";
$lang->tree->confirmDeleteGroup     = 'This group and its sub-groups will be deleted. Are you sure you want to delete them?';
$lang->tree->confirmRoot            = "Any changes to the {$lang->productCommon} will change the {$lang->SRCommon}, stories, bugs, cases of {$lang->productCommon} it belongs to, as well as the linkage of {$lang->executionCommon} and {$lang->productCommon}. This is a high-risk action. Please proceed with caution. Are you sure you want to make this change?";
$lang->tree->confirmRoot4Doc        = "Any changes to the Doc Library will update the associations of all documents under this category. This is a high-risk action. Please proceed with caution. Are you sure you want to make this change?";
$lang->tree->noSubmodule            = "No sub-modules available to duplicate";
$lang->tree->successSave            = 'Saved ';
$lang->tree->successFixed           = 'Fixed';
$lang->tree->repeatName             = 'Module name "%s" already exists';
$lang->tree->repeatDirName          = 'Directory name "%s" already exists';
$lang->tree->shouldNotBlank         = 'Module name cannot be empty';
$lang->tree->syncProductModule      = "Sync {$lang->productCommon} Modules";
$lang->tree->host                   = 'Host';
$lang->tree->editHost               = 'Edit host group';
$lang->tree->deleteHost             = 'Delete host group';
$lang->tree->manageHostChild        = 'Manage Host Sub-groups';
$lang->tree->groupMaintenance       = 'Manage Host Groups';
$lang->tree->groupName              = 'Group Name';
$lang->tree->parentGroup            = 'Parent grpup';
$lang->tree->childGroup             = 'Child';
$lang->tree->confirmDeleteHost      = 'This group and its sub-groups will be deleted. Are you sure?';
$lang->tree->designModule           = 'Design';
$lang->tree->otherModule            = 'Other';

$lang->tree->module       = 'Module';
$lang->tree->name         = 'Module Name';
$lang->tree->wordName     = 'Name';
$lang->tree->line         = "Product Line Name";
$lang->tree->cate         = 'Category Name';
$lang->tree->dir          = 'Directory Name';
$lang->tree->root         = "{$lang->productCommon}";
$lang->tree->branch       = 'Platform/Branch';
$lang->tree->path         = 'Path';
$lang->tree->type         = 'Type';
$lang->tree->parent       = 'Parent Module';
$lang->tree->parentCate   = 'Parent Directory';
$lang->tree->child        = 'Sub-modules';
$lang->tree->parentGroup  = 'Parent group';
$lang->tree->childGroup   = 'Sub-groups';
$lang->tree->subCategory  = 'Sub-categories';
$lang->tree->editCategory = 'Edit Category';
$lang->tree->delCategory  = 'Delete Category';
$lang->tree->lineChild    = "Sub-product Lines";
$lang->tree->owner        = 'Assigned to';
$lang->tree->order        = 'Order';
$lang->tree->short        = 'Abbr';
$lang->tree->all          = 'All Modules';
$lang->tree->executionDoc = "{$lang->executionCommon} Document";
$lang->tree->product      = $lang->productCommon;
$lang->tree->editDir      = "Edit Directory";

$lang->tree->emptyHistory = "No history yet";

$lang->module = new stdclass();
$lang->module->action = new stdclass();
$lang->module->action->created = array('main' => "\$date, created <strong>\$extra</strong> by <strong>\$actor</strong>.");
$lang->module->action->moved   = array('main' => "\$date, moved <strong>\$extra</strong> by <strong>\$actor</strong>.");
$lang->module->action->deleted = array('main' => "\$date, deleted <strong>\$extra</strong> by <strong>\$actor</strong>.");
