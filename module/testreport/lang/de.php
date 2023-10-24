<?php
$lang->testreport->common       = 'Test Bericht';
$lang->testreport->id           = 'ID';
$lang->testreport->browse       = 'Test Bericht';
$lang->testreport->create       = 'Erstellen';
$lang->testreport->edit         = 'Bearbeiten';
$lang->testreport->delete       = 'Löschen';
$lang->testreport->export       = 'Exportieren';
$lang->testreport->exportAction = 'Export Report';
$lang->testreport->view         = 'Anzeigen';
$lang->testreport->recreate     = 'Neu erstellen';

$lang->testreport->title       = 'Titel';
$lang->testreport->product     = $lang->productCommon;
$lang->testreport->bugTitle    = 'Bug Titel';
$lang->testreport->storyTitle  = 'Story Titel';
$lang->testreport->project     = $lang->projectCommon;
$lang->testreport->execution   = 'Execution';
$lang->testreport->testtask    = 'Test Build';
$lang->testreport->tasks       = $lang->testreport->testtask;
$lang->testreport->startEnd    = 'Start&Ende';
$lang->testreport->owner       = 'Besitzer';
$lang->testreport->members     = 'Mitglieder';
$lang->testreport->begin       = 'Start';
$lang->testreport->end         = 'Ende';
$lang->testreport->stories     = 'Teststories';
$lang->testreport->bugs        = 'Bugs';
$lang->testreport->builds      = 'Build Info';
$lang->testreport->goal        = 'Projektziel';
$lang->testreport->cases       = 'Fälle';
$lang->testreport->bugInfo     = 'Bug Info';
$lang->testreport->report      = 'Zusammenfassung';
$lang->testreport->legacyBugs  = 'Legacy Bugs';
$lang->testreport->createdBy   = 'CreatedBy';
$lang->testreport->createdDate = 'Datum';
$lang->testreport->objectID    = 'Objekt';
$lang->testreport->objectType  = 'Objekt Type';
$lang->testreport->profile     = 'Profil';
$lang->testreport->value       = 'Wert';
$lang->testreport->none        = 'Keine';
$lang->testreport->all         = 'Alle Berichte';
$lang->testreport->deleted     = 'Gelöscht';
$lang->testreport->selectTask  = 'Create report by request';

$lang->testreport->legendBasic       = 'Basis Info';
$lang->testreport->legendStoryAndBug = 'Storys und Bugs des Tests';
$lang->testreport->legendBuild       = 'Build Info';
$lang->testreport->legendCase        = 'Fall ausgeführt';
$lang->testreport->legendLegacyBugs  = 'Legacy Bugs';
$lang->testreport->legendReport      = 'Bericht';
$lang->testreport->legendComment     = 'Summe';
$lang->testreport->legendMore        = 'Mehr';
$lang->testreport->date              = 'Date';

$lang->testreport->bugSeverityGroups   = 'Nach Dringlichkeit';
$lang->testreport->bugTypeGroups       = 'Nach Typ';
$lang->testreport->bugStatusGroups     = 'Nach Status';
$lang->testreport->bugOpenedByGroups   = 'Nach Ersteller';
$lang->testreport->bugResolvedByGroups = 'Nach Löser';
$lang->testreport->bugResolutionGroups = 'Nach Lösung';
$lang->testreport->bugModuleGroups     = 'Nach Modul';
$lang->testreport->bugStageGroups      = 'Bug Priority Distribution';
$lang->testreport->bugHandleGroups     = 'Distribution of daily bug processing';
$lang->testreport->legacyBugs          = 'Legacy Bugs';
$lang->testreport->bugConfirmedRate    = 'Bestätigte Bugs Rate (Lösung ist gelöst oder verschoben / Status ist gelöst oder Geschlossen)';
$lang->testreport->bugCreateByCaseRate = 'Bug in Fall Rate (Bugs erstellt in Fall / Neu hinzugefügte Bugs)';

$lang->testreport->bugStageList = array();
$lang->testreport->bugStageList['generated'] = 'Generated Bugs';
$lang->testreport->bugStageList['legacy']    = 'Legacy Bugs';
$lang->testreport->bugStageList['resolved']  = 'Resolved Bugs';

$lang->testreport->caseSummary     = ' <strong>%s</strong> Fälle in Summe : <strong>%s</strong> ausgeführt, <strong>%s</strong> Ergebnisse, <strong>%s</strong> fehlgeschlagen.';
$lang->testreport->buildSummary    = 'Getsetetes <strong>%s</strong> Build.';
$lang->testreport->confirmDelete   = 'Möchten Sie diesen Bericht löschen?';
$lang->testreport->moreNotice      = 'Mehr Funktionen können mit Hilfe der Erweiterungen hinzugefügt werden, oder Sie kontaktieren uns für Anpassungen.';
$lang->testreport->exportNotice    = "Exportiert von <a href='http://www.zentao.net' target='_blank' style='color:grey'>ZenTaoPMS</a>";
$lang->testreport->noReport        = "Es wurde kein Bericht erstellt. Bitte prüfen Sie später noch mal.";
$lang->testreport->foundBugTip     = "Bugs erstellt in diesem Build und innerhalb der Testperiode.";
$lang->testreport->legacyBugTip    = "Aktive Bugs, oder gelöst nach der Testperiode.";
$lang->testreport->activatedBugTip = "Reactived bugs during the testtask.";
$lang->testreport->fromCaseBugTip  = "Bugs created after case-failure in the test period.";
$lang->testreport->errorTrunk      = "Die Trunk Version kann kein Testbericht erstellen. Bitte passen Sie die zugeordnete Version an!";
$lang->testreport->noTestTask      = "No test requests for this {$lang->productCommon}, so no reports can be generated. Please go to {$lang->productCommon} which has test requests and then generate the report.";
$lang->testreport->noObjectID      = "No test request or {$lang->executionCommon} is selected, so no report can be generated.";
$lang->testreport->moreProduct     = "Ein Testbericht kann nur innerhalb des selben Produkts erstellt werden.";
$lang->testreport->hiddenCase      = "Hide %s use cases";
$lang->testreport->goalTip         = "Descriptive information about the {$lang->execution->common} of this build";

$lang->testreport->bugSummary = <<<EOD
Total <strong>%s</strong> Bugs reported <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->foundBugTip}'><i class='icon-help'></i></a>,
<strong>%s</strong> Bugs remained unresolved <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->legacyBugTip}'><i class='icon-help'></i></a>,
<strong>%s</strong> Bugs reactivated <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->activatedBugTip}'><i class='icon-help'></i></a>,
<strong>%s</strong> Bugs found from the running of cases<a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->fromCaseBugTip}'><i class='icon-help'></i></a>.
Bug Effective Rate <a data-toggle='tooltip' class='text-warning' title='Resolution is resolved or delayed / status is resolved or closed'><i class='icon-help'></i></a>: <strong>%s</strong>，Bugs-reported-from-cases rate<a data-toggle='tooltip' class='text-warning' title='Bugs created from cases / bugs'><i class='icon-help'></i></a>: <strong>%s</strong>
EOD;
