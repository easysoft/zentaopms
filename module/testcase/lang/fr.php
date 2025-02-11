<?php
/**
 * The testcase module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id: en.php 4966 2013-07-02 02:59:25Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->testcase->id               = 'ID';
$lang->testcase->product          = $lang->productCommon;
$lang->testcase->project          = $lang->projectCommon;
$lang->testcase->execution        = $lang->executionCommon;
$lang->testcase->linkStory        = 'linkStory';
$lang->testcase->module           = 'Module';
$lang->testcase->auto             = 'Test Automation Cases';
$lang->testcase->frame            = 'Test Automation Cramework';
$lang->testcase->howRun           = 'Testing Method';
$lang->testcase->frequency        = 'Frequency';
$lang->testcase->path             = 'Path';
$lang->testcase->lib              = "Library de cas";
$lang->testcase->branch           = "Branche/Plateforme";
$lang->testcase->moduleAB         = 'Module';
$lang->testcase->story            = 'Story';
$lang->testcase->storyVersion     = 'Version Story';
$lang->testcase->color            = 'Couleur';
$lang->testcase->order            = 'Ordre';
$lang->testcase->title            = 'Titre';
$lang->testcase->precondition     = 'Prérequis';
$lang->testcase->pri              = 'Priorité';
$lang->testcase->type             = 'Type';
$lang->testcase->status           = 'Statut';
$lang->testcase->statusAB         = 'Statut';
$lang->testcase->subStatus        = 'Sous-statut';
$lang->testcase->steps            = 'Etape';
$lang->testcase->openedBy         = 'Créé par';
$lang->testcase->openedByAB       = 'Détecteur';
$lang->testcase->openedDate       = 'Créé le';
$lang->testcase->lastEditedBy     = 'Modifié par';
$lang->testcase->result           = 'Résultat';
$lang->testcase->resultAB         = 'Résultat';
$lang->testcase->real             = 'Détails';
$lang->testcase->keywords         = 'Tags';
$lang->testcase->files            = 'Fichiers';
$lang->testcase->linkCase         = 'CasTests liés';
$lang->testcase->linkCases        = 'Associer CasTests';
$lang->testcase->unlinkCase       = 'Dissocier CasTest';
$lang->testcase->linkBug          = 'Linked Bugs';
$lang->testcase->linkBugs         = 'Link Bug';
$lang->testcase->unlinkBug        = 'Unlink Bugs';
$lang->testcase->stage            = 'Phase';
$lang->testcase->script           = 'Automation Script';
$lang->testcase->scriptedBy       = 'ScriptedBy';
$lang->testcase->scriptedDate     = 'ScriptedDate';
$lang->testcase->scriptStatus     = 'Script Status';
$lang->testcase->scriptLocation   = 'Script Location';
$lang->testcase->reviewedBy       = 'Validé par';
$lang->testcase->reviewedDate     = 'Validé le';
$lang->testcase->reviewResult     = 'Résultat validation';
$lang->testcase->reviewedByAB     = 'Validé par';
$lang->testcase->reviewedDateAB   = 'Validé le';
$lang->testcase->forceReview      = 'Review Required';
$lang->testcase->isReviewed       = 'is it reviewed';
$lang->testcase->lastEditedByAB   = 'Modifié par';
$lang->testcase->lastEditedDateAB = 'Modifié le';
$lang->testcase->lastEditedDate   = 'Modifié le';
$lang->testcase->version          = 'Version CasTest';
$lang->testcase->lastRunner       = 'Joué par';
$lang->testcase->lastRunDate      = 'Dernier Run';
$lang->testcase->assignedTo       = 'Affecté à';
$lang->testcase->colorTag         = 'Couleur';
$lang->testcase->lastRunResult    = 'Résultat';
$lang->testcase->desc             = 'Etape';
$lang->testcase->parent           = 'Parent';
$lang->testcase->xml              = 'XML';
$lang->testcase->expect           = 'Résultat Attendu';
$lang->testcase->allProduct       = "Tous {$lang->productCommon}s";
$lang->testcase->fromBug          = 'Depuis Bug';
$lang->testcase->toBug            = 'Vers Bug';
$lang->testcase->changed          = 'Changé';
$lang->testcase->bugs             = 'Bugs Signalés';
$lang->testcase->bugsAB           = 'B';
$lang->testcase->results          = 'Résultat';
$lang->testcase->resultsAB        = 'R';
$lang->testcase->stepNumber       = 'Etape';
$lang->testcase->stepNumberAB     = 'S';
$lang->testcase->createBug        = 'Signaler Bug';
$lang->testcase->fromModule       = 'Module Source';
$lang->testcase->fromCase         = 'CasTest Source';
$lang->testcase->sync             = 'Sync. CasTests';
$lang->testcase->ignore           = 'Ignorer';
$lang->testcase->fromTesttask     = 'De Campagne';
$lang->testcase->fromCaselib      = 'De Library';
$lang->testcase->fromCaseID       = 'From Case ID';
$lang->testcase->fromCaseVersion  = 'From Case Version';
$lang->testcase->mailto           = 'Mailto';
$lang->testcase->deleted          = 'Deleted';
$lang->testcase->browseUnits      = 'Unit Test';
$lang->testcase->suite            = 'Test Suite';
$lang->testcase->executionStatus  = 'executionStatus';
$lang->testcase->caseType         = 'Case Type';
$lang->testcase->allType          = 'All Types';
$lang->testcase->automated        = 'Automated';
$lang->testcase->automation       = 'Automation Test';

$lang->case = $lang->testcase;  // For dao checking using. Because 'case' is a php keywords, so the module name is testcase, table name is still case.

$lang->testcase->stepID            = 'ID';
$lang->testcase->stepDesc          = 'Etape';
$lang->testcase->stepExpect        = 'Résultat Attendu';
$lang->testcase->stepVersion       = 'Version';
$lang->testcase->stepSameLevel     = 'Sib';
$lang->testcase->stepSubLevel      = 'Sub';
$lang->testcase->expectDisabledTip = 'Expect disabled when has sub steps.';
$lang->testcase->deleteStepTip     = 'This step contains levels and cannot be deleted';
$lang->testcase->dragNestedTip     = 'Supports up to three levels of nesting, cannot be dragged here';
$lang->testcase->stepsPlaceholder  = "Supports up to 3 nested levels. Any deeper structure will be ignored.
Number each test step with  + ‘.’ on a new line.
Expected results should use matching '+ ‘.’ numbers.";

$lang->testcase->index                   = "Accueil CasTest";
$lang->testcase->create                  = "Ajout CasTest";
$lang->testcase->batchCreate             = "Ajouter par Lot";
$lang->testcase->delete                  = "Supprimer";
$lang->testcase->deleteAction            = "Supprimer CasTest";
$lang->testcase->view                    = "Détail CasTest";
$lang->testcase->review                  = "Doit être validé";
$lang->testcase->reviewAB                = "Validation";
$lang->testcase->reviewAction            = "Review Case";
$lang->testcase->batchReview             = "Validation par Lot";
$lang->testcase->edit                    = "Modifier CasTest";
$lang->testcase->batchEdit               = "Modifier par Lot ";
$lang->testcase->batchChangeModule       = "Changer Modules par Lot";
$lang->testcase->confirmLibcaseChange    = "Confirmer Modification Library";
$lang->testcase->ignoreLibcaseChange     = "Ignorer Modification Library";
$lang->testcase->batchChangeBranch       = "Changer Branches par Lot";
$lang->testcase->groupByStories          = 'Grouper par Story';
$lang->testcase->batchDelete             = "Supprimer par Lot ";
$lang->testcase->batchConfirmStoryChange = "Confirmer par Lot";
$lang->testcase->batchChangeType         = "Changer Types par Lot";
$lang->testcase->browse                  = "Liste CasTests";
$lang->testcase->listView                = "View by List";
$lang->testcase->groupCase               = "Vue par Groupe";
$lang->testcase->groupView               = "Group View";
$lang->testcase->zeroCase                = "Stories sans CasTests";
$lang->testcase->import                  = "Importer";
$lang->testcase->importAction            = "Importer CasTest";
$lang->testcase->importCaseAction        = "Importer CasTest";
$lang->testcase->fileImport              = "Importer CSV";
$lang->testcase->importFile              = "Import File";
$lang->testcase->importFromLib           = "Importer de la Library";
$lang->testcase->showImport              = "Voir Import";
$lang->testcase->exportTemplate          = "Exporter Modèle";
$lang->testcase->export                  = "Exporter Données";
$lang->testcase->exportAction            = "Exporter CasTest";
$lang->testcase->reportChart             = 'Graphique';
$lang->testcase->reportAction            = 'Rapport CasTest';
$lang->testcase->confirmChange           = 'Confirmer Modification CasTest';
$lang->testcase->confirmStoryChange      = 'Confirmer Modification Story';
$lang->testcase->copy                    = 'Copier CasTest';
$lang->testcase->group                   = 'Groupe';
$lang->testcase->groupName               = 'Nom Groupe';
$lang->testcase->step                    = 'Etapes';
$lang->testcase->stepChild               = 'Sous-Etapes';
$lang->testcase->viewAll                 = 'Tous les CasTests';
$lang->testcase->importToLib             = "Import To Library";
$lang->testcase->showScript              = 'Show Script';
$lang->testcase->autoScript              = 'Script';
$lang->testcase->autoCase                = 'Automation';

$lang->testcase->new = 'Nouveau';

$lang->testcase->num      = 'Ligne CasTes:';
$lang->testcase->encoding = 'Encoding';

$lang->testcase->deleteStep   = 'Supprimer';
$lang->testcase->insertBefore = 'Insérer Avant';
$lang->testcase->insertAfter  = 'Insérer Après';

$lang->testcase->assignToMe   = 'Affecté à Moi';
$lang->testcase->openedByMe   = 'Créé par Moi';
$lang->testcase->allCases     = 'Tous';
$lang->testcase->allTestcases = 'Tous les CasTests';
$lang->testcase->needConfirm  = 'Story Changée';
$lang->testcase->bySearch     = 'Rechercher';
$lang->testcase->unexecuted   = 'En Attente';

$lang->testcase->lblStory       = 'Story liée';
$lang->testcase->lblLastEdited  = 'Modifié par';
$lang->testcase->lblTypeValue   = 'Valeur Type';
$lang->testcase->lblStageValue  = 'Valeur Phase';
$lang->testcase->lblStatusValue = 'Valeur Statut';

$lang->testcase->legendBasicInfo   = 'Infos de Base';
$lang->testcase->legendAttach      = 'Fichiers';
$lang->testcase->legendLinkBugs    = 'Bugs';
$lang->testcase->legendOpenAndEdit = 'Créer/Editer';
$lang->testcase->legendComment     = 'Commentaire';
$lang->testcase->legendOther       = 'Other Related';

$lang->testcase->confirmDelete         = 'Voulez-vous supprimer ce CasTest ?';
$lang->testcase->confirmBatchDelete    = 'Voulez-vous supprimer des Castests par Lot ?';
$lang->testcase->ditto                 = 'Idem';
$lang->testcase->dittoNotice           = "This Case is not linked to the {$lang->productCommon} as the last one is!";
$lang->testcase->confirmUnlinkTesttask = 'The case [%s] is already associated in the testtask order of the previous branch/platform, after adjusting the branch/platform, it will be removed from the test list of the previous branch/platform, please confirm whether to continue to modify.';

$lang->testcase->autoList['']     = '';
$lang->testcase->autoList['auto'] = 'Yes';
$lang->testcase->autoList['no']   = 'No';

$lang->testcase->reviewList[0] = '否';
$lang->testcase->reviewList[1] = '是';

$lang->testcase->priList[3] = 3;
$lang->testcase->priList[1] = 1;
$lang->testcase->priList[2] = 2;
$lang->testcase->priList[4] = 4;

/* Define the types. */
$lang->testcase->typeList['']            = '';
$lang->testcase->typeList['unit']        = 'Unit';
$lang->testcase->typeList['interface']   = 'Interface';
$lang->testcase->typeList['feature']     = 'Fonctionnalité';
$lang->testcase->typeList['install']     = 'Installation';
$lang->testcase->typeList['config']      = 'Configuration';
$lang->testcase->typeList['performance'] = 'Performance';
$lang->testcase->typeList['security']    = 'Sécurité';
$lang->testcase->typeList['other']       = 'Autre';

