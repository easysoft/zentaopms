<?php
/**
 * The execution module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: en.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
/* Fields. */
$lang->execution->allExecutions       = 'Alle';
$lang->execution->allExecutionAB      = 'Execution List';
$lang->execution->id                  = $lang->executionCommon . ' ID';
$lang->execution->type                = 'Typ';
$lang->execution->name                = 'Name';
$lang->execution->code                = 'Alias';
$lang->execution->projectName         = $lang->projectCommon;
$lang->execution->project             = $lang->projectCommon;
$lang->execution->execId              = "{$lang->execution->common} ID";
$lang->execution->execName            = 'Execution Name';
$lang->execution->execCode            = 'Execution Code';
$lang->execution->execType            = 'Execution Type';
$lang->execution->lifetime            = $lang->projectCommon . ' Cycle';
$lang->execution->attribute           = 'Stage Type';
$lang->execution->percent             = 'Workload %';
$lang->execution->milestone           = 'Milestone';
$lang->execution->parent              = $lang->projectCommon;
$lang->execution->path                = 'Path';
$lang->execution->grade               = 'Grade';
$lang->execution->output              = 'Output';
$lang->execution->version             = 'Version';
$lang->execution->parentVersion       = 'Parent Version';
$lang->execution->planDuration        = 'Plan Duration';
$lang->execution->realDuration        = 'Real Duration';
$lang->execution->openedVersion       = 'Opened Version';
$lang->execution->lastEditedBy        = 'Last EditedBy';
$lang->execution->lastEditedDate      = 'Last EditedDate';
$lang->execution->suspendedDate       = 'Suspended Date';
$lang->execution->vision              = 'Vision';
$lang->execution->displayCards        = 'Max cards per column';
$lang->execution->fluidBoard          = 'Column Width';
$lang->execution->stage               = 'Stage';
$lang->execution->pri                 = 'Priorität';
$lang->execution->openedBy            = 'OpenedBy';
$lang->execution->openedDate          = 'OpenedDate';
$lang->execution->closedBy            = 'ClosedBy';
$lang->execution->closedDate          = 'ClosedDate';
$lang->execution->canceledBy          = 'CanceledBy';
$lang->execution->canceledDate        = 'CanceledDate';
$lang->execution->begin               = 'Start';
$lang->execution->end                 = 'Ende';
$lang->execution->dateRange           = 'Dauer';
$lang->execution->realBeganAB         = 'Actual Begin';
$lang->execution->realEndAB           = 'Actual End';
$lang->execution->teamCount           = 'Anzahl der Personen';
$lang->execution->realBegan           = 'Tatsächlicher Start';
$lang->execution->realEnd             = 'Tatsächliches Ende';
$lang->execution->to                  = 'An';
$lang->execution->days                = 'Manntage';
$lang->execution->day                 = 'Tag';
$lang->execution->workHour            = 'Stunden';
$lang->execution->workHourUnit        = 'H';
$lang->execution->totalHours          = 'Arbeitsstunden';
$lang->execution->totalDays           = 'Arbeitstage';
$lang->execution->status              = 'Status';
$lang->execution->execStatus          = 'Status';
$lang->execution->subStatus           = 'Sub Status';
$lang->execution->desc                = 'Beschreibung';
$lang->execution->execDesc            = 'Description';
$lang->execution->owner               = 'Besitzer';
$lang->execution->PO                  = "{$lang->executionCommon} Owner";
$lang->execution->PM                  = "{$lang->executionCommon} Manager";
$lang->execution->execPM              = "Execution Manager";
$lang->execution->QD                  = 'Test Manager';
$lang->execution->RD                  = 'Release Manager';
$lang->execution->release             = 'Release';
$lang->execution->acl                 = 'Zugriffskontrolle';
$lang->execution->auth                = 'Privileges';
$lang->execution->teamname            = 'Team Name';
$lang->execution->updateOrder         = 'Rank';
$lang->execution->order               = "Sortierung {$lang->executionCommon}";
$lang->execution->orderAB             = "Rank";
$lang->execution->products            = "Verknüpfung {$lang->productCommon}";
$lang->execution->whitelist           = 'Whitelist';
$lang->execution->addWhitelist        = 'Add Whitelist';
$lang->execution->unbindWhitelist     = 'Remove Whitelist';
$lang->execution->totalEstimate       = 'Geplant';
$lang->execution->totalConsumed       = 'Genutzt';
$lang->execution->totalLeft           = 'Rest';
$lang->execution->progress            = 'Fortschritt';
$lang->execution->hours               = '%s geplant, %s verbraucht, %s Rest.';
$lang->execution->viewBug             = 'Bugs';
$lang->execution->noProduct           = "Kein {$lang->productCommon}";
$lang->execution->createStory         = "Story erstellen";
$lang->execution->storyTitle          = "Story Name";
$lang->execution->storyView           = "Story Detail";
$lang->execution->all                 = 'Alle';
$lang->execution->undone              = 'Unabgeschlossen ';
$lang->execution->unclosed            = 'Geschlossen';
$lang->execution->closedExecution     = 'Closed Execution';
$lang->execution->typeDesc            = "Keine {$lang->SRCommon}, Bug, Build, Testaufgabe oder ist bei OPS erlaubt";
$lang->execution->mine                = 'Meine Zuständigkeit: ';
$lang->execution->involved            = 'Mine';
$lang->execution->other               = 'Andere';
$lang->execution->deleted             = 'Gelöscht';
$lang->execution->delayed             = 'Verspätet';
$lang->execution->product             = $lang->execution->products;
$lang->execution->readjustTime        = 'Start und Ende anpassen';
$lang->execution->readjustTask        = 'Fälligkeit der Aufgabe anpassen';
$lang->execution->effort              = 'Aufwand';
$lang->execution->storyEstimate       = 'Story Estimate';
$lang->execution->newEstimate         = 'New Estimate';
$lang->execution->reestimate          = 'Reestimate';
$lang->execution->selectRound         = 'Select Round';
$lang->execution->average             = 'Average';
$lang->execution->relatedMember       = 'Teammitglieder';
$lang->execution->watermark           = 'Exported by ZenTao';
$lang->execution->burnXUnit           = '(Date)';
$lang->execution->burnYUnit           = '(Hours)';
$lang->execution->count               = '(Count)';
$lang->execution->waitTasks           = 'Waiting Tasks';
$lang->execution->viewByUser          = 'By User';
$lang->execution->oneProduct          = "Only one stage can be linked {$lang->productCommon}";
$lang->execution->noLinkProduct       = "Stage not linked {$lang->productCommon}";
$lang->execution->recent              = 'Recent visits: ';
$lang->execution->copyNoExecution     = 'There are no ' . $lang->executionCommon . 'available to copy.';
$lang->execution->noTeam              = 'No team members at the moment';
$lang->execution->or                  = ' or ';
$lang->execution->selectProject       = 'Please select ' . $lang->projectCommon;
$lang->execution->unfoldClosed        = 'Unfold Closed';
$lang->execution->editName            = 'Edit Name';
$lang->execution->setWIP              = 'WIP Settings';
$lang->execution->sortColumn          = 'Kanban Card Sorting';
$lang->execution->batchCreateStory    = "Batch create {$lang->SRCommon}";
$lang->execution->batchCreateTask     = 'Batch create task';
$lang->execution->kanbanNoLinkProduct = "Kanban not linked {$lang->productCommon}";
$lang->execution->myTask              = "My Task";
$lang->execution->list                = "{$lang->executionCommon} List";
$lang->execution->allProject          = 'All';
$lang->execution->method              = 'Management Method';
$lang->execution->sameAsParent        = "Same as parent";

