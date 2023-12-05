<?php
/**
 * The bug module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: en.php 4536 2013-03-02 13:39:37Z wwccss $
 * @link        http://www.zentao.net
 */
/* Fieldlist. */
$lang->bug->common           = 'Bug';
$lang->bug->id               = 'ID';
$lang->bug->product          = $lang->productCommon;
$lang->bug->branch           = 'Branch/Platform';
$lang->bug->module           = 'Modul';
$lang->bug->project          = $lang->projectCommon;
$lang->bug->execution        = $lang->execution->common;
$lang->bug->kanban           = 'Kanban';
$lang->bug->storyVersion     = 'Story Version';
$lang->bug->color            = 'Farbe des Titels';
$lang->bug->title            = 'Titel';
$lang->bug->severity         = 'Schwere(S)';
$lang->bug->pri              = 'Priorität(P)';
$lang->bug->type             = 'Typ';
$lang->bug->os               = 'OS';
$lang->bug->browser          = 'Browser';
$lang->bug->hardware         = 'Hardware';
$lang->bug->result           = 'Result';
$lang->bug->repo             = 'Repo';
$lang->bug->mr               = 'Merge Request';
$lang->bug->entry            = 'Code Path';
$lang->bug->lines            = 'Lines';
$lang->bug->v1               = 'Version A';
$lang->bug->v2               = 'Version B';
$lang->bug->issueKey         = 'Sonarqube Issue Key';
$lang->bug->repoType         = 'Repo Type';
$lang->bug->steps            = 'Reproduktionsschritte';
$lang->bug->status           = 'Status';
$lang->bug->subStatus        = 'Sub Status';
$lang->bug->activatedCount   = 'Aktiv';
$lang->bug->activatedDate    = 'Aktiv seit';
$lang->bug->confirmed        = 'Bestätigt';
$lang->bug->toTask           = 'In Aufgabe konvertieren';
$lang->bug->toStory          = 'In Story konvertieren';
$lang->bug->feedbackBy       = 'Discovered by';
$lang->bug->notifyEmail      = 'Discoverer Email';
$lang->bug->mailto           = 'Mail an';
$lang->bug->openedBy         = 'Ersteller';
$lang->bug->openedDate       = 'Erstellt am';
$lang->bug->openedBuild      = 'Offenes Build';
$lang->bug->assignedTo       = 'An';
$lang->bug->assignedToMe     = 'AssignToMe';
$lang->bug->assignedDate     = 'Zugewisen am';
$lang->bug->resolvedBy       = 'Gelöst';
$lang->bug->resolution       = 'Lösung';
$lang->bug->resolvedBuild    = 'Lösungs Build';
$lang->bug->resolvedDate     = 'Gelöst am';
$lang->bug->deadline         = 'Fällig am';
$lang->bug->plan             = 'Plan';
$lang->bug->closedBy         = 'Geschlossen von';
$lang->bug->closedDate       = 'Geschlossen am';
$lang->bug->duplicateBug     = 'Dublette';
$lang->bug->lastEditedBy     = 'Geändert von';
$lang->bug->caseVersion      = 'Fall Version';
$lang->bug->testtask         = 'Request';
$lang->bug->files            = 'Datei';
$lang->bug->keywords         = 'Tags';
$lang->bug->lastEditedDate   = 'Bearbeitet am';
$lang->bug->fromCase         = 'Von Fall';
$lang->bug->toCase           = 'Zu Fall';
$lang->bug->colorTag         = 'Farb Tag';
$lang->bug->fixedRate        = 'Fixed Rate';
$lang->bug->noticefeedbackBy = 'NoticeFeedbackBy';
$lang->bug->selectProjects   = "Select {$lang->projectCommon}s";
$lang->bug->nextStep         = 'Next Step';
$lang->bug->noProject        = "Haven’t chosen a {$lang->projectCommon} yet.";
$lang->bug->noExecution      = 'Haven’t chosen a ' . strtolower($lang->execution->common) . ' yet.';
$lang->bug->story            = 'Story';
$lang->bug->task             = 'Aufgabe';
$lang->bug->relatedBug       = 'Verknüpfter Bug';
$lang->bug->case             = 'Fall';
$lang->bug->linkMR           = 'Related MRs';
$lang->bug->linkCommit       = 'Related Commits';
$lang->bug->productplan      = $lang->bug->plan;

