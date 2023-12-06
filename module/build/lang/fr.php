<?php
/**
 * The build module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->build->common           = "Build";
$lang->build->create           = "Créer Build";
$lang->build->edit             = "Editer Build";
$lang->build->linkStory        = "Intégrer {$lang->SRCommon}";
$lang->build->linkBug          = "Intégrer Bug";
$lang->build->delete           = "Supprimer Build";
$lang->build->deleted          = "Supprimé";
$lang->build->view             = "Détail Build";
$lang->build->batchUnlink      = 'Retirer par Lot';
$lang->build->batchUnlinkStory = "Retirer {$lang->SRCommon} par Lot";
$lang->build->batchUnlinkBug   = 'Retirer Bugs par Lot';
$lang->build->viewBug          = 'Bugs';
$lang->build->bugList          = 'Bug List';
$lang->build->linkArtifactRepo = 'Link Artifact Repo';

$lang->build->confirmDelete      = "Voulez-vous supprimer ce build ?";
$lang->build->confirmUnlinkStory = "Voulez-vous retirer cette {$lang->SRCommon} du Build ?";
$lang->build->confirmUnlinkBug   = "Voulez-vous retirer ce Bug du Build ?";

$lang->build->basicInfo = 'Infos de Base';

$lang->build->id             = 'ID';
$lang->build->product        = $lang->productCommon;
$lang->build->project        = $lang->projectCommon;
$lang->build->branch         = 'Plateforme/Branche';
$lang->build->branchAll      = 'All associated %s';
$lang->build->branchName     = '%s';
$lang->build->execution      = $lang->executionCommon;
$lang->build->executionAB    = 'execution';
$lang->build->integrated     = 'Integrated';
$lang->build->singled        = 'Singled';
$lang->build->builds         = 'Included Builds';
$lang->build->released       = 'Released';
$lang->build->name           = 'Nom';
$lang->build->nameAB         = 'Name';
$lang->build->date           = 'Date';
$lang->build->builder        = 'Builder';
$lang->build->url            = 'URL';
$lang->build->scmPath        = 'Chemin SCM';
$lang->build->filePath       = 'Chemin Fichier';
$lang->build->desc           = 'Description';
$lang->build->mailto         = 'Mailto';
$lang->build->files          = 'Fichiers';
$lang->build->last           = 'Dernier Build';
$lang->build->packageType    = 'Type de Package';
$lang->build->unlinkStory    = "Retirer {$lang->SRCommon}";
$lang->build->unlinkBug      = 'Retirer Bug';
$lang->build->stories        = 'Stories terminées';
$lang->build->bugs           = 'Bugs Résolus';
$lang->build->generatedBugs  = 'Bugs signalés';
$lang->build->noProduct      = " <span id='noProduct' style='color:red'>Ce {$lang->executionCommon} n'est pas associé à un {$lang->productCommon}, le Build ne peut pas être créé. Commencez par <a href='%s' data-app='%s' data-toggle='modal' data-type='iframe'>rattacher le projet à un {$lang->productCommon}</a></span>";
$lang->build->noBuild        = 'Aucun builds.';
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

$lang->build->finishStories = "  {$lang->SRCommon} Terminées %s";
$lang->build->resolvedBugs  = '  Bugs Résolus %s';
$lang->build->createdBugs   = '  Bugs Signalés %s';

$lang->build->placeholder = new stdclass();
$lang->build->placeholder->scmPath        = ' Répertoire des codes sources, ex: chemin de la bibliothèque Subversion / GitSource';
$lang->build->placeholder->filePath       = ' Chemin de téléchargement pour ce Build.';
$lang->build->placeholder->multipleSelect = "Support select multiple builds";

$lang->build->action = new stdclass();
$lang->build->action->buildopened = '$date, Build <strong>$extra</strong> créé par <strong>$actor</strong>.' . "\n";

$lang->backhome = 'Retour';

$lang->build->isIntegrated = array();
$lang->build->isIntegrated['no']  = 'No';
$lang->build->isIntegrated['yes'] = 'Yes';
