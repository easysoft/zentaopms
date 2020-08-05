<?php
$lang->custom->common     = 'Personnalisation';
$lang->custom->index      = 'Accueil';
$lang->custom->set        = 'Personnaliser';
$lang->custom->restore    = 'Réinitialiser';
$lang->custom->key        = 'Clé';
$lang->custom->value      = 'Valeur';
$lang->custom->flow       = 'Concept';
$lang->custom->working    = 'Mode';
$lang->custom->select     = 'Choix du Concept';
$lang->custom->branch     = 'Multi-Branches';
$lang->custom->owner      = 'Propriétaire';
$lang->custom->module     = 'Module';
$lang->custom->section    = 'Section';
$lang->custom->lang       = 'Langue';
$lang->custom->setPublic  = 'Set Public';
$lang->custom->required   = 'Champ Obligatoire';
$lang->custom->score      = 'Point';
$lang->custom->timezone   = 'Timezone';
$lang->custom->scoreReset = 'Réinit Points';
$lang->custom->scoreTitle = 'Fonctionnalité des Points';

$lang->custom->object['story']    = 'Story';
$lang->custom->object['task']     = 'Tâche';
$lang->custom->object['bug']      = 'Bug';
$lang->custom->object['testcase'] = 'CasTest';
$lang->custom->object['testtask'] = 'Build';
$lang->custom->object['todo']     = 'Agenda';
$lang->custom->object['user']     = 'Utilisateur';
$lang->custom->object['block']    = 'Bloc';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['priList']          = 'Priorité';
$lang->custom->story->fields['sourceList']       = 'Source';
$lang->custom->story->fields['reasonList']       = 'Raison Fermeture';
$lang->custom->story->fields['stageList']        = 'Phase';
$lang->custom->story->fields['statusList']       = 'Statut';
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
$lang->custom->testtask->fields['priList']    = 'Priorité';
$lang->custom->testtask->fields['statusList'] = 'Statut';

$lang->custom->todo = new stdClass();
$lang->custom->todo->fields['priList']    = 'Priorité';
$lang->custom->todo->fields['typeList']   = 'Type';
$lang->custom->todo->fields['statusList'] = 'Statut';

$lang->custom->user = new stdClass();
$lang->custom->user->fields['roleList']     = 'Rôle';
$lang->custom->user->fields['statusList']   = 'Statut';
$lang->custom->user->fields['contactField'] = 'Contact';
$lang->custom->user->fields['deleted']      = 'Parti';

$lang->custom->system = array('flow', 'working', 'required', 'score');

$lang->custom->block->fields['closed'] = 'Bloc Fermé';

$lang->custom->currentLang = 'Langage Courant';
$lang->custom->allLang     = 'Toutes les Langues';

$lang->custom->confirmRestore = 'Voulez-vous réinitialiser ?';

$lang->custom->notice = new stdclass();
$lang->custom->notice->userFieldNotice   = 'Contrôlez si les champs ci-dessus sont affichés sur la page utilisateur. Laissez-le vide pour tout afficher.';
$lang->custom->notice->canNotAdd         = "Il sera calculé, donc la personnalisation n'est pas activée.";
$lang->custom->notice->forceReview       = '%s un examen est requis pour les valideurs sélectionnés.';
$lang->custom->notice->forceNotReview    = "%s un examen n'est pas requis pour les valideurs sélectionnés.";
$lang->custom->notice->longlife          = 'Définir les bugs bloqués.';
$lang->custom->notice->invalidNumberKey  = 'La clé devrait être =< 255.';
$lang->custom->notice->invalidStringKey  = 'La clé devrait être composée de miniscules, de chiffres et du caractère souligné.';
$lang->custom->notice->cannotSetTimezone = "date_default_timezone_set n'existe pas ou est désactivé. Timezone ne peut pas être fixée.";
$lang->custom->notice->noClosedBlock     = "Vous n'avez aucun bloc fermé définitivement.";
$lang->custom->notice->required          = 'Le champ sélectionné est obligatoire.';
$lang->custom->notice->conceptResult     = 'Selon votre préférence, <b> %s-%s </b> peut être fixé pour vous. Utilisez <b>%s</b> + <b> %s</b>。';
$lang->custom->notice->conceptPath       = 'Allez à Admin -> Custom -> Concept pour le paramétrer.';

$lang->custom->notice->indexPage['product'] = "ZenTao 8.2+ possède une page d'accueil. Voulez-vous consulter la page d'accueil du produit ?";
$lang->custom->notice->indexPage['project'] = "ZenTao 8.2+ possède une page d'accueil. Voulez-vous consulter la page d'accueil du produit ?";
$lang->custom->notice->indexPage['qa']      = "ZenTao 8.2+ possède une FAQ. Voulez-vous consulter la FAQ ?";

$lang->custom->notice->invalidStrlen['ten']        = 'La clé devrait être <= 10 caractères.';
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

$lang->custom->productProject = new stdclass();
$lang->custom->productProject->relation['0_0'] = 'Product - Project';
$lang->custom->productProject->relation['0_1'] = 'Product - Iteration';
$lang->custom->productProject->relation['1_1'] = 'Project - Iteration';
$lang->custom->productProject->relation['0_2'] = 'Product - Sprint';
$lang->custom->productProject->relation['1_2'] = 'Project - Sprint';

$lang->custom->productProject->notice = 'Sélectionnez le concept qui correspond à votre équipe.';

$lang->custom->workingList['full']      = 'Application Lifecycle Management';
$lang->custom->workingList['onlyTest']  = 'Gestion de la Qualité';
$lang->custom->workingList['onlyStory'] = 'Gestion des Stories';
$lang->custom->workingList['onlyTask']  = 'Gestion des Tâches';

$lang->custom->menuTip  = "Cliquez pour montrer/cacher le menu. Déplacez pour changer l'ordre d'affichage.";
$lang->custom->saveFail = 'Echec de la sauvegarde !';
$lang->custom->page     = ' Page';

$lang->custom->scoreStatus[1] = 'On';
$lang->custom->scoreStatus[0] = 'Off';

$lang->custom->moduleName['product']     = $lang->productCommon;
$lang->custom->moduleName['productplan'] = 'Plan';
$lang->custom->moduleName['project']     = $lang->projectCommon;

$lang->custom->conceptQuestions['overview']         = "1. Quelle combinaison de gestion convient le mieux à votre entreprise ?";
$lang->custom->conceptQuestions['story']            = "2. Utilisez-vous le concept d'exigence ou de user story dans votre entreprise ?";
$lang->custom->conceptQuestions['requirementpoint'] = "3. Utilisez-vous des heures ou des points de fonction pour faire des estimations dans votre entreprise ?";
$lang->custom->conceptQuestions['storypoint']       = "3. Utilisez-vous des heures ou des points de Story pour faire des estimations dans votre entreprise ?";

$lang->custom->conceptOptions = new stdclass;

$lang->custom->conceptOptions->story = array();
$lang->custom->conceptOptions->story['0'] = 'Exigence';
$lang->custom->conceptOptions->story['1'] = 'Story';

$lang->custom->conceptOptions->hourPoint = array();
$lang->custom->conceptOptions->hourPoint['0'] = 'Heures';
$lang->custom->conceptOptions->hourPoint['1'] = 'Points de Story';
$lang->custom->conceptOptions->hourPoint['2'] = 'Points de Fonction';