/* Fields of zt_team. */
$lang->execution->root     = 'Root';
$lang->execution->estimate = 'estimate';
$lang->execution->consumed = 'consumed';
$lang->execution->left     = 'Left';

$lang->execution->copyTeamTip        = "copy {$lang->projectCommon}/{$lang->execution->common} team members";
$lang->execution->daysGreaterProject = 'Days cannot be greater than days of execution 『%s』';
$lang->execution->errorHours         = 'Hours/Day cannot be greater than『24』';
$lang->execution->agileplusMethodTip = "When creating executions in an Agile Plus {$lang->projectCommon}, both {$lang->executionCommon} and Kanban management methods are supported.";
$lang->execution->typeTip            = "The sub-stages of other types can be created under the parent stage of the 'mix' type, while the type of other parent-child levels is consistent.";
$lang->execution->waterfallTip       = "In the Waterfall {$lang->projectCommon} or in the Waterfall + {$lang->projectCommon},";

$lang->execution->start    = 'Start';
$lang->execution->activate = 'Aktivieren';
$lang->execution->putoff   = 'Zurückstellen';
$lang->execution->suspend  = 'Aussetzen';
$lang->execution->close    = 'Schließen';
$lang->execution->export   = 'Export';
$lang->execution->next     = "Next";

$lang->execution->endList[7]   = '1 Woche';
$lang->execution->endList[14]  = '2 Wochen';
$lang->execution->endList[31]  = '1 Monat';
$lang->execution->endList[62]  = '2 Monate';
$lang->execution->endList[93]  = '3 Monate';
$lang->execution->endList[186] = '6 Monate';
$lang->execution->endList[365] = '1 Jahr';

$lang->execution->lifeTimeList['short'] = "Short-Term";
$lang->execution->lifeTimeList['long']  = "Long-Term";
$lang->execution->lifeTimeList['ops']   = "DevOps";

$lang->execution->cfdTypeList['story'] = "View by {$lang->SRCommon}";
$lang->execution->cfdTypeList['task']  = "View by task";
$lang->execution->cfdTypeList['bug']   = "View By bug";

$lang->team->account    = 'Konto';
$lang->team->role       = 'Rolle';
$lang->team->roleAB     = 'Meine Rolle';
$lang->team->join       = 'Beigetreten';
$lang->team->hours      = 'Stunde/Tag';
$lang->team->days       = 'Arbeitstage';
$lang->team->totalHours = 'Summe';

$lang->team->limited            = 'Eingeschränkte Benutzer';
$lang->team->limitedList['yes'] = 'Ja';
$lang->team->limitedList['no']  = 'Nein';

$lang->execution->basicInfo = 'Basis Info';
$lang->execution->otherInfo = 'Andere Info';

/* Field value list. */
$lang->execution->statusList['wait']      = 'Wartend';
$lang->execution->statusList['doing']     = 'In Arbeit';
$lang->execution->statusList['suspended'] = 'Ausgesetzt';
$lang->execution->statusList['closed']    = 'Geschlossen';

global $config;
$lang->execution->aclList['private'] = "Private (for team members and {$lang->projectCommon} stakeholders)";
$lang->execution->aclList['open']    = "Inherited {$lang->projectCommon} ACL (for who can access the current {$lang->projectCommon})";

$lang->execution->kanbanAclList['private'] = 'Private';
$lang->execution->kanbanAclList['open']    = "Inherited {$lang->projectCommon}";

$lang->execution->storyPoint = 'Story Point';

$lang->execution->burnByList['left']       = 'View by remaining hours';
$lang->execution->burnByList['estimate']   = "View by plan hours";
$lang->execution->burnByList['storyPoint'] = 'View by story point';