$lang->bug->abbr = new stdclass();
$lang->bug->abbr->module         = 'Modul';
$lang->bug->abbr->severity       = 'S';
$lang->bug->abbr->status         = 'Status';
$lang->bug->abbr->activatedCount = 'Aktiv';
$lang->bug->abbr->confirmed      = 'C';
$lang->bug->abbr->openedBy       = 'Reporter';
$lang->bug->abbr->openedDate     = 'Erstellt';
$lang->bug->abbr->assignedTo     = 'An';
$lang->bug->abbr->resolvedBy     = 'Gelöst';
$lang->bug->abbr->resolution     = 'Lösung';
$lang->bug->abbr->resolvedDate   = 'Gelöst';
$lang->bug->abbr->deadline       = 'Fällig';
$lang->bug->abbr->lastEditedBy   = 'Bearbeitet von';
$lang->bug->abbr->lastEditedDate = 'Bearbeitet am';
$lang->bug->abbr->assignToMe     = 'Mir zugeordnet';
$lang->bug->abbr->openedByMe     = 'Von mir erstellt';
$lang->bug->abbr->resolvedByMe   = 'Von mir gelöst';

/* Method list. */
$lang->bug->index              = 'Home';
$lang->bug->browse             = 'Bugs';
$lang->bug->create             = 'Bug erstellen';
$lang->bug->batchCreate        = 'Batch Report Bug';
$lang->bug->createCase         = 'Create Case';
$lang->bug->copy               = 'Kopieren';
$lang->bug->edit               = 'Bearbeiten';
$lang->bug->batchEdit          = 'Batch Edit';
$lang->bug->view               = 'Bug Details';
$lang->bug->delete             = 'Löschen';
$lang->bug->deleteAction       = 'Delete Bug';
$lang->bug->confirm            = 'Bestätigen';
$lang->bug->confirmAction      = 'Confirm Bug';
$lang->bug->batchConfirm       = 'Batch Confirm';
$lang->bug->assignTo           = 'Zuweisen';
$lang->bug->assignAction       = 'Assign Bug';
$lang->bug->batchAssignTo      = 'Batch Assign';
$lang->bug->resolve            = 'Lösen';
$lang->bug->resolveAction      = 'Resolve Bug';
$lang->bug->batchResolve       = 'Batch Resolve';
$lang->bug->close              = 'Schließen';
$lang->bug->closeAction        = 'Close Bug';
$lang->bug->batchClose         = 'Batch Close';
$lang->bug->activate           = 'Aktivieren';
$lang->bug->activateAction     = 'Activate Bug';
$lang->bug->batchActivate      = 'Batch Activate';
$lang->bug->reportChart        = 'Bericht';
$lang->bug->reportAction       = 'Bug Report';
$lang->bug->export             = 'Export';
$lang->bug->exportAction       = 'Export Bug';
$lang->bug->confirmStoryChange = 'Confirm Story Change';
$lang->bug->search             = 'Suche';
$lang->bug->batchChangeModule  = 'Batch Modify Module';
$lang->bug->batchChangeBranch  = 'Batch Modify Branch';
$lang->bug->batchChangePlan    = 'Batch Edit Plans';
$lang->bug->linkBugs         = 'Bug verknupfen';
$lang->bug->unlinkBug        = 'Verknüpfung aufheben';

