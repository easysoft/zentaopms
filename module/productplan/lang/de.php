<?php
/**
 * The productplan module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: en.php 4659 2013-04-17 06:45:08Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->productplan->common     = $lang->productCommon . ' Plan';
$lang->productplan->browse     = "Durchsuchen";
$lang->productplan->index      = "Liste";
$lang->productplan->create     = "Erstellen";
$lang->productplan->edit       = "Bearbeiten";
$lang->productplan->delete     = "Löschen";
$lang->productplan->view       = "Details";
$lang->productplan->bugSummary = "<strong>%s</strong> Bugs auf dieser Seite";
$lang->productplan->basicInfo  = 'Basis Info';
$lang->productplan->batchEdit  = 'Mehrere bearbeiten';

$lang->productplan->batchUnlink      = "Mehere Verknüpfungen aufheben";
$lang->productplan->linkStory        = "Story verknüpfen";
$lang->productplan->unlinkStory      = "Story verknüpgung aufheben";
$lang->productplan->unlinkStoryAB    = "Unlink";
$lang->productplan->batchUnlinkStory = "Mehere Verknüpfungen aufheben";
$lang->productplan->linkedStories    = 'Verknüpfte Storys';
$lang->productplan->unlinkedStories  = 'Unverknüpfte Storys';
$lang->productplan->updateOrder      = 'Sortierung';
$lang->productplan->createChildren   = "Create Child Plans";

$lang->productplan->linkBug          = "Bug Verknüpfen";
$lang->productplan->unlinkBug        = "Bug Verknpfung aufheben";
$lang->productplan->batchUnlinkBug   = "Mehrere Verknpfungen aufheben";
$lang->productplan->linkedBugs       = 'Verknüpfte Bugs';
$lang->productplan->unlinkedBugs     = 'Unverknüpfte Bugs';
$lang->productplan->unexpired        = 'Unexpired Plans';
$lang->productplan->all              = 'All Plans';

$lang->productplan->confirmDelete      = "Möchten Sie diesen Plan löschen?";
$lang->productplan->confirmUnlinkStory = "Möchten Sie diese Story löschen?";
$lang->productplan->confirmUnlinkBug   = "Möchten Sie diesen Bug löschen?";
$lang->productplan->noPlan             = 'Kein Plan. ';
$lang->productplan->cannotDeleteParent = 'Cannot delete parent plan';

$lang->productplan->id         = 'ID';
$lang->productplan->product    = $lang->productCommon;
$lang->productplan->branch     = 'Platform/Branch';
$lang->productplan->title      = 'Titel';
$lang->productplan->desc       = 'Beschreibung';
$lang->productplan->begin      = 'Start';
$lang->productplan->end        = 'Ende';
$lang->productplan->last       = 'Letzter Plan';
$lang->productplan->future     = 'Wartend';
$lang->productplan->stories    = 'Storys';
$lang->productplan->bugs       = 'Bugs';
$lang->productplan->hour       = $lang->hourCommon;
$lang->productplan->project    = $lang->projectCommon;
$lang->productplan->parent     = "Parent Plan";
$lang->productplan->parentAB   = "Parent";
$lang->productplan->children   = "Child Plan";
$lang->productplan->childrenAB = "C";
$lang->productplan->order      = "Rank";
$lang->productplan->deleted    = "Deleted";

$lang->productplan->endList[7]    = '1 Woche';
$lang->productplan->endList[14]   = '2 Wochen';
$lang->productplan->endList[31]   = '1 Monat';
$lang->productplan->endList[62]   = '2 Monate';
$lang->productplan->endList[93]   = '3 Monate';
$lang->productplan->endList[186]  = '6 Monate';
$lang->productplan->endList[365]  = '1 Jahr';

$lang->productplan->errorNoTitle = 'ID %s Titel darf nicht leer sein.';
$lang->productplan->errorNoBegin = 'ID %s Start darf nicht leer sein.';
$lang->productplan->errorNoEnd   = 'ID %s Ende darf nicht leer sein.';
$lang->productplan->beginGeEnd   = 'ID %s Start darf nicht größer als Ende sein.';

$lang->productplan->featureBar['browse']['all']       = 'Alle';
$lang->productplan->featureBar['browse']['unexpired'] = 'Nicht abgelaufen';
$lang->productplan->featureBar['browse']['overdue']   = 'Abgelaufen';
