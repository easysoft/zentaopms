<?php
/**
 * The release module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.pm
 */
$lang->release->common           = 'Release';
$lang->release->create           = "Créer Release";
$lang->release->edit             = "Editer Release";
$lang->release->linkStory        = "Intégrer Story";
$lang->release->linkBug          = "Intégrer Bug";
$lang->release->delete           = "Supprimer Release";
$lang->release->deleted          = 'Supprimée';
$lang->release->view             = "Détail Release";
$lang->release->browse           = "Liste Release";
$lang->release->changeStatus     = "Change Statut";
$lang->release->batchUnlink      = "Retirer par Lot";
$lang->release->batchUnlinkStory = "Retirer Stories par Lot";
$lang->release->batchUnlinkBug   = "Retirer Bugs par Lot";

$lang->release->confirmDelete      = "Voulez-vous réellement supprimer cette release ?";
$lang->release->confirmUnlinkStory = "Voulez-vous retirer cette story de la release ?";
$lang->release->confirmUnlinkBug   = "Voulez-vous retirer ce bug de la release ?";
$lang->release->existBuild         = '『Build』『%s』existant. Vous pouvez changer『name』ou choisir un『build』.';
$lang->release->noRelease          = 'Pas de release à ce jour.';
$lang->release->errorDate          = "La date de release ne doit pas être supérieure à aujourd'hui.";

$lang->release->basicInfo = 'Infos de Base';

$lang->release->id            = 'ID';
$lang->release->product       = $lang->productCommon;
$lang->release->branch        = 'Plateforme/Branche';
$lang->release->build         = 'Build';
$lang->release->name          = 'Nom';
$lang->release->marker        = 'Etape Importante';
$lang->release->date          = 'Date Release';
$lang->release->desc          = 'Description';
$lang->release->status        = 'Statut';
$lang->release->subStatus     = 'Sous-statut';
$lang->release->last          = 'Dernière Release';
$lang->release->unlinkStory   = 'Retirer Story';
$lang->release->unlinkBug     = 'Retirer Bug';
$lang->release->stories       = 'Stories Terminées';
$lang->release->bugs          = 'Bugs Résolus';
$lang->release->leftBugs      = 'Bugs Actifs';
$lang->release->generatedBugs = 'Bugs Actifs';
$lang->release->finishStories = '%s Stories Terminées';
$lang->release->resolvedBugs  = '%s Bugs Résolus';
$lang->release->createdBugs   = '%s Bugs non résolus';
$lang->release->export        = 'Export HTML';
$lang->release->yesterday     = 'Versionné Hier';
$lang->release->all           = 'Tout';

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
$lang->release->action->changestatus = array('main' => '$date,  $extra par  <strong>$actor</strong>', 'extra' => 'changeStatusList');