/* Query condition list. */
$lang->bug->assignToMe         = 'Mir zugeordnet';
$lang->bug->openedByMe         = 'Von mir erstellt';
$lang->bug->resolvedByMe       = 'Von mir gelöst';
$lang->bug->closedByMe         = 'Von mir geschlossen';
$lang->bug->assignedByMe       = 'AssignedByMe';
$lang->bug->assignToNull       = 'Nicht zugeordnet';
$lang->bug->unResolved         = 'Ungelöst';
$lang->bug->toClosed           = 'Nicht geschlossen';
$lang->bug->unclosed           = 'Aktiv';
$lang->bug->unconfirmed        = 'Unbestätigt';
$lang->bug->longLifeBugs       = 'Wartend';
$lang->bug->postponedBugs      = 'Verschoben';
$lang->bug->overdueBugs        = 'Überfällig';
$lang->bug->allBugs            = 'Alle';
$lang->bug->byQuery            = 'Suche';
$lang->bug->needConfirm        = 'Story geändert';
$lang->bug->allProject         = "All {$lang->projectCommon}s";
$lang->bug->allProduct         = 'Alle' . $lang->productCommon;
$lang->bug->my                 = 'Meine';
$lang->bug->yesterdayResolved  = 'Gestern gelöst ';
$lang->bug->yesterdayConfirmed = 'Gestern bestätigt ';
$lang->bug->yesterdayClosed    = 'Gestern geschlossen ';

$lang->bug->deleted        = 'Gelöscht';
$lang->bug->labelConfirmed = 'Confirmed';
$lang->bug->labelPostponed = 'Postponed';
$lang->bug->changed        = 'Changed';
$lang->bug->storyChanged   = 'Story Changed';
$lang->bug->ditto          = 'Dito';

/* Page tags. */
$lang->bug->lblAssignedTo = 'Bearbeiter';
$lang->bug->lblMailto     = 'Mail an';
$lang->bug->lblLastEdited = 'Letzte Bearbeitung';
$lang->bug->lblResolved   = 'Gelöst von';
$lang->bug->loadAll       = 'Load All';
$lang->bug->createBuild   = 'Neu';

global $config;
/* Legend list. */
$lang->bug->legendBasicInfo             = 'Basis Info';
$lang->bug->legendAttach                = 'Anlagen';
$lang->bug->legendPRJExecStoryTask      = "{$lang->SRCommon}/{$lang->executionCommon}/Story/Task";
$lang->bug->legendExecStoryTask         = "{$lang->SRCommon}/Story/Task";
$lang->bug->lblTypeAndSeverity          = 'Typ/Schwere';
$lang->bug->lblSystemBrowserAndHardware = 'System/Browser';
$lang->bug->legendSteps                 = 'Reproduktionsschritte';
$lang->bug->legendComment               = 'Kommentar';
$lang->bug->legendLife                  = 'Über diesen Bug';
$lang->bug->legendMisc                  = 'Sonstiges';
$lang->bug->legendRelated               = 'Sonstige Infos';
$lang->bug->legendThisWeekCreated       = 'This Week Created';

/* Template. */
$lang->bug->tplStep   = "<p>[Steps]</p><p></p>";
$lang->bug->tplResult = "<p>[Results]</p><p></p>";
$lang->bug->tplExpect = "<p>[Expectations]</p><p></p>";

/* Value list for each field. */
$lang->bug->severityList[0] = '';
$lang->bug->severityList[1] = '1';
$lang->bug->severityList[2] = '2';
$lang->bug->severityList[3] = '3';
$lang->bug->severityList[4] = '4';

$lang->bug->priList[0] = '';
$lang->bug->priList[1] = '1';
$lang->bug->priList[2] = '2';
$lang->bug->priList[3] = '3';
$lang->bug->priList[4] = '4';

$lang->bug->osList['']         = '';
$lang->bug->osList['all']      = 'Alle';
$lang->bug->osList['windows']  = 'Windows';
$lang->bug->osList['win11']    = 'Windows 11';
$lang->bug->osList['win10']    = 'Windows 10';
$lang->bug->osList['win8']     = 'Windows 8';
$lang->bug->osList['win7']     = 'Windows 7';
$lang->bug->osList['winxp']    = 'Windows XP';
$lang->bug->osList['osx']      = 'Mac OS';
$lang->bug->osList['android']  = 'Android';
$lang->bug->osList['ios']      = 'IOS';
$lang->bug->osList['linux']    = 'Linux';
$lang->bug->osList['ubuntu']   = 'Ubuntu';
$lang->bug->osList['chromeos'] = 'Chrome OS';
$lang->bug->osList['fedora']   = 'Fedora';
$lang->bug->osList['unix']     = 'Unix';
$lang->bug->osList['others']   = 'Andere';

