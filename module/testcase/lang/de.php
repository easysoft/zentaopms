<?php
/**
 * The testcase module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id: en.php 4966 2013-07-02 02:59:25Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->testcase->id               = 'ID';
$lang->testcase->product          = $lang->productCommon;
$lang->testcase->project          = $lang->projectCommon;
$lang->testcase->execution        = $lang->executionCommon;
$lang->testcase->linkStory        = 'linkStory';
$lang->testcase->module           = 'Module';
$lang->testcase->auto             = 'Test Automation Cases';
$lang->testcase->frame            = 'Test Automation Cramework';
$lang->testcase->howRun           = 'Testing Method';
$lang->testcase->frequency        = 'Frequency';
$lang->testcase->path             = 'Path';
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
$lang->testcase->type             = 'Type';
$lang->testcase->status           = 'Status';
$lang->testcase->statusAB         = 'Status';
$lang->testcase->subStatus        = 'Sub Status';
$lang->testcase->steps            = 'Schritte';
$lang->testcase->openedBy         = 'Ersteller';
$lang->testcase->openedByAB       = 'Reporter';
$lang->testcase->openedDate       = 'Erstellt am';
$lang->testcase->lastEditedBy     = 'Bearbeitet von';
$lang->testcase->result           = 'Ergebnis';
$lang->testcase->resultAB         = 'Ergebnis';
$lang->testcase->real             = 'Details';
$lang->testcase->keywords         = 'Tags';
$lang->testcase->files            = 'Dateien';
$lang->testcase->linkCase         = 'Verbundene Fälle';
$lang->testcase->linkCases        = 'Fall verbinden';
$lang->testcase->unlinkCase       = 'Fall verbindung aufheben';
$lang->testcase->linkBug          = 'Linked Bugs';
$lang->testcase->linkBugs         = 'Link Bug';
$lang->testcase->unlinkBug        = 'Unlink Bugs';
$lang->testcase->stage            = 'Phase';
$lang->testcase->scriptedBy       = 'ScriptedBy';
$lang->testcase->scriptedDate     = 'ScriptedDate';
$lang->testcase->scriptStatus     = 'Script Status';
$lang->testcase->scriptLocation   = 'Script Location';
$lang->testcase->reviewedBy       = 'Überprüft von';
$lang->testcase->reviewedDate     = 'Überprüft von';
$lang->testcase->reviewResult     = 'Prüfungsbericht';
$lang->testcase->reviewedByAB     = 'Von';
$lang->testcase->reviewedDateAB   = 'Datum';
$lang->testcase->forceNotReview   = 'Keine Überprüfung';
$lang->testcase->isReviewed       = 'is it reviewed';
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
$lang->testcase->parent           = 'Parent';
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
$lang->testcase->fromCaseID       = 'From Case ID';
$lang->testcase->fromCaseVersion  = 'From Case Version';
$lang->testcase->mailto           = 'Mailto';
$lang->testcase->deleted          = 'Deleted';
$lang->testcase->browseUnits      = 'Unit Test';
$lang->testcase->suite            = 'Test Suite';
$lang->testcase->executionStatus  = 'executionStatus';
$lang->testcase->caseType         = 'Case Type';
$lang->testcase->allType          = 'All Types';
$lang->testcase->automated        = 'Automated';
$lang->testcase->automation       = 'Automation Test';

$lang->case = $lang->testcase;  // For dao checking using. Because 'case' is a php keywords, so the module name is testcase, table name is still case.

$lang->testcase->stepID            = 'ID';
$lang->testcase->stepDesc          = 'Schritt';
$lang->testcase->stepExpect        = 'Erwartet';
$lang->testcase->stepVersion       = 'Version';
$lang->testcase->stepSameLevel     = 'Sib';
$lang->testcase->stepSubLevel      = 'Sub';
$lang->testcase->expectDisabledTip = 'Expect disabled when has sub steps.';
$lang->testcase->dragNestedTip     = 'Supports up to three levels of nesting, cannot be dragged here';

$lang->testcase->index                   = "Home";
$lang->testcase->create                  = "Fall erstellen";
$lang->testcase->batchCreate             = "Mehrere erstellen";
$lang->testcase->delete                  = "Löschen";
$lang->testcase->deleteAction            = "Löschen Case";
$lang->testcase->view                    = "Übersicht";
$lang->testcase->review                  = "Prüfung";
$lang->testcase->reviewAB                = "Prüfung";
$lang->testcase->reviewAction            = "Review Case";
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
$lang->testcase->batchChangeType         = "Mehrere Typen änderen";
$lang->testcase->browse                  = "Fälle";
$lang->testcase->listView                = "View by List";
$lang->testcase->groupCase               = "Nach Gruppe";
$lang->testcase->groupView               = "Group View";
$lang->testcase->zeroCase                = "Storys ohne Fälle";
$lang->testcase->import                  = "Importieren";
$lang->testcase->importAction            = "Import Case";
$lang->testcase->importCaseAction        = "Import Case";
$lang->testcase->fileImport              = "Importiere CSV";
$lang->testcase->importFile              = "Import File";
$lang->testcase->importFromLib           = "Import aus Bibliothek";
$lang->testcase->showImport              = "Import Anzeigen";
$lang->testcase->exportTemplate          = "Export Vorlage";
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
$lang->testcase->importToLib             = "Import To Library";
$lang->testcase->showScript              = 'Show Script';
$lang->testcase->autoScript              = 'Script';
$lang->testcase->autoCase                = 'Automation';

$lang->testcase->new = 'Neu';

$lang->testcase->num      = 'Fälle:';
$lang->testcase->encoding = 'Encoding';

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

$lang->testcase->legendBasicInfo   = 'Basis Info';
$lang->testcase->legendAttach      = 'Datei';
$lang->testcase->legendLinkBugs    = 'Bug';
$lang->testcase->legendOpenAndEdit = 'Erstellt/Bearbeitet';
$lang->testcase->legendComment     = 'Bemerkung';
$lang->testcase->legendOther       = 'Other Related';

$lang->testcase->confirmDelete         = 'Möchten Sie diesen Testfall schließen?';
$lang->testcase->confirmBatchDelete    = 'Möchten Sie diese Testfälle schließen?';
$lang->testcase->ditto                 = 'Dito';
$lang->testcase->dittoNotice           = "This Case is not linked to the {$lang->productCommon} as the last one is!";
$lang->testcase->confirmUnlinkTesttask = 'The case [%s] is already associated in the testtask order of the previous branch/platform, after adjusting the branch/platform, it will be removed from the test list of the previous branch/platform, please confirm whether to continue to modify.';

$lang->testcase->reviewList[0] = 'Nein';
$lang->testcase->reviewList[1] = 'Ja';

$lang->testcase->autoList = array();
$lang->testcase->autoList['']     = '';
$lang->testcase->autoList['auto'] = 'Yes';
$lang->testcase->autoList['no']   = 'No';

$lang->testcase->priList[0] = '';
$lang->testcase->priList[3] = 3;
$lang->testcase->priList[1] = 1;
$lang->testcase->priList[2] = 2;
$lang->testcase->priList[4] = 4;

/* Define the types. */
$lang->testcase->typeList['']            = '';
$lang->testcase->typeList['unit']        = 'Unit';
$lang->testcase->typeList['interface']   = 'Schnittstelle';
$lang->testcase->typeList['feature']     = 'Feature';
$lang->testcase->typeList['install']     = 'Installation';
$lang->testcase->typeList['config']      = 'Konfiguration';
$lang->testcase->typeList['performance'] = 'Performance';
$lang->testcase->typeList['security']    = 'Sicherheit';
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

