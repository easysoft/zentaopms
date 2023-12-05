<?php
/**
 * The build module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
$lang->build->viewBug          = 'Bugs';
$lang->build->bugList          = 'Bug List';
$lang->build->linkArtifactRepo = 'Link Artifact Repo';

$lang->build->confirmDelete      = "Möchten Sie dieses Build löschen?";
$lang->build->confirmUnlinkStory = "Möchten Sie diese {$lang->SRCommon} löschen?";
$lang->build->confirmUnlinkBug   = "Möchten Sie die Verknüpfung zum Bug aufheben?";

$lang->build->basicInfo = 'Basis Info';

$lang->build->id             = 'ID';
$lang->build->product        = $lang->productCommon;
$lang->build->project        = $lang->projectCommon;
$lang->build->branch         = 'Platform/Branch';
$lang->build->branchAll      = 'All associated %s';
$lang->build->branchName     = '%s';
$lang->build->execution      = $lang->executionCommon;
$lang->build->executionAB    = 'execution';
$lang->build->integrated     = 'Integrated';
$lang->build->singled        = 'Singled';
$lang->build->builds         = 'Included Builds';
$lang->build->released       = 'Released';
$lang->build->name           = 'Name';
$lang->build->nameAB         = 'Name';
$lang->build->date           = 'Datum';
$lang->build->builder        = 'Builder';
$lang->build->url            = 'URL';
$lang->build->scmPath        = 'SCM Pfad';
$lang->build->filePath       = 'Dateipfad';
$lang->build->desc           = 'Beschreibung';
$lang->build->mailto         = 'Mailto';
$lang->build->files          = 'Datei Upload';
$lang->build->last           = 'Letztes Build';
$lang->build->packageType    = 'Package Typ';
$lang->build->unlinkStory    = "{$lang->SRCommon} Verknüpgung aufheben";
$lang->build->unlinkBug      = 'Bug Verknüpgung aufheben';
$lang->build->stories        = "Abgeschlossene {$lang->SRCommon}";
$lang->build->bugs           = 'Gelöster Bug';
$lang->build->generatedBugs  = 'Left Bug';
$lang->build->noProduct      = " <span id='noProduct' style='color:red'>Dieses {$lang->executionCommon} ist nicht mit einem {$lang->productCommon} verknüpft, daher kann das Build nicht erstellt werden. Bitte erst <a href='%s' data-app='%s' data-toggle='modal' data-type='iframe'> {$lang->productCommon} verknüpfen.</a></span>";
$lang->build->noBuild        = 'Keine Builds. ';
$lang->build->emptyExecution = $lang->executionCommon . 'should be not empty.';
$lang->build->linkedBuild    = 'Linked Build';
$lang->build->createTest     = 'Submit Request';

$lang->build->notice = new stdclass();
$lang->build->notice->changeProduct   = "The {$lang->SRCommon}, bug, or the version of the submitted test order has been linked, and its {$lang->productCommon} cannot be modified";
$lang->build->notice->changeExecution = "The version of the submitted test order cannot be modified {$lang->executionCommon}";
$lang->build->notice->changeBuilds    = "The version of the submitted test order cannot be modified builds";
$lang->build->notice->autoRelation    = "The completed requirements, resolved bugs, and generated bugs under the relevant version will be automatically associated with the {$lang->projectCommon} version";
$lang->build->notice->createTest      = "The execution of this version has been deleted, and the test cannot be submitted";

$lang->build->confirmChangeBuild = "After branch『%s』disassociation,under the %s have %s {$lang->SRCommon} and %s Bug will remove synchronization from version, whether to cancel？";
$lang->build->confirmRemoveStory = "After branch『%s』disassociation,under the %s have %s {$lang->SRCommon} will remove synchronization from version, whether to cancel？";
$lang->build->confirmRemoveBug   = "After branch『%s』disassociation,under the %s have %s Bug will remove synchronization from version, whether to cancel？";
$lang->build->confirmRemoveTips  = "Are you sure to delete %s『%s』?";

$lang->build->finishStories = "  %s {$lang->SRCommon} sind abgeschlossen.";
$lang->build->resolvedBugs  = '  %s Bugs sind gelöst.';
$lang->build->createdBugs   = '  %s Bugs wurden erstellt.';

$lang->build->placeholder = new stdclass();
$lang->build->placeholder->scmPath        = ' Source code repository, z.B. Subversion/Git Pfad';
$lang->build->placeholder->filePath       = ' Pfad zum Download für diese Build.';
$lang->build->placeholder->multipleSelect = "Support select multiple builds";

$lang->build->action = new stdclass();
$lang->build->action->buildopened = '$date, erstellt von <strong>$actor</strong>, Build <strong>$extra</strong>.' . "\n";

$lang->backhome = 'zurück';

$lang->build->isIntegrated = array();
$lang->build->isIntegrated['no']  = 'No';
$lang->build->isIntegrated['yes'] = 'Yes';
