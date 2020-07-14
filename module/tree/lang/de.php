<?php
/**
 * The tree module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: en.php 5045 2013-07-06 07:04:40Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->tree = new stdclass();
$lang->tree->common             = 'Modul';
$lang->tree->edit               = 'Bearbeiten';
$lang->tree->delete             = 'Löschen';
$lang->tree->browse             = 'Allgemeine Module verwalten';
$lang->tree->browseTask         = 'Aufgaben Modul verwalten';
$lang->tree->manage             = 'Modul verwalten';
$lang->tree->fix                = 'Fix';
$lang->tree->manageProduct      = "Verwalte {$lang->productCommon}";
$lang->tree->manageProject      = "Verwalte {$lang->projectCommon}";
$lang->tree->manageLine         = "Manage {$lang->productCommon} Line";
$lang->tree->manageBug          = 'Verwalte Bugs';
$lang->tree->manageCase         = 'Verwalte Fälle';
$lang->tree->manageCaseLib      = 'Verwalte Bibliothek';
$lang->tree->manageCustomDoc    = 'Verwalte Dokumente';
$lang->tree->updateOrder        = 'Sortierung';
$lang->tree->manageChild        = 'Verwalte Untermodul';
$lang->tree->manageStoryChild   = 'Verwalte Untermodul';
$lang->tree->manageLineChild    = 'Verwalte Produktlinie';
$lang->tree->manageBugChild     = 'Verwalte Bugs';
$lang->tree->manageCaseChild    = 'Verwalte Fälle';
$lang->tree->manageCaselibChild = 'Verwalte Bibliothek';
$lang->tree->manageTaskChild    = "Verwalte {$lang->projectCommon}";
$lang->tree->syncFromProduct    = "Copy from Other {$lang->productCommon}s";
$lang->tree->dragAndSort        = "Ziehen und Sotieren";
$lang->tree->sort               = "Sortieren";
$lang->tree->addChild           = "Hinzufügen";
$lang->tree->confirmDelete      = 'Do you want to delete this module and its child modules?';
$lang->tree->confirmDeleteLine  = "Do you want to delete this {$lang->productCommon} line?";
$lang->tree->confirmRoot        = "Any changes to the {$lang->productCommon} will change the stories, bugs, cases of {$lang->productCommon} it belongs to, as well as the linkage of {$lang->projectCommon} and {$lang->productCommon}, which is dangerous. Do you want to change it?";
$lang->tree->confirmRoot4Doc    = "Any changes to the library will change the document of library it belongs to, which is dangerous. Do you want to change it?";
$lang->tree->successSave        = 'Saved.';
$lang->tree->successFixed       = 'Fixed.';
$lang->tree->repeatName         = 'The name "%s" exists!';
$lang->tree->shouldNotBlank     = 'Module name should not be blank!';

$lang->tree->module     = 'Modul';
$lang->tree->name       = 'Name';
$lang->tree->line       = "{$lang->productCommon} Line";
$lang->tree->cate       = 'Kategorie Name';
$lang->tree->root       = 'Root';
$lang->tree->branch     = 'Platform/Branch';
$lang->tree->path       = 'Pfad';
$lang->tree->type       = 'Typ';
$lang->tree->parent     = 'Übergeordnet';
$lang->tree->parentCate = 'Parent Category';
$lang->tree->child      = 'Untergeordnet';
$lang->tree->lineChild  = "Child {$lang->productCommon} Line";
$lang->tree->owner      = 'Besitzer';
$lang->tree->order      = 'Reihenfolge';
$lang->tree->short      = 'Sortierung';
$lang->tree->all        = 'Alle Module';
$lang->tree->projectDoc = "{$lang->projectCommon} Dok";
$lang->tree->product    = $lang->productCommon;