/* Method list. */
$lang->execution->index                     = "Home";
$lang->execution->task                      = 'Aufgaben';
$lang->execution->groupTask                 = 'Nach Gruppen';
$lang->execution->story                     = 'Storys';
$lang->execution->qa                        = 'QA';
$lang->execution->bug                       = 'Bugs';
$lang->execution->testcase                  = 'Testcase List';
$lang->execution->dynamic                   = 'Verlauf';
$lang->execution->latestDynamic             = 'Letzter Verlauf';
$lang->execution->build                     = 'Builds';
$lang->execution->testtask                  = 'Testaufgaben';
$lang->execution->burn                      = 'Burndown';
$lang->execution->computeBurn               = 'Aktualisieren';
$lang->execution->computeCFD                = 'Compute Cumulative Flow diagrams';
$lang->execution->fixFirst                  = 'Bearbeite Mannstunden des ersten Tags';
$lang->execution->team                      = 'Teammitglieder';
$lang->execution->doc                       = 'Dok';
$lang->execution->doclib                    = 'Dok Bibliothek';
$lang->execution->manageProducts            = 'Verküpfe ' . $lang->productCommon;
$lang->execution->linkStory                 = "Link {$lang->SRCommon}";
$lang->execution->linkStoryByPlan           = 'Verküpfe Story aus Plan';
$lang->execution->linkPlan                  = 'Verküpfe Plan';
$lang->execution->unlinkStoryTasks          = 'Verknüpfung aufheben';
$lang->execution->linkedProducts            = 'Verküpfte Produkte';
$lang->execution->unlinkedProducts          = 'Produkt verknüpfung aufheben';
$lang->execution->view                      = "Übersicht";
$lang->execution->startAction               = "Start Execution";
$lang->execution->activateAction            = "Activate Execution";
$lang->execution->delayAction               = "Delay Execution";
$lang->execution->suspendAction             = "Suspend Execution";
$lang->execution->closeAction               = "Close Execution";
$lang->execution->testtaskAction            = "Execution Request";
$lang->execution->teamAction                = "Execution Members";
$lang->execution->kanbanAction              = "Execution Kanban";
$lang->execution->printKanbanAction         = "Print Kanban";
$lang->execution->treeAction                = "Execution Tree View";
$lang->execution->exportAction              = "Export Execution";
$lang->execution->computeBurnAction         = "Compute Burn";
$lang->execution->create                    = "Erstelle Projekt";
$lang->execution->createExec                = "Create Execution";
$lang->execution->createAction              = "Create {$lang->execution->common}";
$lang->execution->copyExec                  = "Copy Execution";
$lang->execution->copy                      = "Kopiere {$lang->executionCommon}";
$lang->execution->delete                    = "Lösche";
$lang->execution->deleteAB                  = "Lösche Execution";
$lang->execution->browse                    = "Durchsuchen";
$lang->execution->edit                      = "Bearbeiten";
$lang->execution->editAction                = "Edit Execution";
$lang->execution->batchEdit                 = "Mehere bearbeiten";
$lang->execution->batchEditAction           = "Batch Edit";
$lang->execution->batchChangeStatus         = "Batch Change Status";
$lang->execution->manageMembers             = 'Teams verwalten';
$lang->execution->unlinkMember              = 'Mitgliefer entfernen';
$lang->execution->unlinkStory               = 'Story entfernen';
$lang->execution->unlinkStoryAB             = 'Unlink';
$lang->execution->batchUnlinkStory          = 'Mehere Storys entfernen';
$lang->execution->importTask                = 'Importiere Aufgaben';
$lang->execution->importPlanStories         = 'Verknüpfe Story aus Plan';
$lang->execution->importBug                 = 'Importiere Bugs';
$lang->execution->tree                      = 'Baum';
$lang->execution->treeTask                  = 'Aufgabe anzeigen';
$lang->execution->treeStory                 = 'Story anzeigen';
$lang->execution->treeViewTask              = 'Tree View Task';
$lang->execution->treeViewStory             = 'Tree View Story';
$lang->execution->storyKanban               = 'Story Kanban';
$lang->execution->storySort                 = 'Story sortieren';
$lang->execution->importPlanStory           = $lang->executionCommon . ' is created!\nDo you want to import stories that have been linked to the plan? Only active ' . $lang->SRCommon . ' can be imported.';
$lang->execution->importEditPlanStory       = $lang->executionCommon . ' is edited!\nDo you want to import stories that have been linked to the plan? The stories in the draft will be automatically filtered out when imported.';
$lang->execution->importBranchPlanStory     = $lang->executionCommon . ' is created!\nDo you want to import stories that have been linked to the plan? Only the activation stories of the branch associated with this ' .$lang->executionCommon. ' will be associated with the import';
$lang->execution->importBranchEditPlanStory = $lang->executionCommon . ' is edited!\nDo you want to import stories that have been linked to the plan? Only the activation stories of the branch associated with this ' .$lang->executionCommon. ' will be associated with the import';
$lang->execution->needLinkProducts          = "The execution has not been linked with any {$lang->productCommon}, and the related functions cannot be used. Please link the {$lang->productCommon} first and try again.";
$lang->execution->iteration                 = 'Iteration';
$lang->execution->iterationInfo             = '%s Iterationen';
$lang->execution->viewAll                   = 'Alle anzeigen';
$lang->execution->testreport                = 'Test Report';
$lang->execution->taskKanban                = 'Task Kanban';
$lang->execution->RDKanban                  = 'Research & Development Kanban';

/* Group browsing. */
$lang->execution->allTasks     = 'Alle';
$lang->execution->assignedToMe = 'Meine';
$lang->execution->myInvolved   = 'Beteiligt';
$lang->execution->assignedByMe = 'AssignedByMe';

$lang->execution->statusSelects['']             = 'Mehr';
$lang->execution->statusSelects['wait']         = 'Wartend';
$lang->execution->statusSelects['doing']        = 'In Arbeit';
$lang->execution->statusSelects['undone']       = 'Undone';
$lang->execution->statusSelects['finishedbyme'] = 'Von mir abgeschlossen';
$lang->execution->statusSelects['done']         = 'Erledigt';
$lang->execution->statusSelects['closed']       = 'Geschlossen';
$lang->execution->statusSelects['cancel']       = 'Abgebrochen';
$lang->execution->statusSelects['delayed']      = 'Delayed';

