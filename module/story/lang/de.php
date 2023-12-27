<?php
/**
 * The story module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: en.php 5141 2013-07-15 05:57:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
global $config;
$lang->story->create            = "Story hinzufügen";

$lang->story->requirement       = 'UR';
$lang->story->story             = zget($lang, 'SRCommon', "Story");
$lang->story->createStory       = 'Create ' . $lang->story->story;
$lang->story->createRequirement = 'Create ' . $lang->story->requirement;
$lang->story->affectedStories   = "Affected {$lang->story->story}";

$lang->story->batchCreate        = "Mehere hinzufügen";
$lang->story->change             = "Ändern";
$lang->story->changed            = 'Geändert';
$lang->story->assignTo           = 'Assign';
$lang->story->review             = 'Prüfen';
$lang->story->submitReview       = "Submit Review";
$lang->story->recall             = 'Revoke';
$lang->story->recallChange       = 'Undo Changes';
$lang->story->recallAction       = 'Undo';
$lang->story->needReview         = 'Need Review';
$lang->story->batchReview        = 'Mehere prüfen';
$lang->story->edit               = "Bearbeiten";
$lang->story->editDraft          = "Entwurf bearbeiten";
$lang->story->batchEdit          = "Mehere bearbeiten";
$lang->story->subdivide          = 'Aufteilen';
$lang->story->subdivideSR        = $lang->SRCommon . 'Aufteilen';
$lang->story->link               = 'Link';
$lang->story->unlink             = 'Unlink';
$lang->story->track              = 'Track';
$lang->story->trackAB            = 'Track';
$lang->story->processStoryChange = 'Confirm Story Change';
$lang->story->splitRequirent     = 'Decompose';
$lang->story->close              = 'Schließen';
$lang->story->batchClose         = 'Mehere schließen';
$lang->story->activate           = 'Aktivieren';
$lang->story->delete             = "Löschen";
$lang->story->view               = "Storydetails";
$lang->story->setting            = "Einstellungen";
$lang->story->tasks              = "Verknüpfte Aufgaben";
$lang->story->bugs               = "Verknüpfte Bugs";
$lang->story->cases              = "Verknüpfte Fälle";
$lang->story->taskCount          = 'Aufgaben';
$lang->story->bugCount           = 'Bugs';
$lang->story->caseCount          = 'Fälle';
$lang->story->taskCountAB        = 'T';
$lang->story->bugCountAB         = 'B';
$lang->story->caseCountAB        = 'C';
$lang->story->linkStory          = 'Link Requirement';
$lang->story->unlinkStory        = 'Verknüpfung aufheben';
$lang->story->linkStoriesAB      = "Link {$lang->SRCommon}";
$lang->story->linkRequirementsAB = "Link {$lang->URCommon}";
$lang->story->export             = "Daten exportieren";
$lang->story->zeroCase           = "Storys ohne Fälle";
$lang->story->zeroTask           = "Storys ohne Aufgaben anzeigen";
$lang->story->reportChart        = "Bericht";
$lang->story->copyTitle          = "Titel kopieren";
$lang->story->batchChangePlan    = "Mehere Pläne ändern";
$lang->story->batchChangeBranch  = "Mehere Branches ändern";
$lang->story->batchChangeStage   = "Mehere Phasen ändern";
$lang->story->batchAssignTo      = "Mehere zuordnen";
$lang->story->batchChangeModule  = "Mehere Module ändern";
$lang->story->viewAll            = "Alle";
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
$lang->story->changeAction    = "Change Story";
$lang->story->assignAction    = 'Assign Story';
$lang->story->reviewAction    = 'Review Story';
$lang->story->subdivideAction = 'Decompose Story';
$lang->story->closeAction     = 'Close Story';
$lang->story->activateAction  = 'Activate Story';
$lang->story->deleteAction    = "Delete Story";
$lang->story->exportAction    = "Export Story";
$lang->story->reportAction    = "Story Report";

$lang->story->skipStory        = '%s is a parent story. It cannot be closed.';
$lang->story->closedStory      = 'Story %s is closed and will not be closed.';
$lang->story->batchToTaskTips  = "The closed {$lang->SRCommon} will not be converted into tasks.";
$lang->story->successToTask    = "Converted to task.";
$lang->story->storyRound       = '%s time estimation';
$lang->story->float            = "『%s』should be positive number, decimals included.";
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
$lang->story->branch           = "Branch/Platform";
$lang->story->module           = 'Module';
$lang->story->moduleAB         = 'Module';
$lang->story->roadmap          = 'Roadmap';
$lang->story->source           = 'Von';
$lang->story->sourceNote       = 'Hinweis';
$lang->story->fromBug          = 'Von Bug';
$lang->story->title            = 'Titel';
$lang->story->type             = "Story/Requirement";
$lang->story->category         = 'Category';
$lang->story->color            = 'Color';
$lang->story->toBug            = 'ToBug';
$lang->story->spec             = 'Beschreibung';
$lang->story->assign           = 'Assign';
$lang->story->verify           = 'Akzeptanz';
$lang->story->pri              = 'Priorität';
$lang->story->estimate         = 'Schätzung(h)';
$lang->story->estimateAB       = 'Schätzung(h)';
$lang->story->hour             = 'Stunde';
$lang->story->consumed         = 'Zeitaufwendig';
$lang->story->status           = 'Status';
$lang->story->statusAB         = 'Status';
$lang->story->subStatus        = 'Sub Status';
$lang->story->stage            = 'Phase';
$lang->story->stageAB          = 'Phase';
$lang->story->stagedBy         = 'SetBy';
$lang->story->mailto           = 'Mail an';
$lang->story->openedBy         = 'Ersteller';
$lang->story->openedByAB       = 'Created';
$lang->story->openedDate       = 'Erstellt am';
$lang->story->assignedTo       = 'Zuständiger';
$lang->story->assignedToAB     = 'Assign';
$lang->story->assignedDate     = 'Zugewisen am';
$lang->story->lastEditedBy     = 'Letzte Bearbeitung';
$lang->story->lastEditedByAB   = 'Letzte Bearbeitung';
$lang->story->lastEditedDate   = 'Bearbeitet am';
$lang->story->closedBy         = 'Geschlossen von';
$lang->story->closedDate       = 'Geschlossen am';
$lang->story->closedReason     = 'Geschlossen weil';
$lang->story->rejectedReason   = 'Abgelehnt weil';
$lang->story->changedBy        = 'ChangedBy';
$lang->story->changedDate      = 'ChangedDate';
$lang->story->reviewedBy       = 'Prüfer';
$lang->story->reviewer         = 'Reviewers';
$lang->story->reviewers        = 'Reviewers';
$lang->story->reviewedDate     = 'Geprüft am';
$lang->story->activatedDate    = 'Activated Date';
$lang->story->version          = 'Version';
$lang->story->feedbackBy       = 'From Name';
$lang->story->notifyEmail      = 'From Email';
$lang->story->plan             = 'Plan';
$lang->story->planAB           = 'Plan';
$lang->story->comment          = 'Kommentar';
$lang->story->children         = "Child {$lang->SRCommon}";
$lang->story->childrenAB       = "C";
$lang->story->linkStories      = 'Story verknüpfen';
$lang->story->linkRequirements = "Linked {$lang->URCommon}";
$lang->story->childStories     = 'Story aufteilen';
$lang->story->duplicateStory   = 'Story kopieren';
$lang->story->reviewResult     = 'Ergbnis prüfen';
$lang->story->reviewResultAB   = 'Bewertungsergebnisse';
$lang->story->preVersion       = 'Frühere Version';
$lang->story->keywords         = 'Tags';
$lang->story->newStory         = 'Weitere Story';
$lang->story->colorTag         = 'Farb-Tag';
$lang->story->files            = 'Dateien';
$lang->story->copy             = "Story kopieren";
$lang->story->total            = "Total Storys";
$lang->story->draft            = 'Entwurf';
$lang->story->unclosed         = 'Nicht geschlossen';
$lang->story->deleted          = 'Gelöscht';
$lang->story->released         = 'Released Linked Stories';
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

$lang->story->ditto       = 'Dito';
$lang->story->dittoNotice = "This story is not linked to the same {$lang->productCommon} as the last one is!";

$lang->story->needNotReviewList[0] = 'Need Review';
$lang->story->needNotReviewList[1] = 'Need Not Review';

$lang->story->useList[0] = 'Ja';
$lang->story->useList[1] = 'Nein';

$lang->story->statusList['']          = '';
$lang->story->statusList['draft']     = 'Entwurf';
$lang->story->statusList['reviewing'] = 'Wird geprüft';
$lang->story->statusList['active']    = 'Aktiv';
$lang->story->statusList['closed']    = 'Geschlossen';
$lang->story->statusList['changing']  = 'Geändert';

if($config->systemMode == 'PLM')
{
    $lang->story->statusList['launched']   = 'Launched';
    $lang->story->statusList['developing'] = 'Developing';
}

$lang->story->stageList = array();
$lang->story->stageList['']           = '';
$lang->story->stageList['wait']       = 'Wartend';
$lang->story->stageList['planned']    = 'Geplant';
$lang->story->stageList['projected']  = 'Projektiert';
$lang->story->stageList['developing'] = 'Entwicklung';
$lang->story->stageList['developed']  = 'Entwickelt';
$lang->story->stageList['testing']    = 'Testen';
$lang->story->stageList['tested']     = 'Getestet';
$lang->story->stageList['verified']   = 'Akzepiert';
$lang->story->stageList['released']   = 'Freigegeben';
$lang->story->stageList['closed']     = 'Geschlossen';

$lang->story->reasonList['']           = '';
$lang->story->reasonList['done']       = 'Erledigt';
$lang->story->reasonList['subdivided'] = 'Aufgeteilt';
$lang->story->reasonList['duplicate']  = 'Dublette';
$lang->story->reasonList['postponed']  = 'Verschoben';
$lang->story->reasonList['willnotdo']  = "Ignoriert";
$lang->story->reasonList['cancel']     = 'Abgebrochen';
$lang->story->reasonList['bydesign']   = 'Kein Fehler';
//$lang->story->reasonList['isbug']      = 'Bug!';

$lang->story->reviewResultList['']        = '';
$lang->story->reviewResultList['pass']    = 'Gültig';
$lang->story->reviewResultList['revert']  = 'Umkehren';
$lang->story->reviewResultList['clarify'] = 'Klären';
$lang->story->reviewResultList['reject']  = 'Ablehnen';

$lang->story->reviewList[0] = 'Nein';
$lang->story->reviewList[1] = 'Ja';

$lang->story->sourceList['']           = '';
$lang->story->sourceList['customer']   = 'Kunde';
$lang->story->sourceList['user']       = 'Benutzer';
$lang->story->sourceList['po']         = $lang->productCommon . ' Eigentümer';
$lang->story->sourceList['market']     = 'Marketing';
$lang->story->sourceList['service']    = 'Service';
$lang->story->sourceList['operation']  = 'Operative';
$lang->story->sourceList['support']    = 'Support';
$lang->story->sourceList['competitor'] = 'Wettbewerber';
$lang->story->sourceList['partner']    = 'Partner';
$lang->story->sourceList['dev']        = 'Entwickler';
$lang->story->sourceList['tester']     = 'QS-Team';
$lang->story->sourceList['bug']        = 'Bug';
$lang->story->sourceList['forum']      = 'Forum';
$lang->story->sourceList['other']      = 'Andere';

$lang->story->priList[]  = '';
$lang->story->priList[1] = '1';
$lang->story->priList[2] = '2';
$lang->story->priList[3] = '3';
$lang->story->priList[4] = '4';

$lang->story->changeList = array();
$lang->story->changeList['no']  = 'Cancel';
$lang->story->changeList['yes'] = 'Confirm';

$lang->story->legendBasicInfo      = 'Basis Infos';
$lang->story->legendLifeTime       = 'Story Leben ';
$lang->story->legendRelated        = 'Weitere Infos';
$lang->story->legendMailto         = 'Mail an';
$lang->story->legendAttatch        = 'Dateien';
$lang->story->legendProjectAndTask = $lang->executionCommon . ' Aufgaben';
$lang->story->legendBugs           = 'Verküpfte Bugs';
$lang->story->legendFromBug        = 'Verküpfte Formular Bugs';
$lang->story->legendCases          = 'Verküpfte Fälle';
$lang->story->legendBuilds         = 'Verkü pfte Builds';
$lang->story->legendReleases       = 'Verkü pfte Releases';
$lang->story->legendLinkStories    = 'Verküpfte Storys';
$lang->story->legendChildStories   = 'Story auteien';
$lang->story->legendSpec           = 'Beschreibung';
$lang->story->legendVerify         = 'Akzeptanz';
$lang->story->legendMisc           = 'Sonstiges ';
$lang->story->legendInformation    = 'Story Information';

$lang->story->lblChange   = 'Ändern';
$lang->story->lblReview   = 'Prüfen';
$lang->story->lblActivate = 'Aktivieren';
$lang->story->lblClose    = 'Close';
$lang->story->lblTBC      = 'Task/Bug/Case';

$lang->story->checkAffection       = 'Impact';
$lang->story->affectedProjects     = "{$lang->project->common}s/{$lang->execution->common}s";
$lang->story->affectedBugs         = 'Bug';
$lang->story->affectedCases        = 'Fall';
$lang->story->affectedTwins        = 'Zwillinge';

$lang->story->specTemplate         = "Als ein < type of user >, möchte ich < some goal > dass < some reason >.";
$lang->story->needNotReview        = 'Keine Prüfung';
$lang->story->successSaved         = "Story wurde gespeichrt!";
$lang->story->confirmDelete        = "Möchten Sie diese Story löschen?";
$lang->story->confirmRecall        = "Do you want to recall this story?";
$lang->story->errorEmptyChildStory = '『Unterteilte Story』 darf nicht leer sein.';
$lang->story->errorNotSubdivide    = "If the status is not active, or the stage is not wait, or a sub story, it cannot be subdivided.";
$lang->story->errorEmptyReviewedBy = "『ReviewedBy』darf nicht leer sein.";
$lang->story->mustChooseResult     = 'Ergebnis wählen';
$lang->story->mustChoosePreVersion = 'Version wählen um es umzukhren.';
$lang->story->noStory              = 'Keine Storys. ';
$lang->story->noRequirement        = 'No Requirements';
$lang->story->noRelatedRequirement = "No related requirements.";
$lang->story->ignoreChangeStage    = 'The status of %s is Draft or Closed. This operation has been filtered.';
$lang->story->cannotDeleteParent   = "Can not delete parent {$lang->SRCommon}";
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
$lang->story->form->area     = 'Story Bereich';
$lang->story->form->desc     = 'Welche Story ist es? Was sind die Abnahmebedingungen?';
$lang->story->form->resource = 'Wer plant die Resourcen? Wie lange wird das dauern?';
$lang->story->form->file     = 'Wenn Dateien zu dieser Story gehören, laden Sie diese bIttr hoch.';

$lang->story->action = new stdclass();
$lang->story->action->reviewed              = array('main' => '$date, geprüft von <strong>$actor</strong>. Das Ergebnis ist <strong>$extra</strong>.', 'extra' => 'reviewResultList');
$lang->story->action->rejectreviewed        = array('main' => '$date, geprüft von <strong>$actor</strong>. Das Ergebnis ist <strong>$extra</strong>, Der Grund ist <strong>$reason</strong>.', 'extra' => 'reviewResultList', 'reason' => 'reasonList');
$lang->story->action->recalled              = array('main' => '$date, recalled by <strong>$actor</strong>.');
$lang->story->action->closed                = array('main' => '$date, geschlossen von <strong>$actor</strong>. Der Grund ist <strong>$extra</strong> $appendLink.', 'extra' => 'reasonList');
$lang->story->action->closedbysystem        = array('main' => '$date, The system determines that the parent story is automatically closed because all child stories are closed.');
$lang->story->action->reviewpassed          = array('main' => '$date, determined by the <strong>System</strong>. The result is <strong>Pass</strong>.');
$lang->story->action->reviewrejected        = array('main' => '$date, closed by <strong>System</strong>. The reason is <strong>Rejection</strong>.');
$lang->story->action->reviewclarified       = array('main' => '$date, determined by the <strong>System</strong>. The result is <strong>To Be Clarified</strong>. Please re-initiate the review after edit.');
$lang->story->action->reviewreverted        = array('main' => '$date, determined by the <strong>System</strong>. The result is <strong>Undo Change</strong>.');
$lang->story->action->linked2plan           = array('main' => '$date, verknüpft von <strong>$actor</strong> mit Plan <strong>$extra</strong>');
$lang->story->action->unlinkedfromplan      = array('main' => '$date, Verknüpfung aufgelöst durch <strong>$actor</strong> von Plan <strong>$extra</strong>.');
$lang->story->action->linked2execution      = array('main' => '$date, verknüpft von <strong>$actor</strong> mit ' . $lang->executionCommon . ' <strong>$extra</strong>.');
$lang->story->action->unlinkedfromexecution = array('main' => '$date, Verknüpfung aufgelöst durch <strong>$actor</strong> mit ' . $lang->executionCommon . ' <strong>$extra</strong>.');
$lang->story->action->linked2kanban         = array('main' => '$date, linked by <strong>$actor</strong> to Kanban <strong>$extra</strong>.');
$lang->story->action->linked2project        = array('main' => '$date, linked by <strong>$actor</strong> ' . "to {$lang->projectCommon}" . ' <strong>$extra</strong>.');
$lang->story->action->unlinkedfromproject   = array('main' => '$date, unlinked by <strong>$actor</strong> ' . "from {$lang->projectCommon}" . ' <strong>$extra</strong>.');
$lang->story->action->linked2build          = array('main' => '$date, verknüpft von <strong>$actor</strong> mit Build <strong>$extra</strong>');
$lang->story->action->unlinkedfrombuild     = array('main' => '$date, Verknüpfung aufgelöst durch <strong>$actor</strong> von Build <strong>$extra</strong>.');
$lang->story->action->linked2release        = array('main' => '$date, verknüpft von <strong>$actor</strong> mit Release <strong>$extra</strong>');
$lang->story->action->unlinkedfromrelease   = array('main' => '$date, Verknüpfung aufgelöst durch <strong>$actor</strong> von Release <strong>$extra</strong>.');
$lang->story->action->linked2revision       = array('main' => '$date, linked by <strong>$actor</strong> to Revision <strong>$extra</strong>');
$lang->story->action->unlinkedfromrevision  = array('main' => '$date, unlinked by <strong>$actor</strong> to Revision <strong>$extra</strong>');
$lang->story->action->linkrelatedstory      = array('main' => '$date, verknüpft von <strong>$actor</strong> mit Story <strong>$extra</strong>.');
$lang->story->action->subdividestory        = array('main' => '$date, aufgeteilt von <strong>$actor</strong> mit Story <strong>$extra</strong>.');
$lang->story->action->unlinkrelatedstory    = array('main' => '$date, Verknüpfung aufgelöst durch <strong>$actor</strong> von Story <strong>$extra</strong>.');
$lang->story->action->unlinkchildstory      = array('main' => '$date, Verknüpfung aufgelöst durch <strong>$actor</strong> Story <strong>$extra</strong>.');
$lang->story->action->recalledchange        = array('main' => '$date, Undo changes by <strong>\$actor</strong>.');
$lang->story->action->synctwins             = array('main' => "\$date, the system judges that this story is adjusted synchronously due to the \$operate of twin story <strong>\$extra</strong>.", 'operate' => 'operateList');
$lang->story->action->linked2roadmap        = array('main' => '$date, linked by <strong>$actor</strong> to Roadmap <strong>$extra</strong>');
$lang->story->action->unlinkedfromroadmap   = array('main' => '$date, unlinked by <strong>$actor</strong> from Roadmap <strong>$extra</strong>.');
$lang->story->action->changedbycharter      = array('main' => '$date, launched by <strong>$actor</strong> for charter <strong>$extra</strong>, Synchronously adjust the story status to launched.');

/* Statistical statement. */
$lang->story->report = new stdclass();
$lang->story->report->common = 'Bericht';
$lang->story->report->select = 'Storys gruppieren nach';
$lang->story->report->create = 'Erzeugen';
$lang->story->report->value  = 'Story Anzahl';

