<?php
global $config;

$lang->custom->common               = 'Personnalisation';
$lang->custom->id                   = 'ID';
$lang->custom->index                = 'Accueil';
$lang->custom->set                  = 'Personnaliser';
$lang->custom->restore              = 'Réinitialiser';
$lang->custom->key                  = 'Clé';
$lang->custom->value                = 'Valeur';
$lang->custom->flow                 = 'Concept';
$lang->custom->working              = 'Mode';
$lang->custom->select               = 'Choix du Concept';
$lang->custom->branch               = 'Multi-Branches';
$lang->custom->owner                = 'Propriétaire';
$lang->custom->module               = 'Module';
$lang->custom->section              = 'Section';
$lang->custom->lang                 = 'Langue';
$lang->custom->setPublic            = 'Set Public';
$lang->custom->required             = 'Champ Obligatoire';
$lang->custom->score                = 'Point';
$lang->custom->timezone             = 'Timezone';
$lang->custom->scoreReset           = 'Réinit Points';
$lang->custom->scoreTitle           = 'Fonctionnalité des Points';
$lang->custom->product              = $lang->productCommon;
$lang->custom->convertFactor        = 'Convert factor';
$lang->custom->region               = 'Interval';
$lang->custom->tips                 = 'Tips';
$lang->custom->setTips              = 'Set Tips';
$lang->custom->isRange              = 'Is Target Control';
$lang->custom->concept              = "Concept";
$lang->custom->URStory              = "User requirements";
$lang->custom->SRStory              = "Software requirements";
$lang->custom->epic                 = "Epic";
$lang->custom->default              = "Default";
$lang->custom->mode                 = "Mode";
$lang->custom->scrumStory           = "Story";
$lang->custom->waterfallCommon      = "Waterfall";
$lang->custom->buildin              = "Buildin";
$lang->custom->editStoryConcept     = "Edit Story Concept";
$lang->custom->setStoryConcept      = "Set Story Concept";
$lang->custom->setDefaultConcept    = "Set Default Concept";
$lang->custom->browseStoryConcept   = "List of story concepts";
$lang->custom->deleteStoryConcept   = "Delete story Concept";
$lang->custom->URConcept            = "UR Concept";
$lang->custom->SRConcept            = "SR Concept";
$lang->custom->reviewRule           = 'Review Rules';
$lang->custom->switch               = "Switch";
$lang->custom->oneUnit              = "One {$lang->hourCommon}";
$lang->custom->convertRelationTitle = "Please firstly set the conversion factor from {$lang->hourCommon} to %s";
$lang->custom->superReviewers       = "Super Reviewer";
$lang->custom->kanban               = "Kanban";
$lang->custom->allUsers             = 'All Users';
$lang->custom->account              = 'Users';
$lang->custom->role                 = 'Role';
$lang->custom->dept                 = 'Dept';
$lang->custom->code                 = $lang->code;
$lang->custom->setCode              = 'Activer ou Désactiver le Code';

if($config->systemMode == 'new') $lang->custom->execution = 'Execution';
if($config->systemMode == 'classic' || !$config->systemMode) $lang->custom->execution = 'Execution';

$lang->custom->unitList['efficiency'] = 'Working Hours/';
$lang->custom->unitList['manhour']    = 'Man-hour/';
$lang->custom->unitList['cost']       = 'Yuan/Hour';
$lang->custom->unitList['hours']      = 'Hours';
$lang->custom->unitList['days']       = 'Days';
$lang->custom->unitList['loc']        = 'KLOC';

$lang->custom->tipProgressList['SPI'] = 'Schedule Performance Index(SPI)';
$lang->custom->tipProgressList['SV']  = 'Schedule Variance(SV%)';

$lang->custom->tipCostList['CPI'] = 'Cost Performed Index(CPI)';
$lang->custom->tipCostList['CV']  = 'Cost Variance(CV%)';

$lang->custom->tipRangeList[0]  = 'No';
$lang->custom->tipRangeList[1]  = 'Yes';

$lang->custom->regionMustNumber    = 'The interval must be a number!';
$lang->custom->tipNotEmpty         = 'The prompt can not be empty!';
$lang->custom->currencyNotEmpty    = 'You have to select one currency at least.';
$lang->custom->defaultNotEmpty     = 'The default currency can not be empty';
$lang->custom->convertRelationTips = "After converting {$lang->hourCommon} to %s, the historical data will be converted uniformly to %s.";
$lang->custom->saveTips            = 'After clicking save, the current %s will be used as the default estimation unit';

