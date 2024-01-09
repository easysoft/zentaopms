<?php
/**
 * The story module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: en.php 5141 2013-07-15 05:57:15Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
global $config;
$lang->story->create            = "Créer Story";

$lang->story->requirement       = 'UR';
$lang->story->story             = zget($lang, 'SRCommon', "Story");
$lang->story->createStory       = 'Create ' . $lang->story->story;
$lang->story->createRequirement = 'Create ' . $lang->story->requirement;
$lang->story->affectedStories   = "Affected {$lang->story->story}";

$lang->story->batchCreate        = "Créer par Lot";
$lang->story->change             = "Changer";
$lang->story->changed            = 'Changée';
$lang->story->assignTo           = 'Affecter';
$lang->story->review             = 'Valider';
$lang->story->submitReview       = "Submit Review";
$lang->story->recall             = 'Revoke';
$lang->story->recallChange       = 'Undo Changes';
$lang->story->recallAction       = 'Undo';
$lang->story->relation           = 'Relations';
$lang->story->needReview         = 'Validation requise';
$lang->story->batchReview        = 'Validation par lot';
$lang->story->edit               = "Editer Story";
$lang->story->editDraft          = "Modifier le projet";
$lang->story->batchEdit          = "Editer par Lot";
$lang->story->subdivide          = 'Décomposer';
$lang->story->subdivideSR        = $lang->SRCommon . 'Dé composer';
$lang->story->link               = 'Link';
$lang->story->unlink             = 'Unlink';
$lang->story->track              = 'Track';
$lang->story->trackAB            = 'Track';
$lang->story->processStoryChange = 'Confirm Story Change';
$lang->story->splitRequirent     = 'Décompose';
$lang->story->close              = 'Fermer';
$lang->story->batchClose         = 'Fermer par Lot';
$lang->story->activate           = 'Activer';
$lang->story->delete             = "Supprimer";
$lang->story->view               = "Détail Story";
$lang->story->setting            = "Paramétrage";
$lang->story->tasks              = "Tâches Associées";
$lang->story->bugs               = "Bugs Associés";
$lang->story->cases              = "CasTest Associés";
$lang->story->taskCount          = 'Tâches';
$lang->story->bugCount           = 'Bugs';
$lang->story->caseCount          = 'CasTests';
$lang->story->taskCountAB        = 'T';
$lang->story->bugCountAB         = 'B';
$lang->story->caseCountAB        = 'C';
$lang->story->linkStory          = 'Lier Requirement';
$lang->story->unlinkStory        = "Dissocier {$lang->URCommon}";
$lang->story->linkStoriesAB      = "Lier {$lang->SRCommon}";
$lang->story->linkRequirementsAB = "Lier {$lang->URCommon}";
$lang->story->export             = "Exporter Données";
$lang->story->zeroCase           = "Stories sans CasTests";
$lang->story->zeroTask           = "Seulement liste des stories sans tâches";
$lang->story->reportChart        = "Rapport";
$lang->story->copyTitle          = "Copier Titre";
$lang->story->batchChangePlan    = "Changer Plans par lot";
$lang->story->batchChangeBranch  = "Changer Branches par lot";
$lang->story->batchChangeStage   = "Changer Phases par lot";
$lang->story->batchAssignTo      = "Affecter par lot";
$lang->story->batchChangeModule  = "Changer Modules par lot";
$lang->story->viewAll            = "Voir Tout";
$lang->story->toTask             = 'Convert to Task';
$lang->story->batchToTask        = 'Batch Convert to Task';
$lang->story->convertRelations   = 'Convert Relations';
$lang->story->undetermined       = 'undetermined';
$lang->story->order              = 'Order';
$lang->story->saveDraft          = 'Save as draft';
$lang->story->doNotSubmit        = 'Do Not Submit';
$lang->story->currentBranch      = 'Current Branch';
$lang->story->twins              = 'Twins story';
$lang->story->relieved           = 'Relieved';
$lang->story->relievedTwins      = 'Relieved Twins';
$lang->story->loadAllStories     = 'Load all stories';
$lang->story->hasDividedTask     = 'has divided task';

$lang->story->editAction      = "Edit {$lang->SRCommon}";
$lang->story->changeAction    = "Changer Story";
$lang->story->assignAction    = 'Affecter Story';
$lang->story->reviewAction    = 'Valider Story';
$lang->story->subdivideAction = 'Décomposer Story';
$lang->story->closeAction     = 'Fermer Story';
$lang->story->activateAction  = 'Activer Story';
$lang->story->deleteAction    = "Supprimer Story";
$lang->story->exportAction    = "Exporter Story";
$lang->story->reportAction    = "Rapport de Story";

$lang->story->skipStory        = '%s is a parent story. It cannot be closed.';
$lang->story->closedStory      = 'Story %s is closed and will not be closed.';
$lang->story->batchToTaskTips  = "The closed {$lang->SRCommon} will not be converted into tasks.";
$lang->story->successToTask    = "Converted to task.";
$lang->story->storyRound       = '%s time estimation';
$lang->story->float            = "『 %s 』doit avoir des nombres positifs ou décimaux.";
$lang->story->saveDraftSuccess = 'Save as draft succeeded.';

$lang->story->changeSyncTip       = "The modification of this story will be synchronized to the following twin requirements";
$lang->story->syncTip             = "The twin story are synchronized except for {$lang->productCommon}, branch, module, plan, and stage. After the twin relationship is dissolved, they are no longer synchronized.";
$lang->story->relievedTip         = "The twin relationship cannot be restored after dissolving, the content of the demand is no longer synchronized, whether to dissolving?";
$lang->story->assignSyncTip       = "Both twin stories modify the assignor synchronously";
$lang->story->closeSyncTip        = "Twin stories are closed synchronously";
$lang->story->activateSyncTip     = "Twin stories are activated synchronously";
$lang->story->relievedTwinsTip    = "After {$lang->productCommon} adjustment, the twin relationship of this story will be automatically removed, and the story will no longer be synchronized. Do you want to save?";
$lang->story->batchEditTip        = "{$lang->SRCommon} %sis twin stories, and this operation has been filtered.";

$lang->story->id               = 'ID';
$lang->story->parent           = 'Parent';
$lang->story->product          = $lang->productCommon;
$lang->story->project          = $lang->projectCommon;
$lang->story->branch           = "Branche/Plateforme";
$lang->story->module           = 'Module';
$lang->story->moduleAB         = 'Module';
$lang->story->source           = 'De';
$lang->story->sourceNote       = 'Note';
$lang->story->fromBug          = 'Depuis Bug';
$lang->story->title            = 'Titre';
$lang->story->type             = "Story/Requirement";
$lang->story->category         = 'Category';
$lang->story->color            = 'Couleur';
$lang->story->toBug            = 'Vers Bug';
$lang->story->spec             = 'Description';
$lang->story->assign           = 'Affecter';
$lang->story->verify           = 'Acceptance';
$lang->story->pri              = 'Priorité';
$lang->story->estimate         = 'Estimation';
$lang->story->estimateAB       = 'Esti.(h)';
$lang->story->hour             = 'Heures';
$lang->story->consumed         = 'Long';
$lang->story->status           = 'Statut';
$lang->story->statusAB         = 'Statut';
$lang->story->subStatus        = 'Sous-statut';
$lang->story->stage            = 'Phase';
$lang->story->stageAB          = 'Phase';
$lang->story->stagedBy         = 'Fixé par';
$lang->story->mailto           = 'Mailto';
$lang->story->openedBy         = 'Créée par';
$lang->story->openedByAB       = 'Créer';
$lang->story->openedDate       = 'Date Création';
$lang->story->assignedTo       = 'Assignée à';
$lang->story->assignedToAB     = 'Attribuer';
$lang->story->assignedDate     = 'Date Assignation';
$lang->story->lastEditedBy     = 'Editée par';
$lang->story->lastEditedByAB   = 'Editée par';
$lang->story->lastEditedDate   = 'Date Edition';
$lang->story->closedBy         = 'Fermée par';
$lang->story->closedDate       = 'Date Fermeture';
$lang->story->closedReason     = 'Raison';
$lang->story->rejectedReason   = 'Raison du Rejet';
$lang->story->changedBy        = 'ChangedBy';
$lang->story->changedDate      = 'ChangedDate';
$lang->story->reviewedBy       = 'Validée par';
$lang->story->reviewer         = $lang->story->reviewedBy;
$lang->story->reviewers        = 'Reviewers';
$lang->story->reviewedDate     = 'Date Validation';
$lang->story->activatedDate    = 'Activated Date';
$lang->story->version          = 'Version';
$lang->story->feedbackBy       = 'From Name';
$lang->story->notifyEmail      = 'From Email';
$lang->story->plan             = 'Intégrée Plans';
$lang->story->planAB           = 'Plan';
$lang->story->comment          = 'Commentaire';
$lang->story->children         = "Enfant {$lang->SRCommon}";
$lang->story->childrenAB       = "C";
$lang->story->linkStories      = 'Stories Liées';
$lang->story->linkRequirements = "{$lang->URCommon} Lié es";
$lang->story->childStories     = 'Stories Décomposées';
$lang->story->duplicateStory   = 'Story Dupliquées';
$lang->story->reviewResult     = 'Résultat Validation';
$lang->story->reviewResultAB   = 'Résultats de lévaluation';
$lang->story->preVersion       = 'Dernière Version';
$lang->story->keywords         = 'Tags';
$lang->story->newStory         = "Continuer d'ajouter";
$lang->story->colorTag         = 'Couleur';
$lang->story->files            = 'Fichiers';
$lang->story->copy             = "Copier Story";
$lang->story->total            = "Stories Total";
$lang->story->draft            = 'Brouillon';
$lang->story->unclosed         = 'Non Fermées';
$lang->story->deleted          = 'Supprimé';
$lang->story->released         = 'Stories Versionnées';
$lang->story->URChanged        = 'Requirement Changed';
$lang->story->design           = 'Designs';
$lang->story->case             = 'Cases';
$lang->story->bug              = 'Bugs';
$lang->story->repoCommit       = 'Commits';
$lang->story->one              = 'One';
$lang->story->field            = 'Synchronized fields';
$lang->story->completeRate     = 'Completion Rate';
$lang->story->reviewed         = 'Reviewed';
$lang->story->toBeReviewed     = 'To Be Reviewed';
$lang->story->linkMR           = 'Related MRs';
$lang->story->linkCommit       = 'Related Commits';
$lang->story->URS              = 'User requirements';

$lang->story->ditto       = 'Idem';
$lang->story->dittoNotice = "This story is not linked to the same {$lang->productCommon} as the last one is!";

$lang->story->needNotReviewList[0] = 'Need Review';
$lang->story->needNotReviewList[1] = 'Need Not Review';

$lang->story->useList[0] = 'Oui';
$lang->story->useList[1] = 'Non';

$lang->story->statusList['']          = '';
$lang->story->statusList['draft']     = 'Brouillon';
$lang->story->statusList['reviewing'] = 'Examen en cours';
$lang->story->statusList['active']    = 'Active';
$lang->story->statusList['closed']    = 'Fermée';
$lang->story->statusList['changing']  = 'Changée';

if($config->systemMode == 'PLM')
{
    $lang->story->statusList['launched']   = 'Launched';
    $lang->story->statusList['developing'] = 'Developing';
}

$lang->story->stageList['']           = '';
$lang->story->stageList['wait']       = 'En Attente';
$lang->story->stageList['planned']    = 'Plannifée';
$lang->story->stageList['projected']  = 'Projetée';
$lang->story->stageList['developing'] = 'En Dév.';
$lang->story->stageList['developed']  = 'Développée';
$lang->story->stageList['testing']    = 'En Test';
$lang->story->stageList['tested']     = 'Testée';
$lang->story->stageList['verified']   = 'Acceptée';
$lang->story->stageList['released']   = 'Versionnée';
$lang->story->stageList['closed']     = 'Fermée';

$lang->story->reasonList['']           = '';
$lang->story->reasonList['done']       = 'Déjà Fait';
$lang->story->reasonList['subdivided'] = 'Décomposée';
$lang->story->reasonList['duplicate']  = 'Doublon';
$lang->story->reasonList['postponed']  = 'Reportée';
$lang->story->reasonList['willnotdo']  = "On ne fera pas";
$lang->story->reasonList['cancel']     = 'Annulée';
$lang->story->reasonList['bydesign']   = 'Du Design';
//$lang->story->reasonList['isbug']      = 'Bug!';

$lang->story->reviewResultList['']        = '';
$lang->story->reviewResultList['pass']    = 'Accepté';
$lang->story->reviewResultList['revert']  = 'Retour Arrière';
$lang->story->reviewResultList['clarify'] = 'A Clarifier';
$lang->story->reviewResultList['reject']  = 'Refusé';

$lang->story->reviewList[0] = 'Non';
$lang->story->reviewList[1] = 'Oui';

$lang->story->sourceList['']           = '';
$lang->story->sourceList['customer']   = 'Client';
$lang->story->sourceList['user']       = 'Utilisateur';
$lang->story->sourceList['po']         = $lang->productCommon . ' Owner';
$lang->story->sourceList['market']     = 'Marketing';
$lang->story->sourceList['service']    = 'Service Client';
$lang->story->sourceList['operation']  = 'Opérations';
$lang->story->sourceList['support']    = 'Support';
$lang->story->sourceList['competitor'] = 'Concurrence';
$lang->story->sourceList['partner']    = 'Partenaire';
$lang->story->sourceList['dev']        = 'Equipe de Dev';
$lang->story->sourceList['tester']     = 'Equipe de Test';
$lang->story->sourceList['bug']        = 'Bug';
$lang->story->sourceList['forum']      = 'Forum';
$lang->story->sourceList['other']      = 'Autre';

$lang->story->priList[0] = '';
$lang->story->priList[1] = '1';
$lang->story->priList[2] = '2';
$lang->story->priList[3] = '3';
$lang->story->priList[4] = '4';

$lang->story->changeList = array();
$lang->story->changeList['no']  = 'Cancel';
$lang->story->changeList['yes'] = 'Confirm';

$lang->story->legendBasicInfo      = 'Infos de Base';
$lang->story->legendLifeTime       = 'Vie de la Story ';
$lang->story->legendRelated        = 'Info Connexes';
$lang->story->legendMailto         = 'Mailto';
$lang->story->legendAttach         = 'Fichiers';
$lang->story->legendProjectAndTask = $lang->executionCommon . ' et Tâches';
$lang->story->legendBugs           = 'Bugs Liés';
$lang->story->legendFromBug        = 'du Bug';
$lang->story->legendCases          = 'CasTests Liés';
$lang->story->legendBuilds         = 'Builds Lié s';
$lang->story->legendReleases       = 'Releases Lié s';
$lang->story->legendLinkStories    = 'Stories Liées';
$lang->story->legendChildStories   = 'Sous-Stories';
$lang->story->legendSpec           = 'Description';
$lang->story->legendVerify         = 'Acceptance';
$lang->story->legendMisc           = 'Divers';
$lang->story->legendInformation    = 'Story Information';

$lang->story->lblChange   = 'Changer';
$lang->story->lblReview   = 'Valider';
$lang->story->lblActivate = 'Activer';
$lang->story->lblClose    = 'Fermer';
$lang->story->lblTBC      = 'Tâche/Bug/CasTest';

$lang->story->checkAffection       = 'Influence';
$lang->story->affectedProjects     = "{$lang->project->common}s/{$lang->execution->common}s";
$lang->story->affectedBugs         = 'Bugs';
$lang->story->affectedCases        = 'CasTests';
$lang->story->affectedTwins        = 'des jumeaux';

$lang->story->specTemplate         = "En tant que < type utilisateur >, je veux < différents objectifs > pour < différentes raisons >.";
$lang->story->needNotReview        = 'Aucune Validation Requise';
$lang->story->successSaved         = "Story est sauvegardée !";
$lang->story->confirmDelete        = "Voulez-vous vraiment supprimer cette story ?";
$lang->story->confirmRecall        = "Do you want to recall this story?";
$lang->story->errorEmptyChildStory = '『Decomposed Stories』ne peuvent être vides.';
$lang->story->errorNotSubdivide    = "Si le statut n'est pas actif, ou l'étape n'est pas en attente, ou si c'est une sous-story, elle ne peut pas être subdivisée.";
$lang->story->errorEmptyReviewedBy = "『{$lang->story->reviewers}』canot be blank.";
$lang->story->mustChooseResult     = 'Sélect Résultat';
$lang->story->mustChoosePreVersion = 'Sélect une version pour revenir en arrière.';
$lang->story->noStory              = "Aucune story pour l'instant. ";
$lang->story->noRequirement        = 'No Requirements';
$lang->story->ignoreChangeStage    = 'The status of %s is Draft or Closed. This operation has been filtered.';
$lang->story->cannotDeleteParent   = "Impossible de supprimer {$lang->SRCommon} parent";
$lang->story->moveChildrenTips     = "Its Child {$lang->SRCommon} will be moved to the selected {$lang->productCommon} when editing the linked {$lang->productCommon} of Parent {$lang->SRCommon}.";
$lang->story->changeTips           = 'The story associated with the requirements to change, click "Cancel" ignore this change, click "Confirm" to change the story.';
$lang->story->estimateMustBeNumber = 'Estimate value must be number.';
$lang->story->estimateMustBePlus   = 'Estimated value cannot be negative';
$lang->story->confirmChangeBranch  = $lang->SRCommon . ' %s is linked to the plan of its linked branch. If the branch is edited, ' . $lang->SRCommon . ' will be removed from the plan of its linked branch. Do you want to continue edit ' . $lang->SRCommon . '?';
$lang->story->confirmChangePlan    = $lang->SRCommon . ' %s is linked to the branch of its plan. If the branch is edited, ' . $lang->SRCommon . ' will be removed from the plan. Do you want to continue edit branch ?';
$lang->story->errorDuplicateStory  = $lang->SRCommon . '%s not exist';
$lang->story->confirmRecallChange  = "After undo the change, the story content will revert to the version before the change. Are you sure you want to undo?";
$lang->story->confirmRecallReview  = "Are you sure you want to withdraw the review?";
$lang->story->noStoryToTask        = "Only the activated {$lang->SRCommon} can be converted into a task!";
$lang->story->ignoreClosedStory    = "{$lang->SRCommon} %s status is closed, and the operation has been filtered.";

$lang->story->form = new stdclass();
$lang->story->form->area     = 'Périmètre';
$lang->story->form->desc     = "Quelle story est-ce ? Quel est son état d'acceptance ?";
$lang->story->form->resource = 'Qui va allouer des resources ? Combien de temps cela va-t-il prendre ?';
$lang->story->form->file     = 'Si certains fichiers sont associés à la story, cliquez ici pour les uploader.';

$lang->story->action = new stdclass();
$lang->story->action->reviewed              = array('main' => '$date, validée par <strong>$actor</strong>. Le résultat est <strong>$extra</strong>.', 'extra' => 'reviewResultList');
$lang->story->action->rejectreviewed        = array('main' => '$date, validée par <strong>$actor</strong>. Le résultat est <strong>$extra</strong>. La raison est <strong>$reason</strong>.', 'extra' => 'reviewResultList', 'reason' => 'reasonList');
$lang->story->action->recalled              = array('main' => '$date, recalled by <strong>$actor</strong>.');
$lang->story->action->closed                = array('main' => '$date, Fermée par <strong>$actor</strong>. La raison est <strong>$extra</strong> $appendLink.', 'extra' => 'reasonList');
$lang->story->action->closedbysystem        = array('main' => '$date, The system determines that the parent story is automatically closed because all child stories are closed.');
$lang->story->action->reviewpassed          = array('main' => '$date, determined by the <strong>System</strong>. The result is <strong>Pass</strong>.');
$lang->story->action->reviewrejected        = array('main' => '$date, closed by <strong>System</strong>. The reason is <strong>Rejection</strong>.');
$lang->story->action->reviewclarified       = array('main' => '$date, determined by the <strong>System</strong>. The result is <strong>To Be Clarified</strong>. Please re-initiate the review after edit.');
$lang->story->action->reviewreverted        = array('main' => '$date, determined by the <strong>System</strong>. The result is <strong>Undo Change</strong>.');
$lang->story->action->linked2plan           = array('main' => '$date, planifiée par <strong>$actor</strong> au Plan <strong>$extra</strong>');
$lang->story->action->unlinkedfromplan      = array('main' => '$date, déplanifiée par <strong>$actor</strong> du Plan <strong>$extra</strong>.');
$lang->story->action->linked2execution      = array('main' => '$date, associée au ' . $lang->executionCommon . ' <strong>$extra</strong> par <strong>$actor</strong>.');
$lang->story->action->unlinkedfromexecution = array('main' => '$date, dissociée du ' . $lang->executionCommon . ' <strong>$extra</strong> par <strong>$actor</strong>.');
$lang->story->action->linked2kanban         = array('main' => '$date, linked by <strong>$actor</strong> to Kanban <strong>$extra</strong>.');
$lang->story->action->linked2project        = array('main' => '$date, linked by <strong>$actor</strong> ' . "to {$lang->projectCommon}" . ' <strong>$extra</strong>.');
$lang->story->action->unlinkedfromproject   = array('main' => '$date, unlinked by <strong>$actor</strong> ' . "from {$lang->projectCommon}" . ' <strong>$extra</strong>.');
$lang->story->action->linked2build          = array('main' => '$date, intégrée par <strong>$actor</strong> au Build <strong>$extra</strong>');
$lang->story->action->unlinkedfrombuild     = array('main' => '$date, retirée par <strong>$actor</strong> du Build <strong>$extra</strong>.');
$lang->story->action->linked2release        = array('main' => '$date, ajoutée par <strong>$actor</strong> à la Release <strong>$extra</strong>');
$lang->story->action->unlinkedfromrelease   = array('main' => '$date, retirée par <strong>$actor</strong> de la Release <strong>$extra</strong>.');
$lang->story->action->linked2revision       = array('main' => '$date, linked by <strong>$actor</strong> to Revision <strong>$extra</strong>');
$lang->story->action->unlinkedfromrevision  = array('main' => '$date, unlinked by <strong>$actor</strong> to Revision <strong>$extra</strong>');
$lang->story->action->linkrelatedstory      = array('main' => '$date, associée par <strong>$actor</strong> à la Story <strong>$extra</strong>.');
$lang->story->action->subdividestory        = array('main' => '$date, décomposée par <strong>$actor</strong> en Story <strong>$extra</strong>.');
$lang->story->action->unlinkrelatedstory    = array('main' => '$date, dissociée par <strong>$actor</strong> de la Story <strong>$extra</strong>.');
$lang->story->action->unlinkchildstory      = array('main' => '$date, dissociée par <strong>$actor</strong> de la sous-Story <strong>$extra</strong>.');
$lang->story->action->recalledchange        = array('main' => '$date, Undo changes by <strong>\$actor</strong>.');
$lang->story->action->synctwins             = array('main' => "\$date, the system judges that this story is adjusted synchronously due to the \$operate of twin story <strong>\$extra</strong>.", 'operate' => 'operateList');
$lang->story->action->linked2roadmap        = array('main' => '$date, linked by <strong>$actor</strong> to Roadmap <strong>$extra</strong>');
$lang->story->action->unlinkedfromroadmap   = array('main' => '$date, unlinked by <strong>$actor</strong> from Roadmap <strong>$extra</strong>.');
$lang->story->action->changedbycharter      = array('main' => '$date, launched by <strong>$actor</strong> for charter <strong>$extra</strong>, Synchronously adjust the story status to launched.');

/* Statistical statement. */
$lang->story->report = new stdclass();
$lang->story->report->common = 'Rapport';
$lang->story->report->select = 'Choix Type de Rapport';
$lang->story->report->create = 'Créer Rapport';
$lang->story->report->value  = 'Rapports';