$lang->bug->browserList['']        = '';
$lang->bug->browserList['all']     = 'Alle';
$lang->bug->browserList['chrome']  = 'Chrome';
$lang->bug->browserList['edge']    = 'Edge';
$lang->bug->browserList['ie']      = 'IE series';
$lang->bug->browserList['ie11']    = 'IE11';
$lang->bug->browserList['ie10']    = 'IE10';
$lang->bug->browserList['ie9']     = 'IE9';
$lang->bug->browserList['ie8']     = 'IE8';
$lang->bug->browserList['firefox'] = 'Firefox series';
$lang->bug->browserList['opera']   = 'Opera series';
$lang->bug->browserList['safari']  = 'Safari';
$lang->bug->browserList['360']     = '360 series';
$lang->bug->browserList['qq']      = 'QQ series';
$lang->bug->browserList['other']   = 'Andere';

$lang->bug->typeList['']             = '';
$lang->bug->typeList['codeerror']    = 'Code Fehler';
$lang->bug->typeList['config']       = 'Konfiguration';
$lang->bug->typeList['install']      = 'Install/Ausrollen';
$lang->bug->typeList['security']     = 'Sicherheit';
$lang->bug->typeList['performance']  = 'Performance';
$lang->bug->typeList['standard']     = 'Standard';
$lang->bug->typeList['automation']   = 'Script-Test';
$lang->bug->typeList['designdefect'] = 'Design Fehler';
$lang->bug->typeList['others']       = 'Andere';

$lang->bug->statusList['']         = '';
$lang->bug->statusList['active']   = 'Aktiv';
$lang->bug->statusList['resolved'] = 'Gelöst';
$lang->bug->statusList['closed']   = 'Geschlossen';

$lang->bug->confirmedList[''] = '';
$lang->bug->confirmedList[1] = 'Bestätigt';
$lang->bug->confirmedList[0] = 'Unbestätigt';

$lang->bug->resolutionList['']           = '';
$lang->bug->resolutionList['bydesign']   = 'Kein Fehler';
$lang->bug->resolutionList['duplicate']  = 'Dublette';
$lang->bug->resolutionList['external']   = 'Externe Ursache';
$lang->bug->resolutionList['fixed']      = 'Gelöst';
$lang->bug->resolutionList['notrepro']   = 'Nicht nachvollziehbar';
$lang->bug->resolutionList['postponed']  = 'Verschoben';
$lang->bug->resolutionList['willnotfix'] = "Ignorieren";
$lang->bug->resolutionList['tostory']    = 'In Story konvertieren';

/* Statistical statement. */
$lang->bug->report = new stdclass();
$lang->bug->report->common = 'Berichte';
$lang->bug->report->select = 'Typ selektieren ';
$lang->bug->report->create = 'Bericht erstellen';

