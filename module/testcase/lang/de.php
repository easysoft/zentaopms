<?php
/**
 * The testcase module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id: en.php 4966 2013-07-02 02:59:25Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->testcase->id               = 'ID';
$lang->testcase->product          = $lang->productCommon;
$lang->testcase->module           = 'Module';
$lang->testcase->lib              = "Bibliothek";
$lang->testcase->branch           = "Branch/Platform";
$lang->testcase->moduleAB         = 'Module';
$lang->testcase->story            = 'Story';
$lang->testcase->storyVersion     = 'Story Version';
$lang->testcase->color            = 'Color';
$lang->testcase->order            = 'Rank';
$lang->testcase->title            = 'Titel';
$lang->testcase->precondition     = 'Voraussetzungen';
$lang->testcase->pri              = 'Priorität';
$lang->testcase->type             = 'Typ';
$lang->testcase->status           = 'Status';
$lang->testcase->subStatus        = 'Sub Status';
$lang->testcase->steps            = 'Schritte';
$lang->testcase->openedBy         = 'Ersteller';
$lang->testcase->openedDate       = 'Erstellt am';
$lang->testcase->lastEditedBy     = 'Bearbeitet von';
$lang->testcase->result           = 'Ergebnis';
$lang->testcase->real             = 'Details';
$lang->testcase->keywords         = 'Tags';
$lang->testcase->files            = 'Dateien';
$lang->testcase->linkCase         = 'Verbundene Fälle';
$lang->testcase->linkCases        = 'Fall verbinden';
$lang->testcase->unlinkCase       = 'Fall verbindung aufheben';
$lang->testcase->stage            = 'Phase';
$lang->testcase->reviewedBy       = 'Überprüft von';
$lang->testcase->reviewedDate     = 'Überprüft von';
$lang->testcase->reviewResult     = 'Prüfungsbericht';
$lang->testcase->reviewedByAB     = 'Von';
$lang->testcase->reviewedDateAB   = 'Datum';
$lang->testcase->reviewResultAB   = 'Ergebnis';
$lang->testcase->forceNotReview   = 'Keine Überprüfung';
$lang->testcase->lastEditedByAB   = 'Bearbeiter';
$lang->testcase->lastEditedDateAB = 'Bearbeitet am';
$lang->testcase->lastEditedDate   = 'Bearbeitet am';
$lang->testcase->version          = 'Fallversion';
$lang->testcase->lastRunner       = 'Letzte Ausführung';
$lang->testcase->lastRunDate      = 'Zeit';
$lang->testcase->assignedTo       = 'An';
$lang->testcase->colorTag         = 'Farb-Tag';
$lang->testcase->lastRunResult    = 'Ergebnis';
$lang->testcase->desc             = 'Schritt';
$lang->testcase->xml              = 'XML';
$lang->testcase->expect           = 'Erwartet';
$lang->testcase->allProduct       = "Alle {$lang->productCommon}";
$lang->testcase->fromBug          = 'Von Bug';
$lang->testcase->toBug            = 'An Bug';
$lang->testcase->changed          = 'Geändert';
$lang->testcase->bugs             = 'Bugs erstellt';
$lang->testcase->bugsAB           = 'B';
$lang->testcase->results          = 'Ergebnis';
$lang->testcase->resultsAB        = 'R';
$lang->testcase->stepNumber       = 'Anzahl der Schritte';
$lang->testcase->stepNumberAB     = 'S';
$lang->testcase->createBug        = 'In Bug konvertieren';
$lang->testcase->fromModule       = 'Modul';
$lang->testcase->fromCase         = 'Source Case';
$lang->testcase->sync             = 'Sync. Case';
$lang->testcase->ignore           = 'Ignore';
$lang->testcase->fromTesttask     = 'From Test Request';
$lang->testcase->fromCaselib      = 'From CaseLib';
$lang->testcase->deleted          = 'Deleted';
$lang->case = $lang->testcase;  // For dao checking using. Because 'case' is a php keywords, so the module name is testcase, table name is still case.

$lang->testcase->stepID      = 'ID';
$lang->testcase->stepDesc    = 'Schritt';
$lang->testcase->stepExpect  = 'Erwartet';
$lang->testcase->stepVersion = 'Version';

$lang->testcase->common                  = 'Fall';
$lang->testcase->index                   = "Home";
$lang->testcase->create                  = "Fall erstellen";
$lang->testcase->batchCreate             = "Mehrere erstellen";
$lang->testcase->delete                  = "Löschen";
$lang->testcase->deleteAction            = "Löschen Case";
$lang->testcase->view                    = "Übersicht";
$lang->testcase->review                  = "Prüfung";
$lang->testcase->reviewAB                = "Prüfung";
$lang->testcase->batchReview             = "Mehrere Prüfungfen";
$lang->testcase->edit                    = "Bearbeiten";
$lang->testcase->batchEdit               = "Mehrere bearbeiten ";
$lang->testcase->batchChangeModule       = "Mehrere Module ändern";
$lang->testcase->confirmLibcaseChange    = "Confirm CaseLib Change";
$lang->testcase->ignoreLibcaseChange     = "Ignore CaseLib Change";
$lang->testcase->batchChangeBranch       = "Mehrere Branches ändern";
$lang->testcase->groupByStories          = 'Group by Story';
$lang->testcase->batchDelete             = "Mehrere löschen ";
$lang->testcase->batchConfirmStoryChange = "Mehrere bestätigen";
$lang->testcase->batchCaseTypeChange     = "Mehrere Typen änderen";
$lang->testcase->browse                  = "Fälle";
$lang->testcase->groupCase               = "Nach Gruppe";
$lang->testcase->import                  = "Importieren";
$lang->testcase->importAction            = "Import Case";
$lang->testcase->fileImport              = "Importiere CSV";
$lang->testcase->importFromLib           = "Import aus Bibliothek";
$lang->testcase->showImport              = "Import Anzeigen";
$lang->testcase->exportTemplet           = "Export Vorlage";
$lang->testcase->export                  = "Exportiere Daten";
$lang->testcase->exportAction            = "Export Case";
$lang->testcase->reportChart             = 'Bericht Chart';
$lang->testcase->reportAction            = 'Case Report';
$lang->testcase->confirmChange           = 'Falländerung bestätigen';
$lang->testcase->confirmStoryChange      = 'Storyänderung bestätigen';
$lang->testcase->copy                    = 'Fall kopieren';
$lang->testcase->group                   = 'Gruppe';
$lang->testcase->groupName               = 'Gruppenname';
$lang->testcase->step                    = 'Schritt';
$lang->testcase->stepChild               = 'Teilschritt';
$lang->testcase->viewAll                 = 'Alle anziegen';

$lang->testcase->new = 'Neu';

$lang->testcase->num = 'Fälle:';

$lang->testcase->deleteStep   = 'Löschen';
$lang->testcase->insertBefore = 'Einfügen vor';
$lang->testcase->insertAfter  = 'Einfügen nach';

$lang->testcase->assignToMe   = 'Mir zuordnen';
$lang->testcase->openedByMe   = 'Von mir erstellt';
$lang->testcase->allCases     = 'Alle';
$lang->testcase->allTestcases = 'Alle Fälle';
$lang->testcase->needConfirm  = 'Story geändert';
$lang->testcase->bySearch     = 'Suche';
$lang->testcase->unexecuted   = 'Wartend';

$lang->testcase->lblStory       = 'Story';
$lang->testcase->lblLastEdited  = 'Zuletzt bearbeitet';
$lang->testcase->lblTypeValue   = 'Typenliste';
$lang->testcase->lblStageValue  = 'Stageliste';
$lang->testcase->lblStatusValue = 'Statusliste';

$lang->testcase->legendBasicInfo    = 'Basis Info';
$lang->testcase->legendAttatch      = 'Datei';
$lang->testcase->legendLinkBugs     = 'Bug';
$lang->testcase->legendOpenAndEdit  = 'Erstellt/Bearbeitet';
$lang->testcase->legendComment      = 'Bemerkung';

$lang->testcase->summary            = "Fälle auf dieser Seite: <strong>%s</strong> insgesamt, <strong>%s</strong> ausgeführt.";
$lang->testcase->confirmDelete      = 'Möchten Sie diesen Testfall schließen?';
$lang->testcase->confirmBatchDelete = 'Möchten Sie diese Testfälle schließen?';
$lang->testcase->ditto              = 'Dito';
$lang->testcase->dittoNotice        = 'Dieser Fall gehört nicht zu den Produkt!';

$lang->testcase->reviewList[0] = 'Nein';
$lang->testcase->reviewList[1] = 'Ja';

$lang->testcase->priList[0] = '';
$lang->testcase->priList[3] = 3;
$lang->testcase->priList[1] = 1;
$lang->testcase->priList[2] = 2;
$lang->testcase->priList[4] = 4;

/* Define the types. */
$lang->testcase->typeList['']            = '';
$lang->testcase->typeList['feature']     = 'Feature';
$lang->testcase->typeList['performance'] = 'Performance';
$lang->testcase->typeList['config']      = 'Konfiguration';
$lang->testcase->typeList['install']     = 'Installation';
$lang->testcase->typeList['security']    = 'Sicherheit';
$lang->testcase->typeList['interface']   = 'Schnittstelle';
$lang->testcase->typeList['unit']        = 'Unit';
$lang->testcase->typeList['other']       = 'Sonstiges';