$lang->testcase->stageList['']           = '';
$lang->testcase->stageList['unittest']   = 'Test Unitaire';
$lang->testcase->stageList['feature']    = 'Function Testing';
$lang->testcase->stageList['intergrate'] = 'Integration Testing';
$lang->testcase->stageList['system']     = 'Test Système';
$lang->testcase->stageList['smoke']      = 'Test de Charge';
$lang->testcase->stageList['bvt']        = 'Vérification Build (BVT)';

$lang->testcase->reviewResultList['']        = '';
$lang->testcase->reviewResultList['pass']    = 'Réussi';
$lang->testcase->reviewResultList['clarify'] = 'à Clarifier';

$lang->testcase->statusList['']            = '';
$lang->testcase->statusList['wait']        = 'En Attente';
$lang->testcase->statusList['normal']      = 'Normal';
$lang->testcase->statusList['blocked']     = 'Bloqué';
$lang->testcase->statusList['investigate'] = 'Analyse';

$lang->testcase->resultList['n/a']     = 'Ignorer';
$lang->testcase->resultList['pass']    = 'Réussite';
$lang->testcase->resultList['fail']    = 'Echec';
$lang->testcase->resultList['blocked'] = 'Bloqué';

$lang->testcase->buttonToList = 'Retour';

$lang->testcase->whichLine        = 'Line No.%s : ';
$lang->testcase->stepsEmpty       = 'Step %s cannot be empty.';
$lang->testcase->errorEncode      = 'Pas de données. Veuillez sélectionner le bon encodage et télécharger à nouveau !';
$lang->testcase->noFunction       = "Iconv et mb_convert_encoding non trouvés. Vous ne pouvez pas convertir les données dans l'encodage souhaité !";
$lang->testcase->noRequire        = "Ligne %s a“%s ”qui est un champ obligatoire et ne doit pas être vide.";
$lang->testcase->noRequireTip     = "“%s”is a required field and it should not be blank.";
$lang->testcase->noLibrary        = "Aucune library n'existe. Créez-en une pour commencer.";
$lang->testcase->mustChooseResult = 'La Validation du résultat est nécessaire.';
$lang->testcase->noModule         = "<div>Vous n'avez aucun modules.</div><div>Gérer les modules maintenant.</div>";
$lang->testcase->noCase           = "Aucun CasTest pour l'instant. ";
$lang->testcase->importedCases    = 'Le cas d\'utilisation avec l\'ID %s est déjà importé dans le même module, il est donc ignoré.';
$lang->testcase->importedFromLib  = '%s items imported successfully: %s.';
$lang->testcase->noStep           = 'No steps yet.';