$lang->story->report->charts['storysPerProduct']      = 'nach ' . $lang->productCommon;
$lang->story->report->charts['storysPerModule']       = 'nach Modul';
$lang->story->report->charts['storysPerSource']       = 'nach Source';
$lang->story->report->charts['storysPerPlan']         = 'nach Plan';
$lang->story->report->charts['storysPerStatus']       = 'nach Status';
$lang->story->report->charts['storysPerStage']        = 'nach Phase';
$lang->story->report->charts['storysPerPri']          = 'nach Priorität';
$lang->story->report->charts['storysPerEstimate']     = 'nach Stunden';
$lang->story->report->charts['storysPerOpenedBy']     = 'nach Ersteller';
$lang->story->report->charts['storysPerAssignedTo']   = 'nach Zuständiger';
$lang->story->report->charts['storysPerClosedReason'] = 'nach Grund';
$lang->story->report->charts['storysPerChange']       = 'nach Änderungszeit';

$lang->story->report->options = new stdclass();
$lang->story->report->options->graph  = new stdclass();
$lang->story->report->options->type   = 'pie';
$lang->story->report->options->width  = 500;
$lang->story->report->options->height = 140;

$lang->story->report->storysPerProduct      = new stdclass();
$lang->story->report->storysPerModule       = new stdclass();
$lang->story->report->storysPerSource       = new stdclass();
$lang->story->report->storysPerPlan         = new stdclass();
$lang->story->report->storysPerStatus       = new stdclass();
$lang->story->report->storysPerStage        = new stdclass();
$lang->story->report->storysPerPri          = new stdclass();
$lang->story->report->storysPerOpenedBy     = new stdclass();
$lang->story->report->storysPerAssignedTo   = new stdclass();
$lang->story->report->storysPerClosedReason = new stdclass();
$lang->story->report->storysPerEstimate     = new stdclass();
$lang->story->report->storysPerChange       = new stdclass();