$lang->story->report->charts['storiesPerProduct']      = 'Regroupé par ' . $lang->productCommon . ' Story';
$lang->story->report->charts['storiesPerModule']       = 'Regroupé par Module Story';
$lang->story->report->charts['storiesPerSource']       = 'Regroupé par Source';
$lang->story->report->charts['storiesPerPlan']         = 'Regroupé par Plan';
$lang->story->report->charts['storiesPerStatus']       = 'Regroupé par Statut';
$lang->story->report->charts['storiesPerStage']        = 'Regroupé par Phase';
$lang->story->report->charts['storiesPerPri']          = 'Regroupé par Priorité';
$lang->story->report->charts['storiesPerEstimate']     = 'Regroupé par Estimés';
$lang->story->report->charts['storiesPerOpenedBy']     = 'Regroupé par Créateur';
$lang->story->report->charts['storiesPerAssignedTo']   = 'Regroupé par Détenteur';
$lang->story->report->charts['storiesPerClosedReason'] = 'Par Raison de Fermeture';
$lang->story->report->charts['storiesPerChange']       = 'Regroupé par Modif de Story';

$lang->story->report->options = new stdclass();
$lang->story->report->options->graph  = new stdclass();
$lang->story->report->options->type   = 'pie';
$lang->story->report->options->width  = 500;
$lang->story->report->options->height = 140;