$lang->testcase->searchStories = 'Rechercher des stories';
$lang->testcase->selectLib     = 'Sélectionner Library';
$lang->testcase->selectLibAB   = 'Sélectionner Library';

$lang->testcase->action = new stdclass();
$lang->testcase->action->fromlib               = array('main' => '$date, importé par <strong>$actor</strong> depuis <strong>$extra</strong>.');
$lang->testcase->action->reviewed              = array('main' => '$date, enregistré par <strong>$actor</strong> et le résultat de validation est <strong>$extra</strong>.', 'extra' => 'reviewResultList');
$lang->testcase->action->linked2project        = array('main' => '$date, linked ' . $lang->projectCommon . ' by <strong>$actor</strong> to <strong>$extra</strong>.');
$lang->testcase->action->unlinkedfromproject   = array('main' => '$date, removed by <strong>$actor</strong> from <strong>$extra</strong>.');
$lang->testcase->action->linked2execution      = array('main' => '$date, linked ' . $lang->executionCommon . ' by  <strong>$actor</strong> to <strong>$extra</strong>.');
$lang->testcase->action->unlinkedfromexecution = array('main' => '$date, removed by <strong>$actor</strong> from <strong>$extra</strong>.');

$lang->testcase->featureBar['browse']['all']         = $lang->testcase->allCases;
$lang->testcase->featureBar['browse']['wait']        = 'En Attente';
$lang->testcase->featureBar['browse'][]              = '-';
$lang->testcase->featureBar['browse']['needconfirm'] = $lang->testcase->needConfirm;

