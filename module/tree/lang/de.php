<?php
/**
 * The tree module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: en.php 5045 2013-07-06 07:04:40Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->tree = new stdclass();
$lang->tree->common               = 'Modul';
$lang->tree->edit                 = 'Bearbeiten';
$lang->tree->delete               = 'Löschen';
$lang->tree->browse               = 'Allgemeine Module verwalten';
$lang->tree->browseTask           = 'Aufgaben Modul verwalten';
$lang->tree->manage               = 'Modul verwalten';
$lang->tree->fix                  = 'Fix';
$lang->tree->manageProduct        = "Verwalte {$lang->productCommon}";
$lang->tree->manageProject        = "Verwalte {$lang->projectCommon}";
$lang->tree->manageExecution      = "Verwalte {$lang->execution->common}";
$lang->tree->manageLine           = "Manage Product Line";
$lang->tree->manageBug            = 'Verwalte Bugs';
$lang->tree->manageCase           = 'Verwalte Fälle';
$lang->tree->manageCaseLib        = 'Verwalte Bibliothek';
$lang->tree->manageCustomDoc      = 'Verwalte Dokumente';
$lang->tree->manageApiChild       = 'Manage API Directory';
$lang->tree->updateOrder          = 'Sortierung';
$lang->tree->manageChild          = 'Verwalte Untermodul';
$lang->tree->manageStoryChild     = 'Verwalte Untermodul';
$lang->tree->manageLineChild      = 'Verwalte Produktlinie';
$lang->tree->manageBugChild       = 'Verwalte Bugs';
$lang->tree->manageCaseChild      = 'Verwalte Fälle';
$lang->tree->manageCaselibChild   = 'Verwalte Bibliothek';
$lang->tree->manageDashboard      = 'Manage Dashboard Module';
$lang->tree->manageDashboardChild = 'Manage Dashboard Child Module';
$lang->tree->manageProjectChild   = "Verwalte {$lang->projectCommon}";
$lang->tree->manageTaskChild      = "Verwalte {$lang->execution->common}";
$lang->tree->syncFromProduct      = "Copy from Other {$lang->productCommon}s";
$lang->tree->dragAndSort          = "Ziehen und Sotieren";
$lang->tree->sort                 = "Sortieren";
$lang->tree->addChild             = "Hinzufügen";
$lang->tree->confirmDelete        = 'Do you want to delete this module and its child modules?';
$lang->tree->confirmDeleteMenu    = 'Do you want to delete this menu and its child menus?';
$lang->tree->confirmDelCategory   = 'Möchten Sie diese Kategorie und ihre Kinderkategorien löschen?';
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

$lang->tree->module       = 'Modul';
$lang->tree->name         = 'Name';
$lang->tree->wordName     = 'Name';
$lang->tree->line         = "Product Line";
$lang->tree->cate         = 'Kategorie Name';
$lang->tree->dir          = 'Directory';
$lang->tree->root         = 'Root';
$lang->tree->branch       = 'Platform/Branch';
$lang->tree->path         = 'Pfad';
$lang->tree->type         = 'Typ';
$lang->tree->parent       = 'Übergeordnet';
$lang->tree->parentCate   = 'Parent Directory';
$lang->tree->child        = 'Untergeordnet';
$lang->tree->parentGroup  = 'Parent group';
$lang->tree->childGroup   = 'Children';
$lang->tree->subCategory  = 'SubCategory';
$lang->tree->editCategory = 'Kategorie bearbeiten';
$lang->tree->delCategory  = 'Kategorie löschen';
$lang->tree->lineChild    = "Child Product Line";
$lang->tree->owner        = 'Besitzer';
$lang->tree->order        = 'Reihenfolge';
$lang->tree->short        = 'Sortierung';
$lang->tree->all          = 'Alle Module';
$lang->tree->executionDoc = "{$lang->executionCommon} Dok";
$lang->tree->product      = $lang->productCommon;
$lang->tree->editDir      = "Edit Directory";

$lang->tree->emptyHistory = "No History";

$lang->module = new stdclass();
$lang->module->action = new stdclass();
$lang->module->action->created = array('main' => "\$date, created <strong>\$extra</strong> by <strong>\$actor</strong>.");
$lang->module->action->moved   = array('main' => "\$date, moved <strong>\$extra</strong> by <strong>\$actor</strong>.");
$lang->module->action->deleted = array('main' => "\$date, deleted <strong>\$extra</strong> by <strong>\$actor</strong>.");