$lang->story->report->storiesPerProduct      = new stdclass();
$lang->story->report->storiesPerModule       = new stdclass();
$lang->story->report->storiesPerSource       = new stdclass();
$lang->story->report->storiesPerPlan         = new stdclass();
$lang->story->report->storiesPerStatus       = new stdclass();
$lang->story->report->storiesPerStage        = new stdclass();
$lang->story->report->storiesPerPri          = new stdclass();
$lang->story->report->storiesPerOpenedBy     = new stdclass();
$lang->story->report->storiesPerAssignedTo   = new stdclass();
$lang->story->report->storiesPerClosedReason = new stdclass();
$lang->story->report->storiesPerEstimate     = new stdclass();
$lang->story->report->storiesPerChange       = new stdclass();

$lang->story->report->storiesPerProduct->item      = $lang->productCommon;
$lang->story->report->storiesPerModule->item       = 'Module';
$lang->story->report->storiesPerSource->item       = 'Source';
$lang->story->report->storiesPerPlan->item         = 'Plan';
$lang->story->report->storiesPerStatus->item       = 'Statut';
$lang->story->report->storiesPerStage->item        = 'Phase';
$lang->story->report->storiesPerPri->item          = 'Priorité';
$lang->story->report->storiesPerOpenedBy->item     = 'Créateur';
$lang->story->report->storiesPerAssignedTo->item   = 'Détenteur';
$lang->story->report->storiesPerClosedReason->item = 'Raison';
$lang->story->report->storiesPerEstimate->item     = 'Estimation';
$lang->story->report->storiesPerChange->item       = 'Chang. Story';

