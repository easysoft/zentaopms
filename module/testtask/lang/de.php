<?php
/**
 * The testtask module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: en.php 4490 2013-02-27 03:27:05Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->testtask->index            = "Home";
$lang->testtask->create           = "Test erstellen";
$lang->testtask->reportChart      = 'Bericht';
$lang->testtask->reportAction     = 'Case Report';
$lang->testtask->delete           = "Löschen";
$lang->testtask->importUnitResult = "Import Unit Result";
$lang->testtask->importunitresult = "Import Unit Result"; //Fix bug custom required testtask.
$lang->testtask->browseUnits      = "Unit Test List";
$lang->testtask->unitCases        = "Unit Test Cases";
$lang->testtask->view             = "Übersicht";
$lang->testtask->edit             = "Bearbeiten";
$lang->testtask->browse           = "Testaufgaben";
$lang->testtask->linkCase         = "Fälle";
$lang->testtask->selectVersion    = "Version wählen";
$lang->testtask->unlinkCase       = "Verknüpfung aufheben";
$lang->testtask->batchUnlinkCases = "Mehrere Verknüpfungen aufheben";
$lang->testtask->batchAssign      = "Mehrere zuordnen";
$lang->testtask->runCase          = "Ausführen";
$lang->testtask->batchRun         = "Mehrere ausführen";
$lang->testtask->results          = "Ergebnisse";
$lang->testtask->resultsAction    = "Case Result";
$lang->testtask->createBug        = "Bug erstellen";
$lang->testtask->assign           = 'Zuordnen';
$lang->testtask->cases            = 'Fälle';
$lang->testtask->groupCase        = "Nach Gruppe";
$lang->testtask->pre              = 'Vorherige';
$lang->testtask->next             = 'Nächste';
$lang->testtask->start            = "Start";
$lang->testtask->startAction      = "Start Request";
$lang->testtask->close            = "Schließen";
$lang->testtask->closeAction      = "Close Request";
$lang->testtask->wait             = "Wartend";
$lang->testtask->block            = "Block";
$lang->testtask->blockAction      = "Block Request";
$lang->testtask->activate         = "Aktiviert";
$lang->testtask->activateAction   = "Activate Request";
$lang->testtask->testing          = "Testen";
$lang->testtask->blocked          = "Blockiert";
$lang->testtask->done             = "Getestet";
$lang->testtask->totalStatus      = "Alle";
$lang->testtask->all              = "Alle " . $lang->productCommon;
$lang->testtask->allTasks         = 'Alle';
$lang->testtask->collapseAll      = 'Zuklappen';
$lang->testtask->expandAll        = 'Aufklappen';

$lang->testtask->id             = 'ID';
$lang->testtask->common         = 'Testaufgabe';
$lang->testtask->product        = $lang->productCommon;
$lang->testtask->project        = $lang->projectCommon;
$lang->testtask->build          = 'Build';
$lang->testtask->owner          = 'Besitzer';
$lang->testtask->executor       = 'Executor';
$lang->testtask->execTime       = 'Exec Time';
$lang->testtask->pri            = 'Priorität';
$lang->testtask->name           = 'Name';
$lang->testtask->begin          = 'Start';
$lang->testtask->end            = 'Ende';
$lang->testtask->desc           = 'Beschreibung';
$lang->testtask->mailto         = 'Mail an';
$lang->testtask->status         = 'Status';
$lang->testtask->subStatus      = 'Sub Status';
$lang->testtask->assignedTo     = 'Zugeordnet';
$lang->testtask->linkVersion    = 'Version';
$lang->testtask->lastRunAccount = 'Ausgeführt von';
$lang->testtask->lastRunTime    = 'Ausgeführt am';
$lang->testtask->lastRunResult  = 'Ergebnis';
$lang->testtask->reportField    = 'Bericht';
$lang->testtask->files          = 'Upload';
$lang->testtask->case           = 'Fall';
$lang->testtask->version        = 'Version';
$lang->testtask->caseResult     = 'Test Ergebnis';
$lang->testtask->stepResults    = 'Schritt Ergebnis';
$lang->testtask->lastRunner     = 'Ausgeführt von';
$lang->testtask->lastRunDate    = 'Ausgeführt am';
$lang->testtask->date           = 'Datum';
$lang->testtask->deleted        = "Deleted";
$lang->testtask->resultFile     = "Result File";
$lang->testtask->caseCount      = 'Case Count';
$lang->testtask->passCount      = 'Pass';
$lang->testtask->failCount      = 'Fail';
$lang->testtask->summary        = '%s cases, %s failures, %s time.';

$lang->testtask->beginAndEnd    = 'Datum';
$lang->testtask->to             = 'An';

$lang->testtask->legendDesc      = 'Beschreibung';
$lang->testtask->legendReport    = 'Bericht';
$lang->testtask->legendBasicInfo = 'Basis Info';

$lang->testtask->statusList['wait']    = 'Wartend';
$lang->testtask->statusList['doing']   = 'In Arbeit';
$lang->testtask->statusList['done']    = 'Erledigt';
$lang->testtask->statusList['blocked'] = 'Blockiert';

$lang->testtask->priList[0] = '';
$lang->testtask->priList[3] = '3';
$lang->testtask->priList[1] = '1';
$lang->testtask->priList[2] = '2';
$lang->testtask->priList[4] = '4';

$lang->testtask->unlinkedCases = 'Unverknüpfte Fälle';
$lang->testtask->linkByBuild   = 'Mit Build verknüpfen';
$lang->testtask->linkByStory   = 'Mit Story verknüpfen';
$lang->testtask->linkByBug     = 'Mit Bug verknüpfen';
$lang->testtask->linkBySuite   = 'Mit Suite verknüpfen';
$lang->testtask->passAll       = 'Alle erfolgreich';
$lang->testtask->pass          = 'Erfolgreich';
$lang->testtask->fail          = 'Fehlgeschlagen';
$lang->testtask->showResult    = 'Ausführungen <span class="text-info">%s</span>';
$lang->testtask->showFail      = 'Fehlgeschlagen <span class="text-danger">%s</span>';

$lang->testtask->confirmDelete     = 'Möchten Sie dieses Build löschen?';
$lang->testtask->confirmUnlinkCase = 'Möchten Sie die Verknüpfung zu dem Fall aufheben?';
$lang->testtask->noticeNoOther     = 'Es existieren keine weiteren Testaufgaben für dieses Produkt';
$lang->testtask->noTesttask        = 'Keine Testaufgaben. ';
$lang->testtask->checkLinked       = "Please check whether the product that the test request is linked to has been linked to a project.";
$lang->testtask->noImportData      = 'The imported XML does not parse the data.';
$lang->testtask->unitXMLFormat     = 'Please select a file in JUnit XML format.';
$lang->testtask->titleOfAuto       = "%s automated testing";

$lang->testtask->assignedToMe  = 'Meine';
$lang->testtask->allCases      = 'Alle Fälle';

$lang->testtask->lblCases      = 'Fälle';
$lang->testtask->lblUnlinkCase = 'Fallverknüpfung aufheben';
$lang->testtask->lblRunCase    = 'Fälle ausführen';
$lang->testtask->lblResults    = 'Ergebnisse';

$lang->testtask->placeholder = new stdclass();
$lang->testtask->placeholder->begin = 'Start';
$lang->testtask->placeholder->end   = 'Ende';

$lang->testtask->mail = new stdclass();
$lang->testtask->mail->create = new stdclass();
$lang->testtask->mail->edit   = new stdclass();
$lang->testtask->mail->close  = new stdclass();
$lang->testtask->mail->create->title = "%s erstellte Testaufgabe #%s:%s";
$lang->testtask->mail->edit->title   = "%s abgeschlossene Testaufgaben #%s:%s";
$lang->testtask->mail->close->title  = "%s geschlossene Testaufgaben #%s:%s";

$lang->testtask->action = new stdclass();
$lang->testtask->action->testtaskopened  = '$date,  <strong>$actor</strong> öffnete die Testaufgabe <strong>$extra</strong>.' . "\n";
$lang->testtask->action->testtaskstarted = '$date,  <strong>$actor</strong> startete die Testaufgabe <strong>$extra</strong>.' . "\n";
$lang->testtask->action->testtaskclosed  = '$date,  <strong>$actor</strong> hat die Testaufgabe abgeschlossen <strong>$extra</strong>.' . "\n";

$lang->testtask->unexecuted = 'Nicht ausgeführt';

/* 统计报表。*/
$lang->testtask->report = new stdclass();
$lang->testtask->report->common = 'Bericht';
$lang->testtask->report->select = 'Berichttyp wählen';
$lang->testtask->report->create = 'Erzeugen';