$lang->bug->report->charts['bugsPerExecution']      = $lang->executionCommon . ' Bugs';
$lang->bug->report->charts['bugsPerBuild']          = 'Bugs pro Build';
$lang->bug->report->charts['bugsPerModule']         = 'Bugs pro Modul';
$lang->bug->report->charts['openedBugsPerDay']      = 'Erstellte Bugs pro Tag';
$lang->bug->report->charts['resolvedBugsPerDay']    = 'Gelöste Bugs pro Tag';
$lang->bug->report->charts['closedBugsPerDay']      = 'Geschlossene Bugs pro Tag';
$lang->bug->report->charts['openedBugsPerUser']     = 'Erstellte Bugs je Benutzer';
$lang->bug->report->charts['resolvedBugsPerUser']   = 'Gelöste Bugs je Benutzer';
$lang->bug->report->charts['closedBugsPerUser']     = 'Geschlossene Bugs je Benutzer';
$lang->bug->report->charts['bugsPerSeverity']       = 'Bug Schwerebericht';
$lang->bug->report->charts['bugsPerResolution']     = 'Bug Lösungsbericht';
$lang->bug->report->charts['bugsPerStatus']         = 'Bug Statusbericht';
$lang->bug->report->charts['bugsPerActivatedCount'] = 'Bug Aktivierungsbericht';
$lang->bug->report->charts['bugsPerPri']            = 'Bug Prioritätsbericht';
$lang->bug->report->charts['bugsPerType']           = 'Bug Typenbericht';
$lang->bug->report->charts['bugsPerAssignedTo']     = 'Bug Zuweisungs-Bericht';
//$lang->bug->report->charts['bugLiveDays']        = 'Bug Handling Time Report';
//$lang->bug->report->charts['bugHistories']       = 'Bug Handling Steps Report';

$lang->bug->report->options = new stdclass();
$lang->bug->report->options->graph  = new stdclass();
$lang->bug->report->options->type   = 'pie';
$lang->bug->report->options->width  = 500;
$lang->bug->report->options->height = 140;

$lang->bug->report->bugsPerExecution      = new stdclass();
$lang->bug->report->bugsPerBuild          = new stdclass();
$lang->bug->report->bugsPerModule         = new stdclass();
$lang->bug->report->openedBugsPerDay      = new stdclass();
$lang->bug->report->resolvedBugsPerDay    = new stdclass();
$lang->bug->report->closedBugsPerDay      = new stdclass();
$lang->bug->report->openedBugsPerUser     = new stdclass();
$lang->bug->report->resolvedBugsPerUser   = new stdclass();
$lang->bug->report->closedBugsPerUser     = new stdclass();
$lang->bug->report->bugsPerSeverity       = new stdclass();
$lang->bug->report->bugsPerResolution     = new stdclass();
$lang->bug->report->bugsPerStatus         = new stdclass();
$lang->bug->report->bugsPerActivatedCount = new stdclass();
$lang->bug->report->bugsPerType           = new stdclass();
$lang->bug->report->bugsPerPri            = new stdclass();
$lang->bug->report->bugsPerAssignedTo     = new stdclass();
$lang->bug->report->bugLiveDays           = new stdclass();
$lang->bug->report->bugHistories          = new stdclass();

$lang->bug->report->bugsPerExecution->graph      = new stdclass();
$lang->bug->report->bugsPerBuild->graph          = new stdclass();
$lang->bug->report->bugsPerModule->graph         = new stdclass();
$lang->bug->report->openedBugsPerDay->graph      = new stdclass();
$lang->bug->report->resolvedBugsPerDay->graph    = new stdclass();
$lang->bug->report->closedBugsPerDay->graph      = new stdclass();
$lang->bug->report->openedBugsPerUser->graph     = new stdclass();
$lang->bug->report->resolvedBugsPerUser->graph   = new stdclass();
$lang->bug->report->closedBugsPerUser->graph     = new stdclass();
$lang->bug->report->bugsPerSeverity->graph       = new stdclass();
$lang->bug->report->bugsPerResolution->graph     = new stdclass();
$lang->bug->report->bugsPerStatus->graph         = new stdclass();
$lang->bug->report->bugsPerActivatedCount->graph = new stdclass();
$lang->bug->report->bugsPerType->graph           = new stdclass();
$lang->bug->report->bugsPerPri->graph            = new stdclass();
$lang->bug->report->bugsPerAssignedTo->graph     = new stdclass();
$lang->bug->report->bugLiveDays->graph           = new stdclass();
$lang->bug->report->bugHistories->graph          = new stdclass();

$lang->bug->report->bugsPerExecution->graph->xAxisName = $lang->executionCommon;
$lang->bug->report->bugsPerBuild->graph->xAxisName     = 'Build';
$lang->bug->report->bugsPerModule->graph->xAxisName    = 'Modul';