$lang->custom->numberError = 'The interval must be greater than zero!';

$lang->custom->closedExecution = 'Closed ' . $lang->executionCommon;
$lang->custom->closedKanban    = 'Closed ' . $lang->custom->kanban;
$lang->custom->closedProduct   = 'Closed ' . $lang->productCommon;

if($config->systemMode == 'new') $lang->custom->object['project']   = 'Project';
$lang->custom->object['product']   = $lang->productCommon;
$lang->custom->object['execution'] = $lang->custom->execution;
$lang->custom->object['kanban']    = $lang->custom->kanban;
$lang->custom->object['story']     = 'Story';
$lang->custom->object['task']      = 'Tâche';
$lang->custom->object['bug']       = 'Bug';
$lang->custom->object['testcase']  = 'CasTest';
$lang->custom->object['testtask']  = 'Build';
$lang->custom->object['todo']      = 'Agenda';
$lang->custom->object['user']      = 'Utilisateur';
$lang->custom->object['block']     = 'Bloc';
$lang->custom->object['flow']      = '流程';
$lang->custom->object['score']     = '积分';

if($config->systemMode == 'new') $lang->custom->menuOrder[5]  = 'project';
$lang->custom->menuOrder[10] = 'product';
$lang->custom->menuOrder[15] = 'execution';
$lang->custom->menuOrder[20] = 'kanban';
$lang->custom->menuOrder[25] = 'story';
$lang->custom->menuOrder[30] = 'task';
$lang->custom->menuOrder[35] = 'bug';
$lang->custom->menuOrder[40] = 'testcase';
$lang->custom->menuOrder[45] = 'testtask';
$lang->custom->menuOrder[50] = 'todo';
$lang->custom->menuOrder[55] = 'user';
$lang->custom->menuOrder[60] = 'block';
$lang->custom->menuOrder[65] = 'flow';
$lang->custom->menuOrder[70] = 'score';

$lang->custom->dividerMenu  = ',story,todo,block,';
$lang->custom->separatePage = ',execution,product,kanban,flow,score,';

$lang->custom->block = new stdclass();
$lang->custom->block->fields['closed'] = 'Bloc Fermé';

$lang->custom->project = new stdClass();
$lang->custom->project->currencySetting    = 'Currency Setting';
$lang->custom->project->defaultCurrency    = 'Default Currency';
$lang->custom->project->fields['unitList'] = 'Unit List';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['categoryList']     = 'Category';
$lang->custom->story->fields['priList']          = 'Priorité';
$lang->custom->story->fields['sourceList']       = 'Source';
$lang->custom->story->fields['reasonList']       = 'Raison Fermeture';
$lang->custom->story->fields['stageList']        = 'Phase';
$lang->custom->story->fields['statusList']       = 'Statut';
$lang->custom->story->fields['reviewRules']      = 'Review Rules';
$lang->custom->story->fields['reviewResultList'] = 'Valider Résultats';
$lang->custom->story->fields['review']           = 'Validation Requise';

$lang->custom->task = new stdClass();
$lang->custom->task->fields['priList']    = 'Priorité';
$lang->custom->task->fields['typeList']   = 'Type';
$lang->custom->task->fields['reasonList'] = 'Raison Fermeture';
$lang->custom->task->fields['statusList'] = 'Statut';
$lang->custom->task->fields['hours']      = 'Effort';

$lang->custom->bug = new stdClass();
$lang->custom->bug->fields['priList']        = 'Priorité';
$lang->custom->bug->fields['severityList']   = 'Sévérité';
$lang->custom->bug->fields['osList']         = 'OS';
$lang->custom->bug->fields['browserList']    = 'Browser';
$lang->custom->bug->fields['typeList']       = 'Type';
$lang->custom->bug->fields['resolutionList'] = 'Résolution';
$lang->custom->bug->fields['statusList']     = 'Statut';
$lang->custom->bug->fields['longlife']       = 'Jours Calage';

$lang->custom->testcase = new stdClass();
$lang->custom->testcase->fields['priList']    = 'Priorité';
$lang->custom->testcase->fields['typeList']   = 'Type';
$lang->custom->testcase->fields['stageList']  = 'Phase';
$lang->custom->testcase->fields['resultList'] = 'Résultat';
$lang->custom->testcase->fields['statusList'] = 'Statut';
$lang->custom->testcase->fields['review']     = 'Validation Requise';