$lang->story->report->storysPerProduct->item      = $lang->productCommon;
$lang->story->report->storysPerModule->item       = 'Modul';
$lang->story->report->storysPerSource->item       = 'Source';
$lang->story->report->storysPerPlan->item         = 'Plan';
$lang->story->report->storysPerStatus->item       = 'Status';
$lang->story->report->storysPerStage->item        = 'Phase';
$lang->story->report->storysPerPri->item          = 'Priorität';
$lang->story->report->storysPerOpenedBy->item     = 'Konto';
$lang->story->report->storysPerAssignedTo->item   = 'Benutzer';
$lang->story->report->storysPerClosedReason->item = 'Grund';
$lang->story->report->storysPerEstimate->item     = 'Stunden';
$lang->story->report->storysPerChange->item       = 'Änderung';

$lang->story->report->storysPerProduct->graph      = new stdclass();
$lang->story->report->storysPerModule->graph       = new stdclass();
$lang->story->report->storysPerSource->graph       = new stdclass();
$lang->story->report->storysPerPlan->graph         = new stdclass();
$lang->story->report->storysPerStatus->graph       = new stdclass();
$lang->story->report->storysPerStage->graph        = new stdclass();
$lang->story->report->storysPerPri->graph          = new stdclass();
$lang->story->report->storysPerOpenedBy->graph     = new stdclass();
$lang->story->report->storysPerAssignedTo->graph   = new stdclass();
$lang->story->report->storysPerClosedReason->graph = new stdclass();
$lang->story->report->storysPerEstimate->graph     = new stdclass();
$lang->story->report->storysPerChange->graph       = new stdclass();