$lang->story->report->storiesPerProduct->graph      = new stdclass();
$lang->story->report->storiesPerModule->graph       = new stdclass();
$lang->story->report->storiesPerSource->graph       = new stdclass();
$lang->story->report->storiesPerPlan->graph         = new stdclass();
$lang->story->report->storiesPerStatus->graph       = new stdclass();
$lang->story->report->storiesPerStage->graph        = new stdclass();
$lang->story->report->storiesPerPri->graph          = new stdclass();
$lang->story->report->storiesPerOpenedBy->graph     = new stdclass();
$lang->story->report->storiesPerAssignedTo->graph   = new stdclass();
$lang->story->report->storiesPerClosedReason->graph = new stdclass();
$lang->story->report->storiesPerEstimate->graph     = new stdclass();
$lang->story->report->storiesPerChange->graph       = new stdclass();

$lang->story->report->storiesPerProduct->graph->xAxisName      = $lang->productCommon;
$lang->story->report->storiesPerModule->graph->xAxisName       = 'Module';
$lang->story->report->storiesPerSource->graph->xAxisName       = 'Source';
$lang->story->report->storiesPerPlan->graph->xAxisName         = 'Plan';
$lang->story->report->storiesPerStatus->graph->xAxisName       = 'Statut';
$lang->story->report->storiesPerStage->graph->xAxisName        = 'Phase';
$lang->story->report->storiesPerPri->graph->xAxisName          = 'Priorité';
$lang->story->report->storiesPerOpenedBy->graph->xAxisName     = 'Créateur';
$lang->story->report->storiesPerAssignedTo->graph->xAxisName   = 'Détenteur';
$lang->story->report->storiesPerClosedReason->graph->xAxisName = 'Raison Fermeture';
$lang->story->report->storiesPerEstimate->graph->xAxisName     = 'Estimattion ';
$lang->story->report->storiesPerChange->graph->xAxisName       = 'Change Times';