$lang->execution->groups['']           = 'Gruppen';
$lang->execution->groups['story']      = 'Nach Story';
$lang->execution->groups['status']     = 'Nach Status';
$lang->execution->groups['pri']        = 'Nach Priorität';
$lang->execution->groups['assignedTo'] = 'Nach Zuweisung an';
$lang->execution->groups['finishedBy'] = 'Nach abgeschlossen von';
$lang->execution->groups['closedBy']   = 'Nach geschlossen von';
$lang->execution->groups['type']       = 'Nach Typ';

$lang->execution->groupFilter['story']['all']         = $lang->execution->all;
$lang->execution->groupFilter['story']['linked']      = 'Aufgaben verknüpft mit Story';
$lang->execution->groupFilter['pri']['all']           = $lang->execution->all;
$lang->execution->groupFilter['pri']['noset']         = 'Not gesetzt';
$lang->execution->groupFilter['assignedTo']['undone'] = 'Unabgeschlossen';
$lang->execution->groupFilter['assignedTo']['all']    = $lang->execution->all;

$lang->execution->byQuery = 'Suche';

/* Query condition list. */
$lang->execution->allExecution      = "Alle {$lang->executionCommon}";
$lang->execution->aboveAllProduct   = "Alle oberen {$lang->productCommon}";
$lang->execution->aboveAllExecution = "Alle oberen {$lang->executionCommon}";

/* Page prompt. */
$lang->execution->linkStoryByPlanTips  = "This action will link all stories in this plan to the {$lang->executionCommon}.";
$lang->execution->batchCreateStoryTips = "Please select the {$lang->productCommon} that needs to be created in batches";
$lang->execution->selectExecution      = "Auswahl {$lang->executionCommon}";
$lang->execution->beginAndEnd          = 'Dauer';
$lang->execution->lblStats             = 'Mannstunden Summe(h) : ';
$lang->execution->stats                = '<strong>%s</strong> Verfügbar, <strong>%s</strong> geplant, <strong>%s</strong> genutzt, <strong>%s</strong> Rest.';
$lang->execution->taskSummary          = "Aufgaben auf dieser Seite: <strong>%s</strong> Total, <strong>%s</strong> Wartend, <strong>%s</strong> In Arbeit;  &nbsp;&nbsp;&nbsp;  Stunden : <strong>%s</strong> geplant., <strong>%s</strong> genutzt, <strong>%s</strong> Rest.";
$lang->execution->pageSummary          = "Aufgaben auf dieser Seite:  <strong>%total%</strong>, <strong>%wait%</strong> Wartend, <strong>%doing%</strong> In Arbeit;    Stunden: <strong>%estimate%</strong>  geplant, <strong>%consumed%</strong> genutzt, <strong>%left%</strong> Rest.";
$lang->execution->checkedSummary       = " <strong>%total%</strong> Geprüft, <strong>%wait%</strong> Wartend, <strong>%doing%</strong> In Arbeit;    Stunden: <strong>%estimate%</strong>  geplant, <strong>%consumed%</strong> genutzt, <strong>%left%</strong> Rest.";
$lang->execution->executionSummary     = "Total executions: <strong>%s</strong>.";
$lang->execution->pageExecSummary      = "Total executions: <strong>%total%</strong>. Waiting: <strong>%wait%</strong>. Doing: <strong>%doing%</strong>.";
$lang->execution->checkedExecSummary   = "Selected: <strong>%total%</strong>. Waiting: <strong>%wait%</strong>. Doing: <strong>%doing%</strong>.";
$lang->execution->memberHoursAB        = "%s hat <strong>%s</strong> Stunden";
$lang->execution->memberHours          = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">%s Arbeitsstunden</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->countSummary         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Aufgaben</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">In Arbeit</div><div class="segment-value"><span class="label label-dot label-primary"></span> %s</div></div><div class="segment"><div class="segment-title">Wait</div><div class="segment-value"><span class="label label-dot label-primary muted"></span> %s</div></div></div></div>';
$lang->execution->timeSummary          = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Geplant</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">Genutzt</div><div class="segment-value text-red">%s</div></div><div class="segment"><div class="segment-title">Rest</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->groupSummaryAB       = "<div>Aufgaben <strong>%s</strong></div><div><span class='text-muted'>Wartend</span> %s &nbsp; <span class='text-muted'>In Arbeit</span> %s</div><div>Geplant <strong>%s</strong></div><div><span class='text-muted'>Genutzt</span> %s &nbsp; <span class='text-muted'>Rest</span> %s</div>";
$lang->execution->wbs                  = "Aufgaben aufteilen";
$lang->execution->batchWBS             = "Mehrere aufteilen";
$lang->execution->howToUpdateBurn      = "<a href='https://api.zentao.pm/goto.php?item=burndown' target='_blank' title='Wie wird der Burndown Chart aktualisiert?' class='btn btn-link'>Hilfe <i class='icon icon-help'></i></a>";
$lang->execution->whyNoStories         = "Keine Story kann verknüpft werden. Bitte prüfen Sie ob ein Story mit {$lang->executionCommon} verknüpft ist {$lang->productCommon} und stellen Sie sicher das diese geprüft ist.";
$lang->execution->projectNoStories     = "No story can be linked. Please check whether there is any story in {$lang->projectCommon} and make sure it has been reviewed.";
$lang->execution->productStories       = "{$lang->executionCommon} verknüpfte Story ist ein Subset von {$lang->productCommon}, welche nur nach überprüfung verknüpft werden kann. Bitte <a href='%s'> Story verknüpfen</a>。";
$lang->execution->haveBranchDraft      = "There are %s draft stories or not associated with this {$lang->executionCommon} can't be linked.";
$lang->execution->haveDraft            = "There are %s draft stories with this {$lang->executionCommon} can't be linked.";
$lang->execution->doneExecutions       = 'Erledigt';
$lang->execution->selectDept           = 'Abteilung wählen';
$lang->execution->selectDeptTitle      = 'Abteilung wählen';
$lang->execution->copyTeam             = 'Team kopieren';
$lang->execution->copyFromTeam         = "Kopieren von {$lang->executionCommon} Team: <strong>%s</strong>";
$lang->execution->noMatched            = "$lang->executionCommon mit '%s' konnte nicht gefunden werden.";
$lang->execution->copyTitle            = "Wählen Sie ein {$lang->executionCommon} zum Kopieren.";
$lang->execution->copyNoExecution      = 'There are no ' . $lang->executionCommon . 'available to copy.';
$lang->execution->copyFromExecution    = "Kopie von {$lang->executionCommon} <strong>%s</strong>";
$lang->execution->cancelCopy           = 'Kopieren abbrechen';
$lang->execution->byPeriod             = 'Nach Zeit';
$lang->execution->byUser               = 'Nach Benutzer';
$lang->execution->noExecution          = 'Keine Projekte. ';
$lang->execution->noExecutions         = "No {$lang->execution->common}.";
$lang->execution->noPrintData          = "No data can be printed.";
$lang->execution->noMembers            = 'Keine Mitglieder. ';
$lang->execution->workloadTotal        = "The cumulative workload ratio should not exceed 100%s, and the total workload under the current {$lang->productCommon} is: %s";
$lang->execution->linkAllStoryTip      = "({$lang->SRCommon} has never been linked under the {$lang->projectCommon}, and can be directly linked with {$lang->SRCommon} of the {$lang->productCommon} linked with the sprint/stage)";
$lang->execution->copyTeamTitle        = "Choose a {$lang->project->common} or {$lang->execution->common} Team to copy.";