$lang->testcase->importXmind     = "Import Xmind";
$lang->testcase->exportXmind     = "Export Xmind";
$lang->testcase->exportFreeMind  = "Export FreeMind";
$lang->testcase->getXmindImport  = "Get Mindmap";
$lang->testcase->showXMindImport = "Display Mindmap";
$lang->testcase->saveXmindImport = "Save Mindmap";

$lang->testcase->xmindImport           = "Imort Xmind";
$lang->testcase->xmindExport           = "Export Xmind";
$lang->testcase->xmindImportEdit       = "Xmind Edit";
$lang->testcase->errorFileNotEmpty     = 'The uploaded file cannot be empty';
$lang->testcase->errorXmindUpload      = 'Upload failed';
$lang->testcase->errorFileFormat       = 'File format error';
$lang->testcase->moduleSelector        = 'Module Selection';
$lang->testcase->errorImportBadProduct = 'Product does not exist, import error';
$lang->testcase->errorSceneNotExist    = 'Scene [%d] not exists';
$lang->testcase->errorMindConfig       = "%s characteristic character can only be 1-10 letters.";

$lang->testcase->save  = 'Save';
$lang->testcase->close = 'Close';

$lang->testcase->xmindImportSetting = 'Import Characteristic Character Settings';
$lang->testcase->xmindExportSetting = 'Export Characteristic Character Settings';
$lang->testcase->xmindSettingTip    = 'After the feature characters are set, the Xmind theme can correspond to the ZenTao test case structure.';