$lang->custom->testtask = new stdClass();
$lang->custom->testtask->fields['statusList'] = 'Statut';
$lang->custom->testtask->fields['typeList']   = 'Type de test';
$lang->custom->testtask->fields['priList']    = 'Priorité';

$lang->custom->todo = new stdClass();
$lang->custom->todo->fields['priList']    = 'Priorité';
$lang->custom->todo->fields['typeList']   = 'Type';
$lang->custom->todo->fields['statusList'] = 'Statut';

$lang->custom->user = new stdClass();
$lang->custom->user->fields['roleList']     = 'Rôle';
$lang->custom->user->fields['statusList']   = 'Statut';
$lang->custom->user->fields['contactField'] = 'Contact';
$lang->custom->user->fields['deleted']      = 'Parti';

$lang->custom->currentLang = 'Langage Courant';
$lang->custom->allLang     = 'Toutes les Langues';

$lang->custom->confirmRestore = 'Voulez-vous réinitialiser ?';

$lang->custom->notice = new stdclass();
$lang->custom->notice->userFieldNotice     = 'Contrôlez si les champs ci-dessus sont affichés sur la page utilisateur. Laissez-le vide pour tout afficher.';
$lang->custom->notice->canNotAdd           = "Il sera calculé, donc la personnalisation n'est pas activée.";
$lang->custom->notice->forceReview         = '%s un examen est requis pour les valideurs sélectionnés.';
$lang->custom->notice->forceNotReview      = "%s un examen n'est pas requis pour les valideurs sélectionnés.";
$lang->custom->notice->longlife            = 'Définir les bugs bloqués.';
$lang->custom->notice->invalidNumberKey    = 'La clé devrait être =< 255.';
$lang->custom->notice->invalidStringKey    = 'La clé devrait être composée de miniscules, de chiffres et du caractère souligné.';
$lang->custom->notice->cannotSetTimezone   = "date_default_timezone_set n'existe pas ou est désactivé. Timezone ne peut pas être fixée.";
$lang->custom->notice->noClosedBlock       = "Vous n'avez aucun bloc fermé définitivement.";
$lang->custom->notice->required            = 'Le champ sélectionné est obligatoire.';
$lang->custom->notice->conceptResult       = 'Selon votre préférence, <b> %s-%s </b> peut être fixé pour vous. Utilisez <b>%s</b> + <b> %s</b>。';
$lang->custom->notice->conceptPath         = 'Allez à Admin -> Custom -> Concept pour le paramétrer.';
$lang->custom->notice->readOnlyOfProduct   = 'If Change Forbidden, any change on stories, bugs, cases, efforts, releases and plans of the closed product is also forbidden.';
$lang->custom->notice->readOnlyOfExecution = "If Change Forbidden, any change on tasks, builds, efforts and stories of the closed {$lang->executionCommon} is also forbidden.";
$lang->custom->notice->readOnlyOfKanban    = "If Change Forbidden, any change on kanban card and related operations of {$lang->custom->kanban} is also forbidden.";
$lang->custom->notice->URSREmpty           = 'Custom requirement name can not be empty!';
$lang->custom->notice->valueEmpty          = 'Value can not be empty!';
$lang->custom->notice->confirmDelete       = 'Are you sure you want to delete it?';
$lang->custom->notice->confirmReviewCase   = 'Set the case in Wait to Normal?';
$lang->custom->notice->storyReviewTip      = 'After selecting by individual, position, and department, take the union of these three filters. ';
$lang->custom->notice->selectAllTip        = 'After selecting all people, the reviewers will be emptied and grayed out while hiding their positions and departments.';
$lang->custom->notice->repeatKey           = 'Repeat Key %s';
$lang->custom->notice->readOnlyOfCode      = "Le code est un terme de gestion utilisé pour la confidentialité ou comme alias. Lorsque la gestion du code est activée, le produit, le projet et l'exécution dans le système afficheront les informations de code sur les pages de création, de modification, de détails et de liste.";

$lang->custom->notice->indexPage['product'] = "ZenTao 8.2+ possède une page d'accueil. Voulez-vous consulter la page d'accueil du produit ?";
$lang->custom->notice->indexPage['project'] = "ZenTao 8.2+ possède une page d'accueil. Voulez-vous consulter la page d'accueil du produit ?";
$lang->custom->notice->indexPage['qa']      = "ZenTao 8.2+ possède une FAQ. Voulez-vous consulter la FAQ ?";