$lang->story->report->storysPerProduct->graph->xAxisName      = $lang->productCommon;
$lang->story->report->storysPerModule->graph->xAxisName       = 'Modul';
$lang->story->report->storysPerSource->graph->xAxisName       = 'Source';
$lang->story->report->storysPerPlan->graph->xAxisName         = 'Plan';
$lang->story->report->storysPerStatus->graph->xAxisName       = 'Status';
$lang->story->report->storysPerStage->graph->xAxisName        = 'Phase';
$lang->story->report->storysPerPri->graph->xAxisName          = 'Priorität';
$lang->story->report->storysPerOpenedBy->graph->xAxisName     = 'Ersteller';
$lang->story->report->storysPerAssignedTo->graph->xAxisName   = 'Zuständiger';
$lang->story->report->storysPerClosedReason->graph->xAxisName = 'Grund';
$lang->story->report->storysPerEstimate->graph->xAxisName     = 'Stunden ';
$lang->story->report->storysPerChange->graph->xAxisName       = 'Änderung';

$lang->story->placeholder = new stdclass();
$lang->story->placeholder->estimate = $lang->story->hour;

$lang->story->chosen = new stdClass();
$lang->story->chosen->reviewedBy = 'Prüfer wählen';

$lang->story->notice = new stdClass();
$lang->story->notice->closed           = 'Die ausgewählten Storys wurden bereits geschlossen!';
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