$lang->story->placeholder = new stdclass();
$lang->story->placeholder->estimate = $lang->story->hour;

$lang->story->chosen = new stdClass();
$lang->story->chosen->reviewedBy = 'Choisir valideur';

$lang->story->notice = new stdClass();
$lang->story->notice->closed           = 'La Story que vous avez sélectionnée est malheureusement fermée !';
$lang->story->notice->reviewerNotEmpty = 'This requirement needs to be reviewed, and the reviewedby is required.';
$lang->story->notice->changePlan       = 'The plan can be changed to only one item.';

$lang->story->convertToTask = new stdClass();
$lang->story->convertToTask->fieldList = array();
$lang->story->convertToTask->fieldList['module']     = 'Module';
$lang->story->convertToTask->fieldList['spec']       = "Description";
$lang->story->convertToTask->fieldList['pri']        = 'Priority';
$lang->story->convertToTask->fieldList['mailto']     = 'Mailto';
$lang->story->convertToTask->fieldList['assignedTo'] = 'AssignTo';

$lang->story->categoryList['feature']     = 'Feature';
$lang->story->categoryList['interface']   = 'Interface';
$lang->story->categoryList['performance'] = 'Performance';
$lang->story->categoryList['safe']        = 'Safe';
$lang->story->categoryList['experience']  = 'Experience';
$lang->story->categoryList['improve']     = 'Improve';
$lang->story->categoryList['other']       = 'Other';