/* Interactive prompts. */
$lang->execution->confirmDelete                = "Möchten Sie {$lang->executionCommon}[%s] löschen?";
$lang->execution->confirmUnlinkMember          = "Möchten Sie den Benutzer vom {$lang->executionCommon} entfernen?";
$lang->execution->confirmUnlinkStory           = "After {$lang->SRCommon} is removed, cased linked to {$lang->SRCommon} will be reomoved and tasks linked to {$lang->SRCommon} will be cancelled. Do you want to continue?";
$lang->execution->confirmSync                  = "After modifying the {$lang->projectCommon}, in order to maintain the consistency of data, the data of {$lang->productCommon}s, {$lang->SRCommon}s, teams and whitelist associated with the implementation will be synchronized to the new {$lang->projectCommon}. Please know.";
$lang->execution->confirmUnlinkExecutionStory  = "Do you want to unlink this Story from the {$lang->projectCommon}?";
$lang->execution->notAllowedUnlinkStory        = "This {$lang->SRCommon} is linked to the {$lang->executionCommon} of the {$lang->projectCommon}. Remove it from the {$lang->executionCommon}, then try again.";
$lang->execution->notAllowRemoveProducts       = "The story of this product is linked with the {$lang->executionCommon}. Unlink it before doing any action.";
$lang->execution->errorNoLinkedProducts        = "Kein verknüpftes {$lang->productCommon} in {$lang->executionCommon} gefunden. Sie werden auf die {$lang->productCommon} Seite geleitet.";
$lang->execution->errorSameProducts            = "{$lang->executionCommon} Kann nicht mit mehreren identischen {$lang->productCommon} verknüpft werden";
$lang->execution->errorSameBranches            = "{$lang->executionCommon} cannot be linked to the same branch twice";
$lang->execution->errorBegin                   = "The start time of {$lang->executionCommon} cannot be less than the start time of the {$lang->projectCommon} %s.";
$lang->execution->errorEnd                     = "The end time of {$lang->executionCommon} cannot be greater than the end time %s of the {$lang->projectCommon}.";
$lang->execution->errorLetterProject           = "The start time of {$lang->executionCommon} cannot be less than the start time of the {$lang->projectCommon} %s.";
$lang->execution->errorGreaterProject          = "The end time of {$lang->executionCommon} cannot be greater than the end time %s of the {$lang->projectCommon}.";
$lang->execution->errorCommonBegin             = "The start date of ' . $lang->executionCommon . ' should be ≥ the start date of {$lang->projectCommon} : %s.";
$lang->execution->errorCommonEnd               = "The deadline of ' . $lang->executionCommon .  ' should be ≤ the deadline of {$lang->projectCommon} : %s.";
$lang->execution->errorLetterParent            = 'The begin cannot be less than the begin of the parent stage to which it belongs: %s.';
$lang->execution->errorGreaterParent           = 'The end cannot be greater than the end of the parent stage to which it belongs：%s.';
$lang->execution->errorNameRepeat              = "Child %s of the same parent stage cannot have the same name.";
$lang->execution->errorAttrMatch               = "Parent stage's attribute is [%s], the attribute needs to be consistent with the parent stage.";
$lang->execution->errorLetterPlan              = "『%s』cannot be less than the plan start time『%s』。";
$lang->execution->accessDenied                 = "Zugriff zu {$lang->executionCommon} verweigert!";
$lang->execution->tips                         = 'Hinweis';
$lang->execution->afterInfo                    = "{$lang->executionCommon} wurde erstellt. Als nächstes können Sie ";
$lang->execution->setTeam                      = 'Team setzen';
$lang->execution->createTask                   = 'Aufgaben erstellen';
$lang->execution->goback                       = "Zurückkehren";
$lang->execution->gobackExecution              = "Go Back {$lang->executionCommon} List";
$lang->execution->noweekend                    = 'Ohne Wochenende';
$lang->execution->nodelay                      = 'Exclude Delay Date';
$lang->execution->withweekend                  = 'Mit Wochenende';
$lang->execution->withdelay                    = 'Include Delay Date';
$lang->execution->interval                     = 'Intervale ';
$lang->execution->fixFirstWithLeft             = 'Modify the left';
$lang->execution->unfinishedExecution          = "This {$lang->executionCommon} has ";
$lang->execution->unfinishedTask               = "[%s] unfinished tasks. ";
$lang->execution->unresolvedBug                = "[%s] unresolved bugs. ";
$lang->execution->projectNotEmpty              = "{$lang->projectCommon} cannot be empty.";
$lang->execution->confirmStoryToTask           = $lang->SRCommon . '%s are converted to tasks in the current. Do you want to convert them anyways?';
$lang->execution->ge                           = "『%s』should be >= actual begin『%s』.";
$lang->execution->storyDragError               = "The {$lang->SRCommon} is not active. Please activate and drag again.";
$lang->execution->countTip                     = ' (%s member)';
$lang->execution->pleaseInput                  = "Enter";
$lang->execution->week                         = 'week';
$lang->execution->checkedExecutions            = "Seleted %s {$lang->executionCommon}.";
$lang->execution->hasStartedTaskOrSubStage     = "Tasks or subphases under %s %s have already started, cannot be modified, and have been filtered.";
$lang->execution->hasSuspendedOrClosedChildren = "The sub-stages under stage %s are not all suspended or closed, cannot be modified, and have been filtered.";
$lang->execution->hasNotClosedChildren         = "The sub-stages under stage %s are not all closed, cannot be modified, and have been filtered.";
$lang->execution->hasStartedTask               = "The task under %s %s has already started, cannot be modified, and has been filtered.";
$lang->execution->cannotManageProducts         = 'The ' . strtolower($lang->project->common). ' model of this ' . strtolower($lang->execution->common) . " is %s and this " . strtolower($lang->execution->common) . " cannot be associated with {$lang->productCommon}.";