$lang->bug->report->openedBugsPerDay->type             = 'bar';
$lang->bug->report->openedBugsPerDay->graph->xAxisName = 'Datum';

$lang->bug->report->resolvedBugsPerDay->type             = 'bar';
$lang->bug->report->resolvedBugsPerDay->graph->xAxisName = 'Datum';

$lang->bug->report->closedBugsPerDay->type             = 'bar';
$lang->bug->report->closedBugsPerDay->graph->xAxisName = 'Datum';

$lang->bug->report->openedBugsPerUser->graph->xAxisName   = 'Benutzer';
$lang->bug->report->resolvedBugsPerUser->graph->xAxisName = 'Benutzer';
$lang->bug->report->closedBugsPerUser->graph->xAxisName   = 'Benutzer';

$lang->bug->report->bugsPerSeverity->graph->xAxisName       = 'Prorität';
$lang->bug->report->bugsPerResolution->graph->xAxisName     = 'Lösung';
$lang->bug->report->bugsPerStatus->graph->xAxisName         = 'Status';
$lang->bug->report->bugsPerActivatedCount->graph->xAxisName = 'Aktivitätszähler';
$lang->bug->report->bugsPerPri->graph->xAxisName            = 'Prorität';
$lang->bug->report->bugsPerType->graph->xAxisName           = 'Typ';
$lang->bug->report->bugsPerAssignedTo->graph->xAxisName     = 'Zugewiesen an';
$lang->bug->report->bugLiveDays->graph->xAxisName           = 'Bearbeitungszeit';
$lang->bug->report->bugHistories->graph->xAxisName          = 'Bearbeitungsschritte';

/* Operating record. */
$lang->bug->action = new stdclass();
$lang->bug->action->resolved             = array('main' => '$date, gelöst von <strong>$actor</strong> und die Lösung ist <strong>$extra</strong> $appendLink.', 'extra' => 'resolutionList');
$lang->bug->action->tostory              = array('main' => '$date, konvertiert von <strong>$actor</strong> zu <strong>Story</strong> mit ID <strong>$extra</strong>.');
$lang->bug->action->totask               = array('main' => '$date, importiert von <strong>$actor</strong> als <strong>Aufgabe</strong> mit ID <strong>$extra</strong>.');
$lang->bug->action->converttotask        = array('main' => '$date, imported by <strong>$actor</strong> as <strong>Task</strong>，with ID <strong>$extra</strong>。');
$lang->bug->action->linked2plan          = array('main' => '$date, verknüpft von <strong>$actor</strong> mit Plan <strong>$extra</strong>.');
$lang->bug->action->unlinkedfromplan     = array('main' => '$date, verknüpfung aufgehoben von <strong>$actor</strong> von Plan <strong>$extra</strong>.');
$lang->bug->action->linked2build         = array('main' => '$date, verknüpft von <strong>$actor</strong> zum Build <strong>$extra</strong>.');
$lang->bug->action->unlinkedfrombuild    = array('main' => '$date, verknüpfung aufgehoben von <strong>$actor</strong> von Build <strong>$extra</strong>.');
$lang->bug->action->unlinkedfromrelease  = array('main' => '$date, verknüpfung aufgehoben von <strong>$actor</strong> vom Release <strong>$extra</strong>.');
$lang->bug->action->linked2release       = array('main' => '$date, verknüpft von <strong>$actor</strong> zu Release <strong>$extra</strong>.');
$lang->bug->action->linked2revision      = array('main' => '$date, linked by <strong>$actor</strong> to Revision <strong>$extra</strong>.');
$lang->bug->action->unlinkedfromrevision = array('main' => '$date, unlinked by <strong>$actor</strong> to Revision <strong>$extra</strong>.');
$lang->bug->action->linkrelatedbug       = array('main' => '$date, verknüpft von <strong>$actor</strong> mit Bug <strong>$extra</strong>.');
$lang->bug->action->unlinkrelatedbug     = array('main' => '$date, verknüpfung aufgehoben von <strong>$actor</strong> zum Bug <strong>$extra</strong>.');