$lang->testcase->stageList['']           = '';
$lang->testcase->stageList['unittest']   = 'Unit Test';
$lang->testcase->stageList['feature']    = 'Funktions Test';
$lang->testcase->stageList['intergrate'] = 'Integrations Test';
$lang->testcase->stageList['system']     = 'System Test';
$lang->testcase->stageList['smoke']      = 'Smoking Test';
$lang->testcase->stageList['bvt']        = 'Build Validation Test';

$lang->testcase->reviewResultList['']        = '';
$lang->testcase->reviewResultList['pass']    = 'Erfolgreich';
$lang->testcase->reviewResultList['clarify'] = 'Klärung';

$lang->testcase->statusList['']            = '';
$lang->testcase->statusList['wait']        = 'Wartend';
$lang->testcase->statusList['normal']      = 'Normal';
$lang->testcase->statusList['blocked']     = 'Blockiert';
$lang->testcase->statusList['investigate'] = 'Untersuchung';

$lang->testcase->resultList['n/a']     = 'Ignorieren';
$lang->testcase->resultList['pass']    = 'OK';
$lang->testcase->resultList['fail']    = 'Fehler';
$lang->testcase->resultList['blocked'] = 'Blockiert';

$lang->testcase->buttonToList = 'Zurück';

$lang->testcase->errorEncode      = 'Keine Daten. Bitte wählen Sie das richtige Encoding und versuchen Sie es erneut!';
$lang->testcase->noFunction       = 'Iconv und mb_convert_encoding wurde nicht gefunden. Sie können die Daten nicht in das gewünschte Format konvertieren!';
$lang->testcase->noRequire        = "Zeile %s hat “%s” was ein benötigtes Feld ist und nicht leer sein darf.";
$lang->testcase->noLibrary        = "Es existiert keine Bibliothek. Bitte erstellen Sie eine.";
$lang->testcase->mustChooseResult = 'Prüfungsergebnis wird benötigt.';
$lang->testcase->noModule         = '<div>Es existieren keine Module</div><div>Jetzt verwalten</div>';
$lang->testcase->noCase           = 'Keine Fälle. ';

$lang->testcase->searchStories = 'Story suchen';
$lang->testcase->selectLib     = 'Bibliothek wählen';

$lang->testcase->action = new stdclass();
$lang->testcase->action->fromlib  = array('main' => '$date, importiert von <strong>$actor</strong> aus Bibliothek <strong>$extra</strong>.');
$lang->testcase->action->reviewed = array('main' => '$date, überprüft von <strong>$actor</strong> und Prüfungsergebnis ist <strong>$extra</strong>.', 'extra' => 'reviewResultList');

$lang->testcase->featureBar['browse']['all']         = $lang->testcase->allCases;
$lang->testcase->featureBar['browse']['wait']        = 'Wartend';
$lang->testcase->featureBar['browse']['needconfirm'] = $lang->testcase->needConfirm;
$lang->testcase->featureBar['browse']['group']       = '';
$lang->testcase->featureBar['browse']['suite']       = 'Suite';
$lang->testcase->featureBar['browse']['zerocase']    = '';
$lang->testcase->featureBar['groupcase']             = $lang->testcase->featureBar['browse'];