/* Statistics. */
$lang->execution->charts = new stdclass();
$lang->execution->charts->burn = new stdclass();
$lang->execution->charts->burn->graph = new stdclass();
$lang->execution->charts->burn->graph->caption      = "Burndown";
$lang->execution->charts->burn->graph->xAxisName    = "Datum";
$lang->execution->charts->burn->graph->yAxisName    = "Stunde";
$lang->execution->charts->burn->graph->baseFontSize = 12;
$lang->execution->charts->burn->graph->formatNumber = 0;
$lang->execution->charts->burn->graph->animation    = 0;
$lang->execution->charts->burn->graph->rotateNames  = 1;
$lang->execution->charts->burn->graph->showValues   = 0;
$lang->execution->charts->burn->graph->reference    = 'Referenz';
$lang->execution->charts->burn->graph->actuality    = 'Aktualität';
$lang->execution->charts->burn->graph->delay        = 'Delay';

$lang->execution->charts->cfd = new stdclass();
$lang->execution->charts->cfd->cfdTip        = "<p>
1. The CFD（Cumulative Flow Diagram）reflects the trend of accumulated workload at each stage over time.<br>
2. The horizontal axis represents the date, and the vertical axis represents the number of work items.<br>
3. To learn about the team's delivery, you can calculate the WIP quantity, delivery rate and average lead time through the CFD. <p>";
$lang->execution->charts->cfd->cycleTime     = 'Average cycle time';
$lang->execution->charts->cfd->cycleTimeTip  = 'Average cycle time of each card from development start to completion';
$lang->execution->charts->cfd->throughput    = 'Throughput Rate';
$lang->execution->charts->cfd->throughputTip = 'Throughput Rate = WIP / Average cycle time';

$lang->execution->charts->cfd->begin          = 'Begin';
$lang->execution->charts->cfd->end            = 'End';
$lang->execution->charts->cfd->errorBegin     = 'The start time cannot be greater than the end time.';
$lang->execution->charts->cfd->errorDateRange = 'The Cumulative Flow Diagram（CFD） only provides data display within 3 months.';
$lang->execution->charts->cfd->dateRangeTip   = 'CFD only shows the data within 3 months';

$lang->execution->placeholder = new stdclass();
$lang->execution->placeholder->code      = 'Abkurzung des Projektnamens';
$lang->execution->placeholder->totalLeft = 'Schätzungen zu Beginn des Projekts.';

$lang->execution->selectGroup = new stdclass();
$lang->execution->selectGroup->done = '(Erledigt)';

$lang->execution->orderList['order_asc']  = "Aufsteigend";
$lang->execution->orderList['order_desc'] = "Absteigend";
$lang->execution->orderList['pri_asc']    = "Priorität Auf.";
$lang->execution->orderList['pri_desc']   = "Priorität Ab.";
$lang->execution->orderList['stage_asc']  = "Phase Auf.";
$lang->execution->orderList['stage_desc'] = "Phase Ab.";

$lang->execution->kanban        = "Kanban";
$lang->execution->kanbanSetting = "Kanban Einstellung";
$lang->execution->setKanban     = "Kanban Einstellung";
$lang->execution->resetKanban   = "Einstellungen zurücksetzen";
$lang->execution->printKanban   = "drucken Kanban";
$lang->execution->fullScreen    = "Full Screen";
$lang->execution->bugList       = "Bugs";

$lang->execution->kanbanHideCols   = 'Geschlossene und abgebrochene Spalten in Kanban verstecken';
$lang->execution->kanbanShowOption = 'Aufklappen';
$lang->execution->kanbanColsColor  = 'Spaltenfarben';
$lang->execution->kanbanCardsUnit  = 'X';

