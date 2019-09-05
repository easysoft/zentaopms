<?php
/**
 * The release module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->release->common           = 'Release';
$lang->release->create           = "Erstellen";
$lang->release->edit             = "Bearbeiten";
$lang->release->linkStory        = "Story verknüpfen";
$lang->release->linkBug          = "Bug verknüpfen";
$lang->release->delete           = "Löschen";
$lang->release->deleted          = 'Gelöscht';
$lang->release->view             = "Übersicht";
$lang->release->browse           = "Durchsuchen";
$lang->release->changeStatus     = "Status ändern";
$lang->release->batchUnlink      = "Mehrere entfernen";
$lang->release->batchUnlinkStory = "Mehrere Storys entfernen";
$lang->release->batchUnlinkBug   = "Mehrere Bugs entfernen";

$lang->release->confirmDelete      = "Möchten Sie dieses Releas löschen?";
$lang->release->confirmUnlinkStory = "Möchten Sie diese Story löschen?";
$lang->release->confirmUnlinkBug   = "Möchten Sie diesen Bug löschen?";
$lang->release->existBuild         = '『Build』『%s』 existiert bereits. Sie können den 『name』 ändern oder ein anderes 『build』 wählen.';
$lang->release->noRelease          = 'Keine Releases. ';
$lang->release->errorDate          = 'The release date should not be greater than today.';

$lang->release->basicInfo = 'Basis Info';

$lang->release->id            = 'ID';
$lang->release->product       = $lang->productCommon;
$lang->release->branch        = 'Platform/Branch';
$lang->release->build         = 'Build';
$lang->release->name          = 'Name';
$lang->release->marker        = 'Meilensteine';
$lang->release->date          = 'Datum';
$lang->release->desc          = 'Beschreibung';
$lang->release->status        = 'Status';
$lang->release->subStatus     = 'Sub Status';
$lang->release->last          = 'Letztes Release';
$lang->release->unlinkStory   = 'Story entfernen';
$lang->release->unlinkBug     = 'Bug entfernen';
$lang->release->stories       = 'Abgeschlossene Story';
$lang->release->bugs          = 'Gelöste Bugs';
$lang->release->leftBugs      = 'Verbleibende Bugs';
$lang->release->generatedBugs = 'Gemeldete Bugs';
$lang->release->finishStories = 'Abgeschlossene %s Storys';
$lang->release->resolvedBugs  = 'Gelöste %s Bugs';
$lang->release->createdBugs   = 'Erstellte %s Bugs';
$lang->release->export        = 'Export as HTML';
$lang->release->yesterday     = 'Gestern veröffentlicht';
$lang->release->all           = 'All';

$lang->release->filePath = 'Download : ';
$lang->release->scmPath  = 'SCM Pfad : ';

$lang->release->exportTypeList['all']     = 'Alle';
$lang->release->exportTypeList['story']   = 'Story';
$lang->release->exportTypeList['bug']     = 'Bug';
$lang->release->exportTypeList['leftbug'] = 'Ungelöste Bugs';

$lang->release->statusList['']          = '';
$lang->release->statusList['normal']    = 'Normal';
$lang->release->statusList['terminate'] = 'Terminiert';

$lang->release->changeStatusList['normal']    = 'Aktiviert';
$lang->release->changeStatusList['terminate'] = 'Terminiert';

$lang->release->action = new stdclass();
$lang->release->action->changestatus = array('main' => '$date, 由 <strong>$actor</strong> $extra。', 'extra' => 'changeStatusList');
