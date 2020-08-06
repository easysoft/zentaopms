<?php
/**
 * The testcase module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id: en.php 4966 2013-07-02 02:59:25Z wyd621@gmail.com $
 * @link        https://www.zentao.pm
 */
$lang->testcase->id               = 'ID';
$lang->testcase->product          = $lang->productCommon;
$lang->testcase->module           = 'Module';
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
$lang->testcase->subStatus        = 'Sous-statut';
$lang->testcase->steps            = 'Etape';
$lang->testcase->openedBy         = 'Créé par';
$lang->testcase->openedDate       = 'Créé le';
$lang->testcase->lastEditedBy     = 'Modifié par';
$lang->testcase->result           = 'Résultat';
$lang->testcase->real             = 'Détails';
$lang->testcase->keywords         = 'Tags';
$lang->testcase->files            = 'Fichiers';
$lang->testcase->linkCase         = 'CasTests liés';
$lang->testcase->linkCases        = 'Associer CasTests';
$lang->testcase->unlinkCase       = 'Dissocier CasTest';
$lang->testcase->stage            = 'Phase';
$lang->testcase->reviewedBy       = 'Validé par';
$lang->testcase->reviewedDate     = 'Validé le';
$lang->testcase->reviewResult     = 'Résultat validation';
$lang->testcase->reviewedByAB     = 'Validé par';
$lang->testcase->reviewedDateAB   = 'Validé le';
$lang->testcase->reviewResultAB   = 'Résultat validation';
$lang->testcase->forceNotReview   = 'Aucune Validation Requise';
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
$lang->testcase->deleted          = 'Deleted';
$lang->case = $lang->testcase;  // For dao checking using. Because 'case' is a php keywords, so the module name is testcase, table name is still case.

$lang->testcase->stepID      = 'ID';
$lang->testcase->stepDesc    = 'Etape';
$lang->testcase->stepExpect  = 'Résultat Attendu';
$lang->testcase->stepVersion = 'Version';

$lang->testcase->common                  = 'CasTest';
$lang->testcase->index                   = "Accueil CasTest";
$lang->testcase->create                  = "Ajout CasTest";
$lang->testcase->batchCreate             = "Ajouter par Lot";
$lang->testcase->delete                  = "Supprimer";
$lang->testcase->deleteAction            = "Supprimer CasTest";
$lang->testcase->view                    = "Détail CasTest";
$lang->testcase->review                  = "Doit être validé";
$lang->testcase->reviewAB                = "Validation";
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
$lang->testcase->batchCaseTypeChange     = "Changer Types par Lot";
$lang->testcase->browse                  = "Liste CasTests";
$lang->testcase->groupCase               = "Vue par Groupe";
$lang->testcase->import                  = "Importer";
$lang->testcase->importAction            = "Importer CasTest";
$lang->testcase->fileImport              = "Importer CSV";
$lang->testcase->importFromLib           = "Importer de la Library";
$lang->testcase->showImport              = "Voir Import";
$lang->testcase->exportTemplet           = "Exporter Modèle";
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

$lang->testcase->new = 'Nouveau';

$lang->testcase->num = 'Ligne CasTes:';

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

$lang->testcase->legendBasicInfo    = 'Infos de Base';
$lang->testcase->legendAttatch      = 'Fichiers';
$lang->testcase->legendLinkBugs     = 'Bugs';
$lang->testcase->legendOpenAndEdit  = 'Créer/Editer';
$lang->testcase->legendComment      = 'Commentaire';

$lang->testcase->summary            = "Total <strong>%s</strong> CasTests sur cette page, et <strong>%s</strong> castests ont été joués.";
$lang->testcase->confirmDelete      = 'Voulez-vous supprimer ce CasTest ?';
$lang->testcase->confirmBatchDelete = 'Voulez-vous supprimer des Castests par Lot ?';
$lang->testcase->ditto              = 'Idem';
$lang->testcase->dittoNotice        = "Ce CasTest n'est pas associé au Product alors que le précédent l'était !";

$lang->testcase->reviewList[0] = 'NON';
$lang->testcase->reviewList[1] = 'OUI';

$lang->testcase->priList[0] = '';
$lang->testcase->priList[3] = 3;
$lang->testcase->priList[1] = 1;
$lang->testcase->priList[2] = 2;
$lang->testcase->priList[4] = 4;

/* Define the types. */
$lang->testcase->typeList['']            = '';
$lang->testcase->typeList['feature']     = 'Fonctionnalité';
$lang->testcase->typeList['performance'] = 'Performance';
$lang->testcase->typeList['config']      = 'Configuration';
$lang->testcase->typeList['install']     = 'Installation';
$lang->testcase->typeList['security']    = 'Sécurité';
$lang->testcase->typeList['interface']   = 'Interface';
$lang->testcase->typeList['unit']        = 'Unit';
$lang->testcase->typeList['other']       = 'Autre';

$lang->testcase->stageList['']           = '';
$lang->testcase->stageList['unittest']   = 'Test Unitaire';
$lang->testcase->stageList['feature']    = 'Test Fonctionnel';      // recette MOE
$lang->testcase->stageList['intergrate'] = "Test d'Intégration";    // assemblage
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

$lang->testcase->errorEncode      = 'Pas de données. Veuillez sélectionner le bon encodage et télécharger à nouveau !';
$lang->testcase->noFunction       = "Iconv et mb_convert_encoding non trouvés. Vous ne pouvez pas convertir les données dans l'encodage souhaité !";
$lang->testcase->noRequire        = "Ligne %s a“%s ”qui est un champ obligatoire et ne doit pas être vide.";
$lang->testcase->noLibrary        = "Aucune library n'existe. Créez-en une pour commencer.";
$lang->testcase->mustChooseResult = 'La Validation du résultat est nécessaire.';
$lang->testcase->noModule         = "<div>Vous n'avez aucun modules.</div><div>Gérer les modules maintenant.</div>";
$lang->testcase->noCase           = "Aucun CasTest pour l'instant. ";

$lang->testcase->searchStories = 'Rechercher des stories';
$lang->testcase->selectLib     = 'Sélectionner Library';

$lang->testcase->action = new stdclass();
$lang->testcase->action->fromlib  = array('main' => '$date, importé par <strong>$actor</strong> depuis <strong>$extra</strong>.');
$lang->testcase->action->reviewed = array('main' => '$date, enregistré par <strong>$actor</strong> et le résultat de validation est <strong>$extra</strong>.', 'extra' => 'reviewResultList');

$lang->testcase->featureBar['browse']['all']         = $lang->testcase->allCases;
$lang->testcase->featureBar['browse']['wait']        = 'En Attente';
$lang->testcase->featureBar['browse']['needconfirm'] = $lang->testcase->needConfirm;
$lang->testcase->featureBar['browse']['group']       = '';
$lang->testcase->featureBar['browse']['suite']       = 'Cahier Recette';
$lang->testcase->featureBar['browse']['zerocase']    = '';
$lang->testcase->featureBar['groupcase']             = $lang->testcase->featureBar['browse'];
