<?php
/**
 * The build module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->build->common           = "Build";
$lang->build->create           = "Build erstellen";
$lang->build->edit             = "Bearbeiten";
$lang->build->linkStory        = "{$lang->SRCommon} verknüpfen";
$lang->build->linkBug          = "Bug verknüpfen";
$lang->build->delete           = "Build löschen";
$lang->build->deleted          = "Gelöscht";
$lang->build->view             = "Build Details";
$lang->build->batchUnlink      = 'Batch Unlink';
$lang->build->batchUnlinkStory = "Batch {$lang->SRCommon} Unlink";
$lang->build->batchUnlinkBug   = 'Batch Bug Unlink';

$lang->build->confirmDelete      = "Möchten Sie dieses Build löschen?";
$lang->build->confirmUnlinkStory = "Möchten Sie diese {$lang->SRCommon} löschen?";
$lang->build->confirmUnlinkBug   = "Möchten Sie die Verknüpfung zum Bug aufheben?";

$lang->build->basicInfo = 'Basis Info';

$lang->build->id            = 'ID';
$lang->build->product       = $lang->productCommon;
$lang->build->branch        = 'Platform/Branch';
$lang->build->execution     = $lang->executionCommon;
$lang->build->name          = 'Name';
$lang->build->date          = 'Datum';
$lang->build->builder       = 'Builder';
$lang->build->scmPath       = 'SCM Pfad';
$lang->build->filePath      = 'Dateipfad';
$lang->build->desc          = 'Beschreibung';
$lang->build->files         = 'Datei Upload';
$lang->build->last          = 'Letztes Build';
$lang->build->packageType   = 'Package Typ';
$lang->build->unlinkStory   = "{$lang->SRCommon} Verknüpgung aufheben";
$lang->build->unlinkBug     = 'Bug Verknüpgung aufheben';
$lang->build->stories       = "Abgeschlossene {$lang->SRCommon}";
$lang->build->bugs          = 'Gelöster Bug';
$lang->build->generatedBugs = 'Left Bug';
$lang->build->noProduct     = " <span id='noProduct' style='color:red'>Dieses {$lang->executionCommon} ist nicht mit einem {$lang->productCommon} verknüpft, daher kann das Build nicht erstellt werden. Bitte erst <a href='%s' data-app='%s' data-toggle='modal' data-type='iframe'> {$lang->productCommon} verknüpfen.</a></span>";
$lang->build->noBuild       = 'Keine Builds. ';

$lang->build->notice = new stdclass();
$lang->build->notice->changeProduct   = "The {$lang->SRCommon}, bug, or the version of the submitted test order has been linked, and its {$lang->productCommon} cannot be modified";
$lang->build->notice->changeExecution = "The version of the submitted test order cannot be modified {$lang->executionCommon}";

$lang->build->finishStories = "  %s {$lang->SRCommon} sind abgeschlossen.";
$lang->build->resolvedBugs  = '  %s Bugs sind gelöst.';
$lang->build->createdBugs   = '  %s Bugs wurden erstellt.';

$lang->build->placeholder = new stdclass();
$lang->build->placeholder->scmPath  = ' Source code repository, z.B. Subversion/Git Pfad';
$lang->build->placeholder->filePath = ' Pfad zum Download für diese Build.';

$lang->build->action = new stdclass();
$lang->build->action->buildopened = '$date, erstellt von <strong>$actor</strong>, Build <strong>$extra</strong>.' . "\n";

$lang->backhome = 'zurück';
