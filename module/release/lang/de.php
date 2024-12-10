<?php
/**
 * The release module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->release->create           = 'Erstellen';
$lang->release->edit             = 'Bearbeiten';
$lang->release->linkStory        = 'Story verknüpfen';
$lang->release->linkBug          = 'Bug verknüpfen';
$lang->release->delete           = 'Löschen';
$lang->release->deleted          = 'Gelöscht';
$lang->release->view             = 'Übersicht';
$lang->release->browse           = 'Durchsuchen';
$lang->release->publish          = 'Publish';
$lang->release->changeStatus     = 'Status ändern';
$lang->release->batchUnlink      = 'Mehrere entfernen';
$lang->release->batchUnlinkStory = 'Mehrere Storys entfernen';
$lang->release->batchUnlinkBug   = 'Mehrere Bugs entfernen';
$lang->release->manageSystem     = 'Manage ' . $lang->product->system;
$lang->release->addSystem        = 'Add ' . $lang->product->system;

$lang->release->confirmDelete      = 'Möchten Sie dieses Releas löschen?';
$lang->release->syncFromBuilds     = 'Link the stories completed in the version and the bugs solved to the release';
$lang->release->confirmUnlinkStory = 'Möchten Sie diese Story löschen?';
$lang->release->confirmUnlinkBug   = 'Möchten Sie diesen Bug löschen?';
$lang->release->existBuild         = '『Build』『%s』 existiert bereits. Sie können den 『name』 ändern oder ein anderes 『build』 wählen.';
$lang->release->noRelease          = 'Keine Releases. ';
$lang->release->errorDate          = 'The release date should not be greater than today.';
$lang->release->confirmActivate    = 'Do you want to activate this release?';
$lang->release->confirmTerminate   = 'Do you want to pause this release?';
$lang->release->confirmPublish     = 'Do you want to publish this release?';

$lang->release->basicInfo = 'Basis Info';

$lang->release->id             = 'ID';
$lang->release->product        = $lang->productCommon;
$lang->release->branch         = 'Platform/Branch';
$lang->release->project        = $lang->projectCommon;
$lang->release->build          = 'Build';
$lang->release->includedBuild  = 'Included Builds';
$lang->release->includedSystem = 'Included ' . $lang->product->system;
$lang->release->releases       = $lang->release->includedSystem;
$lang->release->includedApp    = 'Included ' . $lang->product->system;
$lang->release->relatedProject = 'Related ' . $lang->projectCommon;
$lang->release->system         = $lang->product->system;
$lang->release->selectSystem   = 'Select ' . $lang->product->system;
$lang->release->name           = $lang->product->system . ' Version';
$lang->release->marker         = 'Meilensteine';
$lang->release->date           = 'Plan Date';
$lang->release->releasedDate   = 'Actual Date';
$lang->release->desc           = 'Beschreibung';
$lang->release->files          = 'Gehäuse';
$lang->release->status         = 'Release Status';
$lang->release->subStatus      = 'Sub Status';
$lang->release->last           = 'Latest Version';
$lang->release->unlinkStory    = 'Story entfernen';
$lang->release->unlinkBug      = 'Bug entfernen';
$lang->release->stories        = 'Abgeschlossene Story';
$lang->release->bugs           = 'Gelöste Bugs';
$lang->release->leftBugs       = 'Verbleibende Bugs';
$lang->release->generatedBugs  = 'Gemeldete Bugs';
$lang->release->createdBy      = 'Created By';
$lang->release->createdDate    = 'Created Date';
$lang->release->finishStories  = 'Abgeschlossene %s Storys';
$lang->release->resolvedBugs   = 'Gelöste %s Bugs';
$lang->release->createdBugs    = 'Erstellte %s Bugs';
$lang->release->export         = 'Export as HTML';
$lang->release->yesterday      = 'Gestern veröffentlicht';
$lang->release->all            = 'All';
$lang->release->allProject     = 'All';
$lang->release->notify         = 'Notify';
$lang->release->notifyUsers    = 'Notify Users';
$lang->release->mailto         = 'Mailto';
$lang->release->mailContent    = '<p>Dear users,</p><p style="margin-left: 30px;">The following requirements and bugs you feedback have been released in the %s. Please contact your account manager to check the latest version.</p>';
$lang->release->storyList      = '<p style="margin-left: 30px;">Story List：%s。</p>';
$lang->release->bugList        = '<p style="margin-left: 30px;">Bug List：%s。</p>';
$lang->release->pageAllSummary = 'Total releases: <strong>%s</strong>, Released: <strong>%s</strong>, Terminate: <strong>%s</strong>.';
$lang->release->pageSummary    = "Total releases: <strong>%s</strong>.";
$lang->release->fileName       = 'File name';
$lang->release->exportRange    = 'Data to export';

$lang->release->storyTitle = 'Story Name';
$lang->release->bugTitle   = 'Bug Name';

$lang->release->filePath = 'Download : ';
$lang->release->scmPath  = 'SCM Pfad : ';

$lang->release->exportTypeList['all']     = 'Alle';
$lang->release->exportTypeList['story']   = $lang->release->stories;
$lang->release->exportTypeList['bug']     = $lang->release->bugs;
$lang->release->exportTypeList['leftbug'] = $lang->release->leftBugs;

$lang->release->resultList['normal'] = 'Release Success';
$lang->release->resultList['fail']   = 'Release Failed';

$lang->release->statusList['wait']      = 'Wait';
$lang->release->statusList['normal']    = 'Released';
$lang->release->statusList['fail']      = 'Failed';
$lang->release->statusList['terminate'] = 'Terminiert';

$lang->release->changeStatusList['wait']      = 'Publish';
$lang->release->changeStatusList['normal']    = 'Aktiviert';
$lang->release->changeStatusList['terminate'] = 'Terminiert';
$lang->release->changeStatusList['publish']   = 'Publish';
$lang->release->changeStatusList['active']    = 'Aktiviert';
$lang->release->changeStatusList['pause']     = 'Terminiert';

$lang->release->action = new stdclass();
$lang->release->action->changestatus = array('main' => '$date, $extra by  <strong>$actor</strong>.', 'extra' => 'changeStatusList');
$lang->release->action->notified     = array('main' => '$date, <strong>$actor</strong> send notify.');
$lang->release->action->published    = array('main' => '$date, published by <strong>$actor</strong>, result is <strong>$extra</strong>.', 'extra' => 'resultList');

$lang->release->notifyList['FB'] = "Feedback By";
$lang->release->notifyList['PO'] = "{$lang->productCommon} Owner";
$lang->release->notifyList['QD'] = 'QA Manager';
$lang->release->notifyList['SC'] = 'Story Creator';
$lang->release->notifyList['ET'] = "{$lang->execution->common} Team Members";
$lang->release->notifyList['PT'] = "{$lang->projectCommon} Team Members";
$lang->release->notifyList['CT'] = "Copy To";

$lang->release->featureBar['browse']['all']       = $lang->release->all;
$lang->release->featureBar['browse']['wait']      = $lang->release->statusList['wait'];
$lang->release->featureBar['browse']['normal']    = $lang->release->statusList['normal'];
$lang->release->featureBar['browse']['fail']      = $lang->release->statusList['fail'];
$lang->release->featureBar['browse']['terminate'] = $lang->release->statusList['terminate'];

$lang->release->markerList[1] = 'Yes';
$lang->release->markerList[0] = 'No';

$lang->release->failTips = 'Deployment/Launch Failed';
