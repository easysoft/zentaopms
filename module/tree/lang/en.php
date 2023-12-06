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
$lang->tree->common               = 'Module';
$lang->tree->edit                 = 'Edit Module';
$lang->tree->delete               = 'Delete Module';
$lang->tree->browse               = 'Manage General Module';
$lang->tree->browseTask           = 'Manage Task Module';
$lang->tree->manage               = 'Manage Module';
$lang->tree->fix                  = 'Fix Module';
$lang->tree->manageProduct        = "Manage {$lang->productCommon} Modules";
$lang->tree->manageProject        = "Manage {$lang->projectCommon} Modules";
$lang->tree->manageExecution      = "Manage {$lang->execution->common} Modules";
$lang->tree->manageLine           = "Manage Product Line";
$lang->tree->manageBug            = 'Manage Bug';
$lang->tree->manageCase           = 'Manage Case';
$lang->tree->manageCaseLib        = 'Manage Library';
$lang->tree->manageCustomDoc      = 'Manage Document Library';
$lang->tree->manageApiChild       = 'Manage API Directory';
$lang->tree->updateOrder          = 'Rank Module';
$lang->tree->manageChild          = 'Manage Child Modules';
$lang->tree->manageStoryChild     = 'Manage Child Modules';
$lang->tree->manageLineChild      = "Manage Product Line";
$lang->tree->manageBugChild       = 'Manage Child Modules of Bugs';
$lang->tree->manageCaseChild      = 'Manage Child Cases';
$lang->tree->manageCaselibChild   = 'Manage Child Libraries';
$lang->tree->manageDashboard      = 'Manage Dashboard Module';
$lang->tree->manageDashboardChild = 'Manage Dashboard Child Module';
$lang->tree->manageProjectChild   = "Manage Child {$lang->projectCommon} Modules";
$lang->tree->manageTaskChild      = "Manage Child {$lang->execution->common} Modules";
$lang->tree->syncFromProduct      = "Copy from Other {$lang->productCommon}s";
$lang->tree->dragAndSort          = "Drag to order";
$lang->tree->sort                 = "Order";
$lang->tree->addChild             = "Add Child Module";
$lang->tree->confirmDelete        = 'Do you want to delete this module and its child modules?';
$lang->tree->confirmDeleteMenu    = 'Do you want to delete this menu and its child menus?';
$lang->tree->confirmDelCategory   = 'Do you want to delete this category and its child categories?';
$lang->tree->confirmDeleteLine    = "Do you want to delete this product line?";
$lang->tree->confirmDeleteGroup   = 'Do you want to delete this group and its child groups?';
$lang->tree->confirmRoot          = "Any changes to the {$lang->productCommon} will change the stories, bugs, cases of {$lang->productCommon} it belongs to, as well as the linkage of {$lang->executionCommon} and {$lang->productCommon}, which is dangerous. Do you want to change it?";
$lang->tree->confirmRoot4Doc      = "Any changes to the library will change the document of library it belongs to, which is dangerous. Do you want to change it?";
$lang->tree->noSubmodule          = "There are no copyable submodules under the current module!";
$lang->tree->successSave          = 'Saved.';
$lang->tree->successFixed         = 'Fixed.';
$lang->tree->repeatName           = 'The name "%s" exists!';
$lang->tree->repeatDirName        = 'The name "%s" exists!';
$lang->tree->shouldNotBlank       = 'Module name should not be blank!';
$lang->tree->syncProductModule    = 'Sync Module';
$lang->tree->host                 = 'Host';
$lang->tree->editHost             = 'Edit host group';
$lang->tree->deleteHost           = 'Delete host group';
$lang->tree->manageHostChild      = 'Manage child host';
$lang->tree->groupMaintenance     = 'Manage host group';
$lang->tree->groupName            = 'Group Name';
$lang->tree->parentGroup          = 'Parent grpup';
$lang->tree->childGroup           = 'Child';
$lang->tree->confirmDeleteHost    = 'Do you want to delete this host and its child hosts?';

$lang->tree->module       = 'Module';
$lang->tree->name         = 'Module Name';
$lang->tree->wordName     = 'Name';
$lang->tree->line         = "Product Line";
$lang->tree->cate         = 'Category';
$lang->tree->dir          = 'Directory';
$lang->tree->root         = 'Root';
$lang->tree->branch       = 'Platform/Branch';
$lang->tree->path         = 'Path';
$lang->tree->type         = 'Type';
$lang->tree->parent       = 'Parent Module';
$lang->tree->parentCate   = 'Parent Directory';
$lang->tree->child        = 'Children';
$lang->tree->parentGroup  = 'Parent group';
$lang->tree->childGroup   = 'Children';
$lang->tree->subCategory  = 'SubCategory';
$lang->tree->editCategory = 'Edit Category';
$lang->tree->delCategory  = 'Delete Category';
$lang->tree->lineChild    = "Child Product Line";
$lang->tree->owner        = 'Owner';
$lang->tree->order        = 'Order';
$lang->tree->short        = 'Abbr.';
$lang->tree->all          = 'All Modules';
$lang->tree->executionDoc = "{$lang->executionCommon} Document";
$lang->tree->product      = $lang->productCommon;
$lang->tree->editDir      = "Edit Directory";

$lang->tree->emptyHistory = "No History";

$lang->module = new stdclass();
$lang->module->action = new stdclass();
$lang->module->action->created = array('main' => "\$date, created <strong>\$extra</strong> by <strong>\$actor</strong>.");
$lang->module->action->moved   = array('main' => "\$date, moved <strong>\$extra</strong> by <strong>\$actor</strong>.");
$lang->module->action->deleted = array('main' => "\$date, deleted <strong>\$extra</strong> by <strong>\$actor</strong>.");