$lang->story->changeTip = "Seules les exigences de l'état actif peuvent être modifiées.";

$lang->story->reviewTip = array();
$lang->story->reviewTip['active']      = "Cette Story est déjà active, il n'y a pas de demande de révision.";
$lang->story->reviewTip['notReviewer'] = "Vous n'êtes pas l'évaluateur de cette Story et ne pouvez pas effectuer d'opérations d'évaluation.";
$lang->story->reviewTip['reviewed']    = "Révisé";

$lang->story->recallTip = array();
$lang->story->recallTip['actived'] = "Aucun processus de révision n'a été lancé pour cette Story, et il n'est pas nécessaire de révoquer l'opération.";

$lang->story->subDivideTip = array();
$lang->story->subDivideTip['subStory']   = 'Les sous-stories ne peuvent pas être décomposées.';
$lang->story->subDivideTip['notWait']    = "L'exigence %s ne peut pas être subdivisée.";
$lang->story->subDivideTip['notActive']  = "The %s is not active and cannot be subdivided.";
$lang->story->subDivideTip['twinsSplit'] = 'The Twins Story cannot be subdivided.';

$lang->story->featureBar['browse']['all']       = $lang->all;
$lang->story->featureBar['browse']['unclosed']  = $lang->story->unclosed;
$lang->story->featureBar['browse']['draft']     = $lang->story->statusList['draft'];
$lang->story->featureBar['browse']['reviewing'] = $lang->story->statusList['reviewing'];