$lang->bug->featureBar['browse']['all']          = $lang->bug->allBugs;
$lang->bug->featureBar['browse']['unclosed']     = $lang->bug->unclosed;
$lang->bug->featureBar['browse']['openedbyme']   = $lang->bug->openedByMe;
$lang->bug->featureBar['browse']['assigntome']   = $lang->bug->assignToMe;
$lang->bug->featureBar['browse']['resolvedbyme'] = $lang->bug->resolvedByMe;
$lang->bug->featureBar['browse']['more']         = $lang->more;

$lang->bug->moreSelects['browse']['more']['unresolved']    = $lang->bug->unResolved;
$lang->bug->moreSelects['browse']['more']['assignedbyme']  = $lang->bug->assignedByMe;
$lang->bug->moreSelects['browse']['more']['unconfirmed']   = $lang->bug->unconfirmed;
$lang->bug->moreSelects['browse']['more']['assigntonull']  = $lang->bug->assignToNull;
$lang->bug->moreSelects['browse']['more']['longlifebugs']  = $lang->bug->longLifeBugs;
$lang->bug->moreSelects['browse']['more']['toclosed']      = $lang->bug->toClosed;
$lang->bug->moreSelects['browse']['more']['postponedbugs'] = $lang->bug->postponedBugs;
$lang->bug->moreSelects['browse']['more']['overduebugs']   = $lang->bug->overdueBugs;
$lang->bug->moreSelects['browse']['more']['needconfirm']   = $lang->bug->needConfirm;

$lang->bug->placeholder = new stdclass();
$lang->bug->placeholder->chooseBuilds = 'Build wählen...';
$lang->bug->placeholder->newBuildName = 'Der Name des neuen Builds';
$lang->bug->placeholder->duplicate    = 'Please enter keyword search';

/* Interactive prompt. */
$lang->bug->notice = new stdclass();
$lang->bug->notice->summary               = "Bugs on this page : <strong>%s</strong> Total, <strong>%s</strong> Unresolved.";
$lang->bug->notice->confirmChangeProduct  = "Change {$lang->productCommon} will cause linked {$lang->executionCommon},Story and Task change. Do you want to do this?";
$lang->bug->notice->confirmDelete         = 'Do you want to delete this bug?';
$lang->bug->notice->remindTask            = 'This Bug has been converted to Task. Do you want to update Status of Task(ID %s)?';
$lang->bug->notice->skipClose             = 'Bug %s is/are Not Resolved states and cannot be closed. They will be ignored automatically.';
$lang->bug->notice->executionAccessDenied = "You access to the {$lang->executionCommon} to which this bug belongs is denied!";
$lang->bug->notice->confirmUnlinkBuild    = "Replacing the solution version will disassociate the bug from the old version. Are you sure you want to disassociate the bug from %s?";
$lang->bug->notice->noSwitchBranch        = 'The linked module of Bug%s is not in the current branch. It will be omitted.';
$lang->bug->notice->confirmToStory        = 'The bug will be closed automatically after transferring to requirements, and the reason for closing is that the bug has been converted to requirements status.';
$lang->bug->notice->productDitto          = "This bug is not linked to the same {$lang->productCommon} as the last one is!";
$lang->bug->notice->noBug                 = 'Keine Bugs. ';
$lang->bug->notice->noModule              = '<div>Sie haben keine Module</div><div>Jetzt verwalten</div>';
$lang->bug->notice->delayWarning          = " <strong class='text-danger'> Delay %s days </strong>";

$lang->bug->error = new stdclass();
$lang->bug->error->notExist       = "Bug doesn't exist.";
$lang->bug->error->cannotActivate = 'Bugs with a status other than Resolved or Closed cannot be activated.';
$lang->bug->error->stepsNotEmpty  = "The reproduction step cannot be empty.";