$lang->story->ipdCategoryList['zhanlue']         = 'strategic';
$lang->story->ipdCategoryList['maintainability'] = 'maintainability';
$lang->story->ipdCategoryList['packing']         = 'packing';

$lang->story->changeTip = 'Only active can be changed.';

$lang->story->reviewTip = array();
$lang->story->reviewTip['active']      = 'The Story is already active,no review requirements.';
$lang->story->reviewTip['notReviewer'] = 'You are not the reviewer of this Story and cannot perform review operations.';
$lang->story->reviewTip['reviewed']    = 'Reviewed';

$lang->story->recallTip = array();
$lang->story->recallTip['actived'] = 'The Story has not initiated a review process and no undo action is required.';

$lang->story->subDivideTip = array();
$lang->story->subDivideTip['subStory']   = 'The Sub-stories cannot be subdivided.';
$lang->story->subDivideTip['notWait']    = 'The Story has been %s and cannot be subdivided.';
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
$lang->requirement->linkStory          = 'Link Story';

$lang->story->addBranch      = 'Add %s';
$lang->story->deleteBranch   = 'Delete %s';
$lang->story->notice->branch = 'Each branch will establish a requirement. The requirements are twins. The twins requirements are synchronized except for the product, branch, module, plan, and stage fields. You can manually remove the twins relationship later';

$lang->story->relievedTwinsRelation     = 'Relieved twins relationship';
$lang->story->relievedTwinsRelationTips = 'After the twins relationship is terminated, it cannot be restored and the shutdown of the requirement is no longer synchronized.';
$lang->story->changeRelievedTwinsTips   = 'After the twins relationship is terminated, the twin stories are no longer synchronized.';
$lang->story->storyUnlinkRoadmap        = 'This story was launched and then removed from the roadmap, and needs to be launched again before it can be viewed in the IPD rnd management page.';