$lang->testcase->whichLine        = 'Line No.%s : ';
$lang->testcase->stepsEmpty       = 'Step %s cannot be empty.';
$lang->testcase->errorEncode      = 'Keine Daten. Bitte wählen Sie das richtige Encoding und versuchen Sie es erneut!';
$lang->testcase->noFunction       = 'Iconv und mb_convert_encoding wurde nicht gefunden. Sie können die Daten nicht in das gewünschte Format konvertieren!';
$lang->testcase->noRequire        = "Zeile %s hat “%s” was ein benötigtes Feld ist und nicht leer sein darf.";
$lang->testcase->noRequireTip     = "“%s”is a required field and it should not be blank.";
$lang->testcase->noLibrary        = "Es existiert keine Bibliothek. Bitte erstellen Sie eine.";
$lang->testcase->mustChooseResult = 'Prüfungsergebnis wird benötigt.';
$lang->testcase->noModule         = '<div>Es existieren keine Module</div><div>Jetzt verwalten</div>';
$lang->testcase->noCase           = 'Keine Fälle. ';
$lang->testcase->importedCases    = 'The case with ID%s has been imported in the same module and has been ignored.';
$lang->testcase->importedFromLib  = '%s items imported successfully: %s.';
$lang->testcase->noStep           = 'No steps yet.';

