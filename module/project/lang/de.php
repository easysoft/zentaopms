<?php
/**
 * The project module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: en.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
/* Fields. */
$lang->project->common        = $lang->projectCommon;
$lang->project->allProjects   = 'Alle';
$lang->project->id            = $lang->projectCommon . ' ID';
$lang->project->type          = 'Typ';
$lang->project->name          = 'Name';
$lang->project->code          = 'Alias';
$lang->project->statge        = 'Stage';
$lang->project->pri           = 'Priorität';
$lang->project->openedBy      = 'OpenedBy';
$lang->project->openedDate    = 'OpenedDate';
$lang->project->closedBy      = 'ClosedBy';
$lang->project->closedDate    = 'ClosedDate';
$lang->project->canceledBy    = 'CanceledBy';
$lang->project->canceledDate  = 'CanceledDate';
$lang->project->begin         = 'Start';
$lang->project->end           = 'Ende';
$lang->project->dateRange     = 'Dauer';
$lang->project->to            = 'An';
$lang->project->days          = 'Manntage';
$lang->project->day           = 'Tag';
$lang->project->workHour      = 'Stunden';
$lang->project->totalHours    = 'Arbeitsstunden';
$lang->project->totalDays     = 'Arbeitstage';
$lang->project->status        = 'Status';
$lang->project->subStatus     = 'Sub Status';
$lang->project->desc          = 'Beschreibung';
$lang->project->owner         = 'Besitzer';
$lang->project->PO            = 'PO';
$lang->project->PM            = 'PM';
$lang->project->QD            = 'QS Manager';
$lang->project->RD            = 'Release Manager';
$lang->project->qa            = 'Test';
$lang->project->release       = 'Release';
$lang->project->acl           = 'Zugriffskontrolle';
$lang->project->teamname      = 'Team Name';
$lang->project->order         = "Sortierung {$lang->projectCommon}";
$lang->project->orderAB       = "Rank";
$lang->project->products      = "Verknüpfung {$lang->productCommon}";
$lang->project->whitelist     = 'Whitelist';
$lang->project->totalEstimate = 'Geplant';
$lang->project->totalConsumed = 'Genutzt';
$lang->project->totalLeft     = 'Rest';
$lang->project->progress      = 'Fortschritt';
$lang->project->hours         = '%s geplant, %s verbraucht, %s Rest.';
$lang->project->viewBug       = 'Bugs';
$lang->project->noProduct     = "Kein {$lang->productCommon}";
$lang->project->createStory   = "Story erstellen";
$lang->project->all           = 'Alle';
$lang->project->undone        = 'Unabgeschlossen ';
$lang->project->unclosed      = 'Geschlossen';
$lang->project->typeDesc      = "Keine {$lang->storyCommon}, Bug, Build, Testaufgabe oder ist bei OPS erlaubt";
$lang->project->mine          = 'Meine Zuständigkeit: ';
$lang->project->other         = 'Andere:';
$lang->project->deleted       = 'Gelöscht';
$lang->project->delayed       = 'Verspätet';
$lang->project->product       = $lang->project->products;
$lang->project->readjustTime  = 'Start und Ende anpassen';
$lang->project->readjustTask  = 'Fälligkeit der Aufgabe anpassen';
$lang->project->effort        = 'Aufwand';
$lang->project->relatedMember = 'Teammitglieder';
$lang->project->watermark     = 'Exported by ZenTao';
$lang->project->viewByUser    = 'By User';

$lang->project->start    = 'Start';
$lang->project->activate = 'Aktivieren';
$lang->project->putoff   = 'Zurückstellen';
$lang->project->suspend  = 'Aussetzen';
$lang->project->close    = 'Schließen';
$lang->project->export   = 'Export';

$lang->project->typeList['sprint']    = 'Sprint';
$lang->project->typeList['waterfall'] = 'Waterfall';
$lang->project->typeList['ops']       = 'OPS';

$lang->project->endList[7]   = '1 Woche';
$lang->project->endList[14]  = '2 Wochen';
$lang->project->endList[31]  = '1 Monat';
$lang->project->endList[62]  = '2 Monate';
$lang->project->endList[93]  = '3 Monate';
$lang->project->endList[186] = '6 Monate';
$lang->project->endList[365] = '1 Jahr';

$lang->team = new stdclass();
$lang->team->account    = 'Konto';
$lang->team->role       = 'Rolle';
$lang->team->join       = 'Beigetreten';
$lang->team->hours      = 'Stunde/Tag';
$lang->team->days       = 'Arbeitstage';
$lang->team->totalHours = 'Summe';