$lang->custom->notice->invalidStrlen['ten']        = 'La clé devrait être <= 10 caractères.';
$lang->custom->notice->invalidStrlen['fifteen']    = 'The key should be <= 15 characters.';
$lang->custom->notice->invalidStrlen['twenty']     = 'La clé devrait être <= 20 caractères.';
$lang->custom->notice->invalidStrlen['thirty']     = 'La clé devrait être <= 30 caractères.';
$lang->custom->notice->invalidStrlen['twoHundred'] = 'La clé devrait être <= 225 caractères.';

$lang->custom->storyReview    = 'Validation';
$lang->custom->forceReview    = 'Validation Requise';
$lang->custom->forceNotReview = 'Aucune Validation Requise';
$lang->custom->reviewList[1]  = 'On';
$lang->custom->reviewList[0]  = 'Off';

$lang->custom->deletedList[1] = 'Montrer';
$lang->custom->deletedList[0] = 'Cacher';

$lang->custom->workingHours   = 'Heures/Jour';
$lang->custom->weekend        = 'Weekend';
$lang->custom->weekendList[2] = '2-Jour';
$lang->custom->weekendList[1] = '1-Jour';

global $config;
if($config->systemMode == 'classic')
{
    $lang->custom->sprintConceptList[0] = 'Product - Project';
    $lang->custom->sprintConceptList[1] = 'Product - Iteration';
    $lang->custom->sprintConceptList[2] = 'Product - Sprint';
}
else
{
    $lang->custom->sprintConceptList[0] = 'Product - Project';
    $lang->custom->sprintConceptList[1] = 'Product - Iteration';
}

$lang->custom->workingList['full'] = 'Application Lifecycle Management';

$lang->custom->menuTip          = "Cliquez pour montrer/cacher le menu. Déplacez pour changer l'ordre d'affichage.";
$lang->custom->saveFail         = 'Echec de la sauvegarde !';
$lang->custom->page             = ' Page';
$lang->custom->changeClassicTip = 'The Program module will be hidden, if you switch to the classic mode.';
$lang->custom->changeModeTips   = 'Historical deleted data does not participate in the data merging process. After switching the mode, it will not support restoration. Please know.';

$lang->custom->scoreStatus[1] = 'On';
$lang->custom->scoreStatus[0] = 'Off';

$lang->custom->CRProduct[1] = 'Change Allowed';
$lang->custom->CRProduct[0] = 'Change Forbidden';

$lang->custom->CRExecution[1] = 'Change Allowed';
$lang->custom->CRExecution[0] = 'Change Forbidden';

$lang->custom->CRKanban[1] = 'Change Allowed';
$lang->custom->CRKanban[0] = 'Change Forbidden';

$lang->custom->moduleName['product']     = $lang->productCommon;
$lang->custom->moduleName['productplan'] = 'Plan';
$lang->custom->moduleName['execution']   = $lang->custom->execution;

$lang->custom->conceptQuestions['overview']   = "1. Quelle combinaison de gestion convient le mieux à votre entreprise ?";
$lang->custom->conceptQuestions['URAndSR']    = "2. Do you want to use the concept of {$lang->URCommon} and {$lang->SRCommon} in ZenTao?";
$lang->custom->conceptQuestions['storypoint'] = "3. Which of the following units is your company using for scale estimation?";

$lang->custom->conceptOptions             = new stdclass;
$lang->custom->conceptOptions->story      = array();
$lang->custom->conceptOptions->story['0'] = 'Exigence';
$lang->custom->conceptOptions->story['1'] = 'Story';

$lang->custom->conceptOptions->URAndSR = array();
$lang->custom->conceptOptions->URAndSR['1'] = 'Yes';
$lang->custom->conceptOptions->URAndSR['0'] = 'No';

$lang->custom->conceptOptions->hourPoint      = array();
$lang->custom->conceptOptions->hourPoint['0'] = 'Heures';
$lang->custom->conceptOptions->hourPoint['1'] = 'Story Point';
$lang->custom->conceptOptions->hourPoint['2'] = 'Function Point';

$lang->custom->scrum = new stdclass();
$lang->custom->scrum->setConcept = 'Set concept';

$lang->custom->reviewRules['allpass']  = 'All passed';
$lang->custom->reviewRules['halfpass'] = 'More than half passed';
