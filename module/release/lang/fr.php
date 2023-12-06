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
$lang->release->create           = 'Créer Release';
$lang->release->edit             = 'Editer Release';
$lang->release->linkStory        = 'Intégrer Story';
$lang->release->linkBug          = 'Intégrer Bug';
$lang->release->delete           = 'Supprimer Release';
$lang->release->deleted          = 'Supprimée';
$lang->release->view             = 'Détail Release';
$lang->release->browse           = 'Liste Release';
$lang->release->changeStatus     = 'Change Statut';
$lang->release->batchUnlink      = 'Retirer par Lot';
$lang->release->batchUnlinkStory = 'Retirer Stories par Lot';
$lang->release->batchUnlinkBug   = 'Retirer Bugs par Lot';

$lang->release->confirmDelete      = 'Voulez-vous réellement supprimer cette release ?';
$lang->release->syncFromBuilds     = 'Link the stories completed in the version and the bugs solved to the release';
$lang->release->confirmUnlinkStory = 'Voulez-vous retirer cette story de la release ?';
$lang->release->confirmUnlinkBug   = 'Voulez-vous retirer ce bug de la release ?';
$lang->release->existBuild         = '『Build』『%s』existant. Vous pouvez changer『name』ou choisir un『build』.';
$lang->release->noRelease          = 'Pas de release à ce jour.';
$lang->release->errorDate          = "La date de release ne doit pas être supérieure à aujourd'hui.";
$lang->release->confirmActivate    = 'Do you want to activate this release?';
$lang->release->confirmTerminate   = 'Do you want to pause this release?';

$lang->release->basicInfo = 'Infos de Base';

$lang->release->id             = 'ID';
$lang->release->product        = $lang->productCommon;
$lang->release->branch         = 'Plateforme/Branche';
$lang->release->project        = $lang->projectCommon;
$lang->release->build          = 'Build';
$lang->release->includedBuild  = 'Included Builds';
$lang->release->relatedProject = 'Related ' . $lang->projectCommon;
$lang->release->name           = 'Nom';
$lang->release->marker         = 'Etape Importante';
$lang->release->date           = 'Date Release';
$lang->release->desc           = 'Description';
$lang->release->files          = 'Files';
$lang->release->status         = 'Statut';
$lang->release->subStatus      = 'Sous-statut';
$lang->release->last           = 'Dernière Release';
$lang->release->unlinkStory    = 'Retirer Story';
$lang->release->unlinkBug      = 'Retirer Bug';
$lang->release->stories        = 'Stories Terminées';
$lang->release->bugs           = 'Bugs Résolus';
$lang->release->leftBugs       = 'Bugs Actifs';
$lang->release->generatedBugs  = 'Bugs Actifs';
$lang->release->finishStories  = '%s Stories Terminées';
$lang->release->resolvedBugs   = '%s Bugs Résolus';
$lang->release->createdBugs    = '%s Bugs non résolus';
$lang->release->export         = 'Export HTML';
$lang->release->yesterday      = 'Versionné Hier';
$lang->release->all            = 'Tout';
$lang->release->allProject     = 'All';
$lang->release->notify         = 'Notify';
$lang->release->notifyUsers    = 'Notify Users';
$lang->release->mailto         = 'Mailto';
$lang->release->mailContent    = '<p>Dear users,</p><p style="margin-left: 30px;">The following requirements and bugs you feedback have been released in the %s. Please contact your account manager to check the latest version.</p>';
$lang->release->storyList      = '<p style="margin-left: 30px;">Story List：%s。</p>';
$lang->release->bugList        = '<p style="margin-left: 30px;">Bug List：%s。</p>';
$lang->release->pageAllSummary = 'Total releases: <strong>%s</strong>, Normal: <strong>%s</strong>, Terminate: <strong>%s</strong>.';
$lang->release->pageSummary    = "Total releases: <strong>%s</strong>.";
$lang->release->fileName       = 'File name';
$lang->release->exportRange    = 'Data to export';

$lang->release->storyTitle = 'Story Name';
$lang->release->bugTitle   = 'Bug Name';

$lang->release->filePath = 'Télecharger : ';
$lang->release->scmPath  = 'SCM Path : ';

$lang->release->exportTypeList['all']     = 'Tous';
$lang->release->exportTypeList['story']   = 'Story';
$lang->release->exportTypeList['bug']     = 'Bug';
$lang->release->exportTypeList['leftbug'] = 'Bug Actifs';

$lang->release->statusList['']          = '';
$lang->release->statusList['normal']    = 'Normale';
$lang->release->statusList['terminate'] = 'Terminée';

$lang->release->changeStatusList['normal']    = 'Active';
$lang->release->changeStatusList['terminate'] = 'Terminée';

$lang->release->action = new stdclass();
$lang->release->action->changestatus = array('main' => '$date, $extra by  <strong>$actor</strong>.', 'extra' => 'changeStatusList');
$lang->release->action->notified     = array('main' => '$date, <strong>$actor</strong> send notify.');

$lang->release->notifyList['FB'] = "Feedback By";
$lang->release->notifyList['PO'] = "{$lang->productCommon} Owner";
$lang->release->notifyList['QD'] = 'QA Manager';
$lang->release->notifyList['SC'] = 'Story Creator';
$lang->release->notifyList['ET'] = "{$lang->execution->common} Team Members";
$lang->release->notifyList['PT'] = "{$lang->projectCommon} Team Members";
$lang->release->notifyList['CT'] = "Copy To";

$lang->release->featureBar['browse']['all']       = $lang->release->all;
$lang->release->featureBar['browse']['normal']    = $lang->release->statusList['normal'];
$lang->release->featureBar['browse']['terminate'] = $lang->release->statusList['terminate'];

$lang->release->markerList[1] = 'Yes';
$lang->release->markerList[0] = 'No';