$lang->team->limited            = 'Eingeschränkte Benutzer';
$lang->team->limitedList['yes'] = 'Ja';
$lang->team->limitedList['no']  = 'Nein';

$lang->project->basicInfo = 'Basis Info';
$lang->project->otherInfo = 'Andere Info';

/* 字段取值列表。*/
$lang->project->statusList['wait']      = 'Wartend';
$lang->project->statusList['doing']     = 'In Arbeit';
$lang->project->statusList['suspended'] = 'Ausgesetzt';
$lang->project->statusList['closed']    = 'Geschlossen';

$lang->project->aclList['open']    = "Standard (Benutzer mit Rechten für Projekte können zugreifen.)";
$lang->project->aclList['private'] = "Privat (Nur Teammitglieder)";
$lang->project->aclList['custom']  = 'Benutzerdefiniert (Teammitglieder und Whitelist Benutzer haben Zugriff.)';

/* 方法列表。*/
$lang->project->index             = "Home";
$lang->project->task              = 'Aufgaben';
$lang->project->groupTask         = 'Nach Gruppen';
$lang->project->story             = 'Storys';
$lang->project->bug               = 'Bugs';
$lang->project->dynamic           = 'Verlauf';
$lang->project->latestDynamic     = 'Letzter Verlauf';
$lang->project->build             = 'Builds';
$lang->project->testtask          = 'Testaufgaben';
$lang->project->burn              = 'Burndown';
$lang->project->computeBurn       = 'Aktualisieren';
$lang->project->burnData          = 'Burndown Daten';
$lang->project->fixFirst          = 'Bearbeite Mannstunden des ersten Tags';
$lang->project->team              = 'Teammitglieder';
$lang->project->doc               = 'Dok';
$lang->project->doclib            = 'Dok Bibliothek';
$lang->project->manageProducts    = 'Verküpfe ' . $lang->productCommon;
$lang->project->linkStory         = 'Link Stories';
$lang->project->linkStoryByPlan   = 'Verküpfe Story aus Plan';
$lang->project->linkPlan          = 'Verküpfe Plan';
$lang->project->unlinkStoryTasks  = 'Verknüpfung aufheben';
$lang->project->linkedProducts    = 'Verküpfte Produkte';
$lang->project->unlinkedProducts  = 'Produkt verknüpfung aufheben';
$lang->project->view              = "Übersicht";
$lang->project->startAction       = "Start {$lang->projectCommon}";
$lang->project->activateAction    = "Activate {$lang->projectCommon}";
$lang->project->delayAction       = "Delay {$lang->projectCommon}";
$lang->project->suspendAction     = "Suspend {$lang->projectCommon}";
$lang->project->closeAction       = "Close {$lang->projectCommon}";
$lang->project->testtaskAction    = "{$lang->projectCommon} Request";
$lang->project->teamAction        = "{$lang->projectCommon} Members";
$lang->project->kanbanAction      = "{$lang->projectCommon} Kanban";
$lang->project->printKanbanAction = "Print Kanban";
$lang->project->treeAction        = "{$lang->projectCommon} Tree View";
$lang->project->exportAction      = "Export {$lang->projectCommon}";
$lang->project->computeBurnAction = "Compute Burn";
$lang->project->create            = "Erstelle Projekt";
$lang->project->copy              = "Kopiere {$lang->projectCommon}";
$lang->project->delete            = "Lösche";
$lang->project->browse            = "Durchsuchen";
$lang->project->edit              = "Bearbeiten";
$lang->project->batchEdit         = "Mehere bearbeiten";
$lang->project->manageMembers     = 'Teams verwalten';
$lang->project->unlinkMember      = 'Mitgliefer entfernen';
$lang->project->unlinkStory       = 'Story entfernen';
$lang->project->unlinkStoryAB     = 'Unlink';
$lang->project->batchUnlinkStory  = 'Mehere Storys entfernen';
$lang->project->importTask        = 'Importiere Aufgaben';
$lang->project->importPlanStories = 'Verknüpfe Story aus Plan';
$lang->project->importBug         = 'Importiere Bugs';
$lang->project->updateOrder       = 'Sortierung';
$lang->project->tree              = 'Baum';
$lang->project->treeTask          = 'Aufgabe anzeigen';
$lang->project->treeStory         = 'Story anzeigen';
$lang->project->treeOnlyTask      = 'Show Task Only';
$lang->project->treeOnlyStory     = 'Show Story Only';
$lang->project->storyKanban       = 'Story Kanban';
$lang->project->storySort         = 'Story sortieren';
$lang->project->importPlanStory   = '' . $lang->projectCommon . ' wurde erstellt!\nMöchten Sie Storys aus dem Plan importieren?';
$lang->project->iteration         = 'Iteration';
$lang->project->iterationInfo     = '%s Iterationen';
$lang->project->viewAll           = 'Alle anzeigen';

