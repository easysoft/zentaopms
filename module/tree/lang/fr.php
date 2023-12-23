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
$lang->tree->edit                 = 'Editer Module';
$lang->tree->delete               = 'Supprimer Module';
$lang->tree->browse               = 'Gérer les Modules Généraux';
$lang->tree->browseTask           = 'Gérer les Modules Tâche';
$lang->tree->manage               = 'Gérer les Modules';
$lang->tree->fix                  = 'Corrigez Module';
$lang->tree->manageProduct        = "Gérer les Modules {$lang->productCommon}";
$lang->tree->manageProject        = "Gérer les Modules {$lang->projectCommon}";
$lang->tree->manageExecution      = "Gérer les Modules {$lang->execution->common}";
$lang->tree->manageLine           = 'Gérer Ligne Produit';
$lang->tree->manageBug            = 'Gérer Bugs';
$lang->tree->manageCase           = 'Gérer CasTests';
$lang->tree->manageCaselib        = 'Gérer Librairie de recette';
$lang->tree->manageCustomDoc      = 'Gérer la librairie de Doc';
$lang->tree->manageApiChild       = 'Manage API Directory';
$lang->tree->updateOrder          = 'Rang Module';
$lang->tree->manageChild          = 'Gérer Sous-Module';
$lang->tree->manageStoryChild     = 'Gérer Sous-Module';
$lang->tree->manageLineChild      = 'Gérer Ligne Produit';
$lang->tree->manageBugChild       = 'Gérer Sous-Bugs';
$lang->tree->manageCaseChild      = 'Gérer Sous-CasTests';
$lang->tree->manageCaselibChild   = 'Gérer Sous-Librairies';
$lang->tree->manageDashboard      = 'Manage Dashboard Module';
$lang->tree->manageDashboardChild = 'Manage Dashboard Child Module';
$lang->tree->manageProjectChild   = "Gérer Sous-Modules {$lang->projectCommon}";
$lang->tree->manageTaskChild      = "Gérer Sous-Modules {$lang->execution->common}";
$lang->tree->syncFromProduct      = "Copier d'un autre {$lang->productCommon}";
$lang->tree->dragAndSort          = "Faites glisser pour ordonner";
$lang->tree->sort                 = "Ordonnez";
$lang->tree->addChild             = "Ajout Sous-Module";
$lang->tree->confirmDelete        = 'Voulez-vous supprimer ce module et tous ses sous-modules ?';
$lang->tree->confirmDeleteMenu    = 'Do you want to delete this menu and its child menus?';
$lang->tree->confirmDelCategory   = 'Voulez-vous supprimer cette catégorie et ses sous-catégories?';
$lang->tree->confirmDeleteLine    = 'Voulez-vous supprimer cette ligne de produit ?';
$lang->tree->confirmDeleteGroup   = 'Do you want to delete this group and its child groups?';
$lang->tree->confirmRoot          = "Les changements du {$lang->productCommon} vont impacter les stories, bugs, casTests du {$lang->productCommon} auquel ils appartiennent, ainsi que les associations de {$lang->executionCommon} et {$lang->productCommon}, ce qui est dangereux. Voulez-vous malgré tout effectuer le changement ?";
$lang->tree->confirmRoot4Doc      = "Toute modification apportée à la bibliothèque modifiera le document de la bibliothèque à laquelle elle appartient, ce qui est dangereux. Voulez-vous le changer ?";
$lang->tree->noSubmodule          = "There are no copyable submodules under the current module!";
$lang->tree->successSave          = 'Sauvé.';
$lang->tree->successFixed         = 'Corrigé.';
$lang->tree->repeatName           = 'Le nom "%s" existe déjà !';
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
$lang->tree->name         = 'Nom';
$lang->tree->wordName     = 'Name';
$lang->tree->line         = 'Ligne Produit';
$lang->tree->cate         = 'Catégorie';
$lang->tree->dir          = 'Directory';
$lang->tree->root         = 'Racine';
$lang->tree->branch       = 'Plateforme/Branche';
$lang->tree->path         = 'Chemin';
$lang->tree->type         = 'Type';
$lang->tree->parent       = 'Module Parent';
$lang->tree->parentCate   = 'Parent Directory';
$lang->tree->child        = 'Enfants';
$lang->tree->parentGroup  = 'Parent group';
$lang->tree->childGroup   = 'Children';
$lang->tree->subCategory  = 'SubCategory';
$lang->tree->editCategory = 'Modifier la catégorie';
$lang->tree->delCategory  = 'Supprimer la catégorie';
$lang->tree->lineChild    = 'Sous-Ligne Produit';
$lang->tree->owner        = 'Propriétaire';
$lang->tree->order        = 'Ordre';
$lang->tree->short        = 'Abbr.';
$lang->tree->all          = 'Tous les Modules';
$lang->tree->executionDoc = "Document {$lang->executionCommon}";
$lang->tree->product      = $lang->productCommon;
$lang->tree->editDir      = "Edit Directory";

$lang->tree->emptyHistory = "Pas Historique";

$lang->module = new stdclass();
$lang->module->action = new stdclass();
$lang->module->action->created = array('main' => "\$date, créé  <strong>\$extra</strong> par <strong>\$actor</strong>.");
$lang->module->action->moved   = array('main' => "\$date, déplacé  <strong>\$extra</strong> par <strong>\$actor</strong>.");
$lang->module->action->deleted = array('main' => "\$date, supprimé <strong>\$extra</strong> par <strong>\$actor</strong>.");