$lang->testcase->searchStories = 'Story suchen';
$lang->testcase->selectLib     = 'Bibliothek wählen';
$lang->testcase->selectLibAB   = 'Bibliothek wählen';

$lang->testcase->action = new stdclass();
$lang->testcase->action->fromlib               = array('main' => '$date, importiert von <strong>$actor</strong> aus Bibliothek <strong>$extra</strong>.');
$lang->testcase->action->reviewed              = array('main' => '$date, überprüft von <strong>$actor</strong> und Prüfungsergebnis ist <strong>$extra</strong>.', 'extra' => 'reviewResultList');
$lang->testcase->action->linked2project        = array('main' => '$date, linked ' . $lang->projectCommon . ' by <strong>$actor</strong> to <strong>$extra</strong>.');
$lang->testcase->action->unlinkedfromproject   = array('main' => '$date, removed by <strong>$actor</strong> from <strong>$extra</strong>.');
$lang->testcase->action->linked2execution      = array('main' => '$date, linked ' . $lang->executionCommon . ' by  <strong>$actor</strong> to <strong>$extra</strong>.');
$lang->testcase->action->unlinkedfromexecution = array('main' => '$date, removed by <strong>$actor</strong> from <strong>$extra</strong>.');

$lang->testcase->featureBar['browse']['all']         = $lang->testcase->allCases;
$lang->testcase->featureBar['browse']['wait']        = 'Wartend';
$lang->testcase->featureBar['browse']['needconfirm'] = $lang->testcase->needConfirm;

$lang->testcase->importXmind     = "Import Xmind";
$lang->testcase->exportXmind     = "Export Xmind";
$lang->testcase->getXmindImport  = "Get Mindmap";
$lang->testcase->showXMindImport = "Display Mindmap";
$lang->testcase->saveXmindImport = "Save Mindmap";

$lang->testcase->xmindImport           = "Imort Xmind";
$lang->testcase->xmindExport           = "Export Xmind";
$lang->testcase->xmindImportEdit       = "Xmind Edit";
$lang->testcase->errorFileNotEmpty     = 'The uploaded file cannot be empty';
$lang->testcase->errorXmindUpload      = 'Upload failed';
$lang->testcase->errorFileFormat       = 'File format error';
$lang->testcase->moduleSelector        = 'Module Selection';
$lang->testcase->errorImportBadProduct = 'Product does not exist, import error';
$lang->testcase->errorSceneNotExist    = 'Scene [%d] not exists';
$lang->testcase->errorXmindConfig      = "%s characteristic character can only be 1-10 letters.";

$lang->testcase->save  = 'Save';
$lang->testcase->close = 'Close';

$lang->testcase->xmindImportSetting = 'Import Characteristic Character Settings';
$lang->testcase->xmindExportSetting = 'Export Characteristic Character Settings';
$lang->testcase->xmindSettingTip    = 'After the feature characters are set, the XMind theme can correspond to the ZenTao test case structure.';