$lang->execution->kanbanViewList['all']   = 'All';
$lang->execution->kanbanViewList['story'] = "{$lang->SRCommon}";
$lang->execution->kanbanViewList['bug']   = 'Bug';
$lang->execution->kanbanViewList['task']  = 'Task';

$lang->execution->teamWords  = 'Team';

$lang->kanbanSetting = new stdclass();
$lang->kanbanSetting->noticeReset     = 'Möchten Sie die Einstellungen des Kanbans zurücksetzen?';
$lang->kanbanSetting->optionList['0'] = 'Verstecken';
$lang->kanbanSetting->optionList['1'] = 'Anzeigen';

$lang->printKanban = new stdclass();
$lang->printKanban->common  = 'drucken Kanban';
$lang->printKanban->content = 'Inhanlt';
$lang->printKanban->print   = 'Drucken';

$lang->printKanban->taskStatus = 'Status';

$lang->printKanban->typeList['all']       = 'Alle';
$lang->printKanban->typeList['increment'] = 'Erhöhen';

$lang->execution->typeList['']       = '';
$lang->execution->typeList['stage']  = 'Stage';
$lang->execution->typeList['sprint'] = $lang->executionCommon;
$lang->execution->typeList['kanban'] = 'Kanban';

$lang->execution->featureBar['task']['all']          = $lang->execution->allTasks;
$lang->execution->featureBar['task']['unclosed']     = $lang->execution->unclosed;
$lang->execution->featureBar['task']['assignedtome'] = $lang->execution->assignedToMe;
$lang->execution->featureBar['task']['myinvolved']   = $lang->execution->myInvolved;
$lang->execution->featureBar['task']['assignedbyme'] = $lang->execution->assignedByMe;
$lang->execution->featureBar['task']['needconfirm']  = 'Story geändert';
$lang->execution->featureBar['task']['status']       = $lang->more;

$lang->execution->moreSelects['task']['status']['wait']         = 'Waiting';
$lang->execution->moreSelects['task']['status']['doing']        = 'Doing';
$lang->execution->moreSelects['task']['status']['undone']       = 'Unfinished';
$lang->execution->moreSelects['task']['status']['finishedbyme'] = 'FinishedByMe';
$lang->execution->moreSelects['task']['status']['done']         = 'Done';
$lang->execution->moreSelects['task']['status']['closed']       = 'Closed';
$lang->execution->moreSelects['task']['status']['cancel']       = 'Cancelled';
$lang->execution->moreSelects['task']['status']['delayed']      = 'Delayed';

$lang->execution->featureBar['all']['all']       = $lang->execution->all;
$lang->execution->featureBar['all']['undone']    = $lang->execution->undone;
$lang->execution->featureBar['all']['wait']      = $lang->execution->statusList['wait'];
$lang->execution->featureBar['all']['doing']     = $lang->execution->statusList['doing'];
$lang->execution->featureBar['all']['suspended'] = $lang->execution->statusList['suspended'];
$lang->execution->featureBar['all']['closed']    = $lang->execution->statusList['closed'];

$lang->execution->featureBar['bug']['all']        = 'All';
$lang->execution->featureBar['bug']['unresolved'] = 'Active';

$lang->execution->featureBar['build']['all'] = 'Build List';

$lang->execution->featureBar['story']['all']       = 'All';
$lang->execution->featureBar['story']['unclosed']  = 'Unclosed';
$lang->execution->featureBar['story']['draft']     = 'Draft';
$lang->execution->featureBar['story']['reviewing'] = 'Reviewing';

$lang->execution->featureBar['testcase']['all'] = 'All';

$lang->execution->myExecutions = 'Ich bin beteiligt.';
$lang->execution->doingProject = "Ongoing {$lang->projectCommon}s";

$lang->execution->kanbanColType['wait']      = $lang->execution->statusList['wait']     . ' ' . $lang->execution->common;
$lang->execution->kanbanColType['doing']     = $lang->execution->statusList['doing']    . ' ' . $lang->execution->common;
$lang->execution->kanbanColType['suspended'] = $lang->execution->statusList['suspended']. ' ' . $lang->execution->common;
$lang->execution->kanbanColType['closed']    = $lang->execution->statusList['closed']   . ' ' . $lang->execution->common;

$lang->execution->treeLevel = array();
$lang->execution->treeLevel['all']   = 'Alle aufklappen';
$lang->execution->treeLevel['root']  = 'Alle zuklappen';
$lang->execution->treeLevel['task']  = 'Aufgabe anzeigen';
$lang->execution->treeLevel['story'] = 'Story anzeigen';