/* 分组浏览。*/
$lang->project->allTasks     = 'Alle';
$lang->project->assignedToMe = 'Meine';
$lang->project->myInvolved   = 'Beteiligt';

$lang->project->statusSelects['']             = 'Mehr';
$lang->project->statusSelects['wait']         = 'Wartend';
$lang->project->statusSelects['doing']        = 'In Arbeit';
$lang->project->statusSelects['undone']       = 'Undone';
$lang->project->statusSelects['finishedbyme'] = 'Von mir abgeschlossen';
$lang->project->statusSelects['done']         = 'Erledigt';
$lang->project->statusSelects['closed']       = 'Geschlossen';
$lang->project->statusSelects['cancel']       = 'Abgebrochen';

$lang->project->groups['']           = 'Gruppen';
$lang->project->groups['story']      = 'Nach Story';
$lang->project->groups['status']     = 'Nach Status';
$lang->project->groups['pri']        = 'Nach Priorität';
$lang->project->groups['assignedTo'] = 'Nach Zuweisung an';
$lang->project->groups['finishedBy'] = 'Nach abgeschlossen von';
$lang->project->groups['closedBy']   = 'Nach geschlossen von';
$lang->project->groups['type']       = 'Nach Typ';

$lang->project->groupFilter['story']['all']         = $lang->project->all;
$lang->project->groupFilter['story']['linked']      = 'Aufgaben verknüpft mit Story';
$lang->project->groupFilter['pri']['all']           = $lang->project->all;
$lang->project->groupFilter['pri']['noset']         = 'Not gesetzt';
$lang->project->groupFilter['assignedTo']['undone'] = 'Unabgeschlossen';
$lang->project->groupFilter['assignedTo']['all']    = $lang->project->all;

$lang->project->byQuery = 'Suche';

/* 查询条件列表。*/
$lang->project->allProject      = "Alle {$lang->projectCommon}";
$lang->project->aboveAllProduct = "Alle oberen {$lang->productCommon}";
$lang->project->aboveAllProject = "Alle oberen {$lang->projectCommon}";