$lang->testcase->settingModule = 'Module';
$lang->testcase->settingScene  = 'Scene';
$lang->testcase->settingCase   = 'Testcase';
$lang->testcase->settingPri    = 'Priority';
$lang->testcase->settingGroup  = 'Step Group';

$lang->testcase->caseNotExist = 'The test case in the imported file was not recognized and the import failed';
$lang->testcase->saveFail     = 'Save failed';
$lang->testcase->set2Scene    = 'Set as Scene';
$lang->testcase->set2Testcase = 'Set as Testcase';
$lang->testcase->clearSetting = 'Clear Settings';
$lang->testcase->setModule    = 'Set scene module';
$lang->testcase->pickModule   = 'Please select a module';
$lang->testcase->clearBefore  = 'Clear previous scenes';
$lang->testcase->clearAfter   = 'Clear the following scenes';
$lang->testcase->clearCurrent = 'Clear the current scene';
$lang->testcase->removeGroup  = 'Remove Group';
$lang->testcase->set2Group    = 'Set as Group';

$lang->testcase->exportTemplet = 'Export Template';

$lang->testcase->createScene      = "Add Scene";
$lang->testcase->changeScene      = "Drag to change the scene which it belongs";
$lang->testcase->batchChangeScene = "Batch change scene";
$lang->testcase->updateOrder      = "Drag Sort";
$lang->testcase->differentProduct = "Different product";

$lang->testcase->newScene           = "Add Scene";
$lang->testcase->sceneTitle         = 'Scene Name';
$lang->testcase->parentScene        = "Parent Scene";
$lang->testcase->scene              = "Scene";
$lang->testcase->summary            = 'Total %d Top Scene，%d Independent test case.';
$lang->testcase->summaryScene       = 'Total %d Top Scene.';
$lang->testcase->failSummary        = 'Total %d Cases, which did not pass %d.';
$lang->testcase->checkedSummary     = '{checked} checked test cases, {run} run.';
$lang->testcase->failCheckedSummary = '%total% checked test cases，%fail% run fail。';
$lang->testcase->deleteScene        = 'Delete Scene';
$lang->testcase->editScene          = 'Edit Scene';
$lang->testcase->hasChildren        = 'This scene has sub scene or test cases. Do you want to delete them all?';
$lang->testcase->confirmDeleteScene = 'Are you sure you want to delete the scene: \"%s\"?';
$lang->testcase->sceneb             = 'Scene';
$lang->testcase->onlyAutomated      = 'Only Automated';
$lang->testcase->onlyScene          = 'Only Scene';
$lang->testcase->iScene             = 'Scene';
$lang->testcase->generalTitle       = 'Title';
$lang->testcase->noScene            = 'No Scene';
$lang->testcase->rowIndex           = 'Row Index';
$lang->testcase->nestTotal          = 'nest total';
$lang->testcase->normal             = 'normal';

/* Translation for drag modal message box. */
$lang->testcase->dragModalTitle       = 'Drag and drop operation selection';
$lang->testcase->dragModalMessage     = '<p>There are two possible situations for the current operation: </p><p>1) Adjust the sequence.<br/> 2) Change its scenario, meanwhile its module will be changed accordingly.</p><p>Please select the operation you want to perform.</p>';
$lang->testcase->dragModalChangeScene = 'Change its scene';
$lang->testcase->dragModalChangeOrder = 'Reorder';

$lang->testcase->confirmBatchDeleteSceneCase = 'Are you sure you want to delete these scene or test cases in batch?';

$lang->scene = new stdclass();
$lang->scene->product = 'Product';
$lang->scene->branch  = 'Branch';
$lang->scene->module  = 'Module';
$lang->scene->parent  = 'Parent Scene';
$lang->scene->title   = 'Scene Name';
$lang->scene->noCase  = 'No case';