$lang->execution->action = new stdclass();
$lang->execution->action->opened               = '$date, created by <strong>$actor</strong>. $extra' . "\n";
$lang->execution->action->managed              = '$date, managed by <strong>$actor</strong>. $extra' . "\n";
$lang->execution->action->edited               = '$date, edited by <strong>$actor</strong>. $extra' . "\n";
$lang->execution->action->extra                = "Linked {$lang->productCommon}s is %s.";
$lang->execution->action->startbychildactivate = '$date, activating the sub stage sets the execution status as Ongoing.' . "\n";
$lang->execution->action->waitbychilddelete    = '$date, deleting the sub stage sets the execution status as waitting.' . "\n";
$lang->execution->action->closebychilddelete   = '$date, deleting the sub stage sets the execution status as closing.' . "\n";
$lang->execution->action->closebychildclose    = '$date, closing the sub stage sets the execution status as closing.' . "\n";
$lang->execution->action->waitbychild          = '$date, the stage status is <strong>Wait</strong> as the system judges that all its sub-stages statuses are <strong>Wait</strong>.';
$lang->execution->action->suspendedbychild     = '$date, the stage status is <strong>Suspended</strong> as the system judges that all its sub-stages statuses are <strong>Suspended</strong>.';
$lang->execution->action->closedbychild        = '$date, the stage status is <strong>Closed</strong> as the system judges that all its sub-stages are <strong>Closed</strong>.';
$lang->execution->action->startbychildstart    = '$date, the stage status is <strong>Doing</strong> as the system judges that its sub-stages are <strong>Started</strong>.';
$lang->execution->action->startbychildactivate = '$date, the stage status is <strong>Doing</strong> as the system judges that its sub-stages are <strong>Activated</strong>.';
$lang->execution->action->startbychildsuspend  = '$date, the stage status is <strong>Doing</strong> as the system judges that its sub-stages are <strong>Suspended</strong>.';
$lang->execution->action->startbychildclose    = '$date, the stage status is <strong>Doing</strong> as the system judges that its sub-stages are <strong>Closed</strong>.';
$lang->execution->action->startbychildcreate   = '$date, the stage status is <strong>Doing</strong> as the system judges that its sub-stages are <strong>Created</strong>. ';
$lang->execution->action->startbychildedit     = '$date, the stage status is <strong>Doing</strong> as the system judges that its sub-stages are <strong>Edited</strong>';
$lang->execution->action->startbychild         = '$date, the stage status is <strong>Doing</strong> as the system judges that its sub-stages are <strong>Activated</strong>.';
$lang->execution->action->waitbychild          = '$date, the stage status is <strong>Wait</strong> as the system judges that its sub-stages are <strong>Edited</strong>';
$lang->execution->action->suspendbychild       = '$date, the stage status is <strong>Suspended</strong> as the system judges that its sub-stages are <strong>Edited</strong>';
$lang->execution->action->closebychild         = '$date, the stage status is <strong>Closed</strong> as the system judges that its sub-stages are <strong>Edited</strong>';

$lang->execution->startbychildactivate = 'activated';
$lang->execution->waitbychilddelete    = 'stop';
$lang->execution->closebychilddelete   = 'closed';
$lang->execution->closebychildclose    = 'closed';
$lang->execution->waitbychild          = 'activated';
$lang->execution->suspendedbychild     = 'suspended';
$lang->execution->closedbychild        = 'closed';
$lang->execution->startbychildstart    = 'started';
$lang->execution->startbychildactivate = 'activated';
$lang->execution->startbychildsuspend  = 'activated';
$lang->execution->startbychildclose    = 'activated';
$lang->execution->startbychildcreate   = 'activated';
$lang->execution->startbychildedit     = 'activated';
$lang->execution->startbychild         = 'activated';
$lang->execution->waitbychild          = 'stop';
$lang->execution->suspendbychild       = 'suspended';
$lang->execution->closebychild         = 'closed';

$lang->execution->statusColorList = array();
$lang->execution->statusColorList['wait']      = '#0991FF';
$lang->execution->statusColorList['doing']     = '#0BD986';
$lang->execution->statusColorList['suspended'] = '#fdc137';
$lang->execution->statusColorList['closed']    = '#838A9D';

if(!isset($lang->execution->gantt)) $lang->execution->gantt = new stdclass();
$lang->execution->gantt->progressColor[0] = '#B7B7B7';
$lang->execution->gantt->progressColor[1] = '#FF8287';
$lang->execution->gantt->progressColor[2] = '#FFC73A';
$lang->execution->gantt->progressColor[3] = '#6BD5F5';
$lang->execution->gantt->progressColor[4] = '#9DE88A';
$lang->execution->gantt->progressColor[5] = '#9BA8FF';

$lang->execution->gantt->color[0] = '#E7E7E7';
$lang->execution->gantt->color[1] = '#FFDADB';
$lang->execution->gantt->color[2] = '#FCECC1';
$lang->execution->gantt->color[3] = '#D3F3FD';
$lang->execution->gantt->color[4] = '#DFF5D9';
$lang->execution->gantt->color[5] = '#EBDCF9';

$lang->execution->gantt->textColor[0] = '#2D2D2D';
$lang->execution->gantt->textColor[1] = '#8D0308';
$lang->execution->gantt->textColor[2] = '#9D4200';
$lang->execution->gantt->textColor[3] = '#006D8E';
$lang->execution->gantt->textColor[4] = '#1A8100';
$lang->execution->gantt->textColor[5] = '#660ABC';

$lang->execution->gantt->stage = new stdclass();
$lang->execution->gantt->stage->progressColor = '#70B8FE';
$lang->execution->gantt->stage->color         = '#D2E7FC';
$lang->execution->gantt->stage->textColor     = '#0050A7';

$lang->execution->gantt->defaultColor         = '#EBDCF9';
$lang->execution->gantt->defaultProgressColor = '#9BA8FF';
$lang->execution->gantt->defaultTextColor     = '#660ABC';

$lang->execution->gantt->bar_height = '24';

$lang->execution->gantt->exportImg  = 'Export as Image';
$lang->execution->gantt->exportPDF  = 'Export as PDF';
$lang->execution->gantt->exporting  = 'Exporting...';
$lang->execution->gantt->exportFail = 'Failed to export.';

$lang->execution->boardColorList = array('#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#7FBB00', '#424BAC', '#66c5f8', '#EC2761');

$lang->execution->linkBranchStoryByPlanTips = "When a scheduled association requirement is executed, only the active requirements associated with the %s of this execution are imported.";
$lang->execution->linkNormalStoryByPlanTips = "Only the active requirements are imported when the scheduled requirements are associated.";

$lang->execution->featureBar['dynamic']['all']       = 'All';
$lang->execution->featureBar['dynamic']['today']     = 'Today';
$lang->execution->featureBar['dynamic']['yesterday'] = 'Yesterday';
$lang->execution->featureBar['dynamic']['thisWeek']  = 'This Week';
$lang->execution->featureBar['dynamic']['lastWeek']  = 'Last Week';
$lang->execution->featureBar['dynamic']['thisMonth'] = 'This Month';
$lang->execution->featureBar['dynamic']['lastMonth'] = 'Last Month';