/* 页面提示。*/
$lang->project->linkStoryByPlanTips = "This action will link all stories in this plan to the {$lang->projectCommon}.";
$lang->project->selectProject       = "Auswahl {$lang->projectCommon}";
$lang->project->beginAndEnd         = 'Dauer';
$lang->project->begin               = 'Start';
$lang->project->end                 = 'Ende';
$lang->project->lblStats            = 'Mannstunden Summe(h) : ';
$lang->project->stats               = '<strong>%s</strong> Verfügbar, <strong>%s</strong> geplant, <strong>%s</strong> genutzt, <strong>%s</strong> Rest.';
$lang->project->taskSummary         = "Aufgaben auf dieser Seite: <strong>%s</strong> Total, <strong>%s</strong> Wartend, <strong>%s</strong> In Arbeit;  &nbsp;&nbsp;&nbsp;  Stunden : <strong>%s</strong> geplant., <strong>%s</strong> genutzt, <strong>%s</strong> Rest.";
$lang->project->pageSummary         = "Aufgaben auf dieser Seite:  <strong>%total%</strong>, <strong>%wait%</strong> Wartend, <strong>%doing%</strong> In Arbeit;    Stunden: <strong>%estimate%</strong>  geplant, <strong>%consumed%</strong> genutzt, <strong>%left%</strong> Rest.";
$lang->project->checkedSummary      = " <strong>%total%</strong> Geprüft, <strong>%wait%</strong> Wartend, <strong>%doing%</strong> In Arbeit;    Stunden: <strong>%estimate%</strong>  geplant, <strong>%consumed%</strong> genutzt, <strong>%left%</strong> Rest.";
$lang->project->memberHoursAB       = "%s hat <strong>%s</strong> Stunden";
$lang->project->memberHours         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">%s Arbeitsstunden</div><div class="segment-value">%s</div></div></div></div>';
$lang->project->countSummary        = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Aufgaben</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">In Arbeit</div><div class="segment-value"><span class="label label-dot label-primary"></span> %s</div></div><div class="segment"><div class="segment-title">Wait</div><div class="segment-value"><span class="label label-dot label-primary muted"></span> %s</div></div></div></div>';
$lang->project->timeSummary         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Geplant</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">Genutzt</div><div class="segment-value text-red">%s</div></div><div class="segment"><div class="segment-title">Rest</div><div class="segment-value">%s</div></div></div></div>';
$lang->project->groupSummaryAB      = "<div>Aufgaben <strong>%s</strong></div><div><span class='text-muted'>Wartend</span> %s &nbsp; <span class='text-muted'>In Arbeit</span> %s</div><div>Geplant <strong>%s</strong></div><div><span class='text-muted'>Genutzt</span> %s &nbsp; <span class='text-muted'>Rest</span> %s</div>";
$lang->project->wbs                 = "Aufgaben aufteilen";
$lang->project->batchWBS            = "Mehrere aufteilen";
$lang->project->howToUpdateBurn     = "<a href='http://api.zentao.net/goto.php?item=burndown&lang=zh-cn' target='_blank' title='Wie wird der Burndown Chart aktualisiert?' class='btn btn-link'>Hilfe <i class='icon icon-help'></i></a>";
$lang->project->whyNoStories        = "Keine Story kann verknüpft werden. Bitte prüfen Sie ob ein Story mit {$lang->projectCommon} verknüpft ist {$lang->productCommon} und stellen Sie sicher das diese geprüft ist.";
$lang->project->productStories      = "{$lang->projectCommon} verknüpfte Story ist ein Subset von {$lang->productCommon}, welche nur nach überprüfung verknüpft werden kann. Bitte <a href='%s'> Story verknüpfen</a>。";
$lang->project->haveDraft           = "There are %s draft stories can't be linked.";
$lang->project->doneProjects        = 'Erledigt';
$lang->project->selectDept          = 'Abteilung wählen';
$lang->project->selectDeptTitle     = 'Abteilung wählen';
$lang->project->copyTeam            = 'Team kopieren';
$lang->project->copyFromTeam        = "Kopieren von {$lang->projectCommon} Team: <strong>%s</strong>";
$lang->project->noMatched           = "$lang->projectCommon mit '%s' konnte nicht gefunden werden.";
$lang->project->copyTitle           = "Wählen Sie ein {$lang->projectCommon} zum Kopieren.";
$lang->project->copyTeamTitle       = "Wählen Sie ein {$lang->projectCommon} Team zum Kopieren.";
$lang->project->copyNoProject       = "{$lang->projectCommon} kann nicht kopiert werden.";
$lang->project->copyFromProject     = "Kopie von {$lang->projectCommon} <strong>%s</strong>";
$lang->project->cancelCopy          = 'Kopieren abbrechen';
$lang->project->byPeriod            = 'Nach Zeit';
$lang->project->byUser              = 'Nach Benutzer';
$lang->project->noProject           = 'Keine Projekte. ';
$lang->project->noMembers           = 'Keine Mitglieder. ';

/* 交互提示。*/
$lang->project->confirmDelete         = "Möchten Sie {$lang->projectCommon}[%s] löschen?";
$lang->project->confirmUnlinkMember   = "Möchten Sie den Benutzer vom {$lang->projectCommon} entfernen?";
$lang->project->confirmUnlinkStory    = "Möchten Sie die Story vom {$lang->projectCommon} entfernen?";
$lang->project->errorNoLinkedProducts = "Kein verknüpftes {$lang->productCommon} in {$lang->projectCommon} gefunden. Sie werden auf die {$lang->productCommon} Seite geleitet.";
$lang->project->errorSameProducts     = "{$lang->projectCommon} Kann nicht mit mehreren identischen {$lang->productCommon} verknüpft werden";
$lang->project->accessDenied          = "Zugriff zu {$lang->projectCommon} verweigert!";
$lang->project->tips                  = 'Hinweis';
$lang->project->afterInfo             = "{$lang->projectCommon} wurde erstellt. Als nächstes können Sie ";
$lang->project->setTeam               = 'Team setzen';
$lang->project->linkStory             = 'Storys verküpfen';
$lang->project->createTask            = 'Aufgaben erstellen';
$lang->project->goback                = "Zurückkehren";
$lang->project->noweekend             = 'Ohne Wochenende';
$lang->project->withweekend           = 'Mit Wochenende';
$lang->project->interval              = 'Intervale ';
$lang->project->fixFirstWithLeft      = 'Modify the left';

$lang->project->action = new stdclass();
$lang->project->action->opened  = '$date, created by <strong>$actor</strong> . $extra' . "\n";
$lang->project->action->managed = '$date, managed by <strong>$actor</strong> . $extra' . "\n";
$lang->project->action->edited  = '$date, edited by <strong>$actor</strong> . $extra' . "\n";
$lang->project->action->extra   = "The linked {$lang->productCommon}s are %s.";