$lang->testcase->settingModule = 'Module';
$lang->testcase->settingScene  = 'Scene';
$lang->testcase->settingCase   = 'Testcase';
$lang->testcase->settingPre    = 'Precondition';
$lang->testcase->settingPri    = 'Priority';
$lang->testcase->settingGroup  = 'Step Group';

$lang->testcase->jsLng = new stdclass();
$lang->testcase->jsLng->caseNotExist = 'The test case in the imported file was not recognized and the import failed';
$lang->testcase->jsLng->saveFail     = 'Save failed';
$lang->testcase->jsLng->set2Scene    = 'Set as Scene';
$lang->testcase->jsLng->set2Testcase = 'Set as Testcase';
$lang->testcase->jsLng->clearSetting = 'Clear Settings';
$lang->testcase->jsLng->setModule    = 'Set scene module';
$lang->testcase->jsLng->pickModule   = 'Please select a module';
$lang->testcase->jsLng->clearBefore  = 'Clear previous scenes';
$lang->testcase->jsLng->clearAfter   = 'Clear the following scenes';
$lang->testcase->jsLng->clearCurrent = 'Clear the current scene';
$lang->testcase->jsLng->removeGroup  = 'Remove Group';
$lang->testcase->jsLng->set2Group    = 'Set as Group';

$lang->testcase->exportTemplet = 'Export Template';

$lang->testcase->createScene      = "Add Scene";
$lang->testcase->changeScene      = "Drag to change the scene which it belongs";
$lang->testcase->batchChangeScene = "Batch change scene";
$lang->testcase->updateOrder      = "Drag Sort";
$lang->testcase->differentProduct = "Different product";

$lang->testcase->newScene           = "Add Scene";
$lang->testcase->sceneTitle         = 'Scene Name';
$lang->testcase->parentScene        = "Parent Scene";
$lang->testcase->scene              = "Scene";
$lang->testcase->summary            = 'Total %d Top Scene，%d Independent test case.';
$lang->testcase->summaryScene       = 'Total %d Top Scene.';
$lang->testcase->failSummary        = 'Total %d Cases, which did not pass %d.';
$lang->testcase->checkedSummary     = '{checked} checked test cases, {run} run.';
$lang->testcase->failCheckedSummary = '%total% checked test cases，%fail% run fail。';
$lang->testcase->deleteScene        = 'Delete Scene';
$lang->testcase->editScene          = 'Edit Scene';
$lang->testcase->hasChildren        = 'This scene has sub scene or test cases. Do you want to delete them all?';
$lang->testcase->confirmDeleteScene = 'Are you sure you want to delete the scene: \"%s\"?';
$lang->testcase->sceneb             = 'Scene';
$lang->testcase->onlyAutomated      = 'Only Automated';
$lang->testcase->onlyScene          = 'Only Scene';
$lang->testcase->iScene             = 'Scene';
$lang->testcase->generalTitle       = 'Title';
$lang->testcase->noScene            = 'No Scene';
$lang->testcase->rowIndex           = 'Row Index';
$lang->testcase->nestTotal          = 'nest total';
$lang->testcase->normal             = 'normal';

/* Translation for drag modal message box. */
$lang->testcase->dragModalTitle       = 'Drag and drop operation selection';
$lang->testcase->dragModalDesc        = 'There are two possible scenarios for the current operation:';
$lang->testcase->dragModalOrder       = '1) Adjust sorting';
$lang->testcase->dragModalScene       = '2) Change the scene to which the module belongs and also change it to the module of the target scene';
$lang->testcase->dragModalAction      = 'Please select the action you want to perform';
$lang->testcase->dragModalChangeScene = 'Change its scene';
$lang->testcase->dragModalChangeOrder = 'Reorder';

$lang->testcase->confirmBatchDeleteSceneCase = 'Are you sure you want to delete these scene or test cases in batch?';

$lang->scene = new stdclass();
$lang->scene->product = 'Product';
$lang->scene->branch  = 'Branch';
$lang->scene->module  = 'Module';
$lang->scene->parent  = 'Parent Scene';
$lang->scene->title   = 'Scene Name';
$lang->scene->noCase  = 'No case';