$lang->testtask->report->charts['testTaskPerRunResult'] = 'Ergebnisbericht';
$lang->testtask->report->charts['testTaskPerType']      = 'Berichttyp';
$lang->testtask->report->charts['testTaskPerModule']    = 'Modulbericht';
$lang->testtask->report->charts['testTaskPerRunner']    = 'Ausgeführt von Bericht';
$lang->testtask->report->charts['bugSeverityGroups']    = 'Dringlichkeitsbericht';
$lang->testtask->report->charts['bugStatusGroups']      = 'Status Bericht';
$lang->testtask->report->charts['bugOpenedByGroups']    = 'Bugersteller Bericht';
$lang->testtask->report->charts['bugResolvedByGroups']  = 'Gelöst von Bericht';
$lang->testtask->report->charts['bugResolutionGroups']  = 'Lösungsbersicht';
$lang->testtask->report->charts['bugModuleGroups']      = 'Bug Modul Bericht';

$lang->testtask->report->options = new stdclass();
$lang->testtask->report->options->graph  = new stdclass();
$lang->testtask->report->options->type   = 'pie';
$lang->testtask->report->options->width  = 500;
$lang->testtask->report->options->height = 140;

$lang->testtask->featureBar['browse']['totalStatus'] = $lang->testtask->totalStatus;
$lang->testtask->featureBar['browse']['wait']        = $lang->testtask->wait;
$lang->testtask->featureBar['browse']['doing']       = $lang->testtask->testing;
$lang->testtask->featureBar['browse']['blocked']     = $lang->testtask->blocked;
$lang->testtask->featureBar['browse']['done']        = $lang->testtask->done;