/* 统计。*/
$lang->project->charts = new stdclass();
$lang->project->charts->burn = new stdclass();
$lang->project->charts->burn->graph = new stdclass();
$lang->project->charts->burn->graph->caption      = "Burndown";
$lang->project->charts->burn->graph->xAxisName    = "Datum";
$lang->project->charts->burn->graph->yAxisName    = "Stunde";
$lang->project->charts->burn->graph->baseFontSize = 12;
$lang->project->charts->burn->graph->formatNumber = 0;
$lang->project->charts->burn->graph->animation    = 0;
$lang->project->charts->burn->graph->rotateNames  = 1;
$lang->project->charts->burn->graph->showValues   = 0;
$lang->project->charts->burn->graph->reference    = 'Referenz';
$lang->project->charts->burn->graph->actuality    = 'Aktualität';

$lang->project->placeholder = new stdclass();
$lang->project->placeholder->code      = 'Abkurzung des Projektnamens';
$lang->project->placeholder->totalLeft = 'Schätzungen zu Beginn des Projekts.';

$lang->project->selectGroup = new stdclass();
$lang->project->selectGroup->done = '(Erledigt)';

$lang->project->orderList['order_asc']  = "Aufsteigend";
$lang->project->orderList['order_desc'] = "Absteigend";
$lang->project->orderList['pri_asc']    = "Priorität Auf.";
$lang->project->orderList['pri_desc']   = "Priorität Ab.";
$lang->project->orderList['stage_asc']  = "Phase Auf.";
$lang->project->orderList['stage_desc'] = "Phase Ab.";

$lang->project->kanban        = "Kanban";
$lang->project->kanbanSetting = "Kanban Einstellung";
$lang->project->resetKanban   = "Einstellungen zurücksetzen";
$lang->project->printKanban   = "Kanban drucken";
$lang->project->bugList       = "Bugs";

$lang->project->kanbanHideCols   = 'Geschlossene und abgebrochene Spalten in Kanban verstecken';
$lang->project->kanbanShowOption = 'Aufklappen';
$lang->project->kanbanColsColor  = 'Spaltenfarben';

$lang->kanbanSetting = new stdclass();
$lang->kanbanSetting->noticeReset     = 'Möchten Sie die Einstellungen des Kanbans zurücksetzen?';
$lang->kanbanSetting->optionList['0'] = 'Verstecken';
$lang->kanbanSetting->optionList['1'] = 'Anzeigen';

$lang->printKanban = new stdclass();
$lang->printKanban->common  = 'Kanban drucken';
$lang->printKanban->content = 'Inhanlt';
$lang->printKanban->print   = 'Drucken';

$lang->printKanban->taskStatus = 'Status';

$lang->printKanban->typeList['all']       = 'Alle';
$lang->printKanban->typeList['increment'] = 'Erhöhen';

$lang->project->featureBar['task']['all']          = $lang->project->allTasks;
$lang->project->featureBar['task']['unclosed']     = $lang->project->unclosed;
$lang->project->featureBar['task']['assignedtome'] = $lang->project->assignedToMe;
$lang->project->featureBar['task']['myinvolved']   = $lang->project->myInvolved;
$lang->project->featureBar['task']['delayed']      = 'Verspätet';
$lang->project->featureBar['task']['needconfirm']  = 'Story geändert';
$lang->project->featureBar['task']['status']       = $lang->project->statusSelects[''];

$lang->project->featureBar['all']['all']       = $lang->project->all;
$lang->project->featureBar['all']['undone']    = $lang->project->undone;
$lang->project->featureBar['all']['wait']      = $lang->project->statusList['wait'];
$lang->project->featureBar['all']['doing']     = $lang->project->statusList['doing'];
$lang->project->featureBar['all']['suspended'] = $lang->project->statusList['suspended'];
$lang->project->featureBar['all']['closed']    = $lang->project->statusList['closed'];

$lang->project->treeLevel = array();
$lang->project->treeLevel['all']   = 'Alle aufklappen';
$lang->project->treeLevel['root']  = 'Alle zuklappen';
$lang->project->treeLevel['task']  = 'Aufgabe anzeigen';
$lang->project->treeLevel['story'] = 'Story anzeigen';

global $config;
if($config->global->flow == 'onlyTask')
{
    unset($lang->project->groups['story']);
    unset($lang->project->featureBar['task']['needconfirm']);
}