$lang->story->operateList = array();
$lang->story->operateList['assigned']       = 'assigned';
$lang->story->operateList['closed']         = 'closed';
$lang->story->operateList['activated']      = 'activated';
$lang->story->operateList['changed']        = 'changed';
$lang->story->operateList['reviewed']       = 'reviewed';
$lang->story->operateList['edited']         = 'edited';
$lang->story->operateList['submitreview']   = 'submit review';
$lang->story->operateList['recalledchange'] = 'recalled change';
$lang->story->operateList['recalled']       = 'recalled review';

$lang->requirement->common             = $lang->URCommon;
$lang->requirement->create             = 'Create Requirement';
$lang->requirement->batchCreate        = "Batch Create";
$lang->requirement->editAction         = "Edit {$lang->URCommon}";
$lang->requirement->changeAction       = "Change {$lang->URCommon}";
$lang->requirement->assignAction       = "Assign {$lang->URCommon}";
$lang->requirement->reviewAction       = "Review {$lang->URCommon}";
$lang->requirement->subdivideAction    = "Subdivide {$lang->URCommon}";
$lang->requirement->closeAction        = "Close {$lang->URCommon}";
$lang->requirement->activateAction     = "Activate {$lang->URCommon}";
$lang->requirement->deleteAction       = "Delete {$lang->URCommon}";
$lang->requirement->exportAction       = "Export {$lang->URCommon}";
$lang->requirement->reportAction       = "Report";
$lang->requirement->recall             = $lang->story->recallAction;
$lang->requirement->batchReview        = 'Batch Review';
$lang->requirement->batchEdit          = "Batch Edit";
$lang->requirement->batchClose         = 'Batch Close';
$lang->requirement->view               = 'Requirement Detail';
$lang->requirement->linkRequirementsAB = "Link {$lang->URCommon}";
$lang->requirement->batchChangeBranch  = "Batch Change Branches";
$lang->requirement->batchAssignTo      = "Batch Assign";
$lang->requirement->batchChangeModule  = "Batch Change Modules";
$lang->requirement->submitReview       = $lang->story->submitReview;
$lang->requirement->linkStory          = 'Lier Story';

$lang->story->addBranch      = 'Add %s';
$lang->story->deleteBranch   = 'Delete %s';
$lang->story->notice->branch = 'Each branch will establish a requirement. The requirements are twins. The twins requirements are synchronized except for the product, branch, module, plan, and stage fields. You can manually remove the twins relationship later';

$lang->story->relievedTwinsRelation     = 'Relieved twins relationship';
$lang->story->relievedTwinsRelationTips = 'After the twins relationship is terminated, it cannot be restored and the shutdown of the requirement is no longer synchronized.';
$lang->story->changeRelievedTwinsTips   = 'After the twins relationship is terminated, the twin stories are no longer synchronized.';
