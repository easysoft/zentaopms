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
$lang->testcase->lib              = "Case Library";
$lang->testcase->branch           = "Branch/Platform";
$lang->testcase->moduleAB         = 'Module';
$lang->testcase->story            = 'Story';
$lang->testcase->storyVersion     = 'Story Version';
$lang->testcase->color            = 'Color';
$lang->testcase->order            = 'Order';
$lang->testcase->title            = 'Title';
$lang->testcase->precondition     = 'Prerequisite';
$lang->testcase->pri              = 'Priority';
$lang->testcase->type             = 'Type';
$lang->testcase->status           = 'Status';
$lang->testcase->statusAB         = 'Status';
$lang->testcase->subStatus        = 'Sub Status';
$lang->testcase->steps            = 'Steps';
$lang->testcase->openedBy         = 'CreatedBy';
$lang->testcase->openedByAB       = 'Reporter';
$lang->testcase->openedDate       = 'CreatedDate';
$lang->testcase->lastEditedBy     = 'EditedBy';
$lang->testcase->result           = 'Result';
$lang->testcase->real             = 'Details';
$lang->testcase->keywords         = 'Tags';
$lang->testcase->files            = 'Files';
$lang->testcase->linkCase         = 'Linked Cases';
$lang->testcase->linkCases        = 'Link Case';
$lang->testcase->unlinkCase       = 'Unlink Cases';
$lang->testcase->linkBug          = 'Linked Bugs';
$lang->testcase->linkBugs         = 'Link Bug';
$lang->testcase->unlinkBug        = 'Unlink Bugs';
$lang->testcase->stage            = 'Phase';
$lang->testcase->scriptedBy       = 'ScriptedBy';
$lang->testcase->scriptedDate     = 'ScriptedDate';
$lang->testcase->scriptStatus     = 'Script Status';
$lang->testcase->scriptLocation   = 'Script Location';
$lang->testcase->reviewedBy       = 'ReviewedBy';
$lang->testcase->reviewedDate     = 'ReviewedDate';
$lang->testcase->reviewResult     = 'Review Result';
$lang->testcase->reviewedByAB     = 'ReviewedBy';
$lang->testcase->reviewedDateAB   = 'ReviewedDate';
$lang->testcase->reviewResultAB   = 'Result';
$lang->testcase->forceNotReview   = 'No Review Required';
$lang->testcase->isReviewed       = 'is it reviewed';
$lang->testcase->lastEditedByAB   = 'EditedBy';
$lang->testcase->lastEditedDateAB = 'Edited';
$lang->testcase->lastEditedDate   = 'EditedDate';
$lang->testcase->version          = 'Case Version';
$lang->testcase->lastRunner       = 'RunBy';
$lang->testcase->lastRunDate      = 'LastRun';
$lang->testcase->assignedTo       = 'AssingedTo';
$lang->testcase->colorTag         = 'Color';
$lang->testcase->lastRunResult    = 'Result';
$lang->testcase->desc             = 'Steps';
$lang->testcase->parent           = 'Parent';
$lang->testcase->xml              = 'XML';
$lang->testcase->expect           = 'Expectations';
$lang->testcase->allProduct       = "All {$lang->productCommon}s";
$lang->testcase->fromBug          = 'From Bug';
$lang->testcase->toBug            = 'To Bug';
$lang->testcase->changed          = 'Changed';
$lang->testcase->bugs             = 'Reported Bug';
$lang->testcase->bugsAB           = 'B';
$lang->testcase->results          = 'Result';
$lang->testcase->resultsAB        = 'R';
$lang->testcase->stepNumber       = 'Steps';
$lang->testcase->stepNumberAB     = 'S';
$lang->testcase->createBug        = 'Report Bug';
$lang->testcase->fromModule       = 'Source Module';
$lang->testcase->fromCase         = 'Source Case';
$lang->testcase->sync             = 'Synchronize Case';
$lang->testcase->ignore           = 'Ignore';
$lang->testcase->fromTesttask     = 'From Test Request';
$lang->testcase->fromCaselib      = 'From Case Library';
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
$lang->testcase->stepDesc          = 'Step';
$lang->testcase->stepExpect        = 'Expectation';
$lang->testcase->stepVersion       = 'Version';
$lang->testcase->stepSameLevel     = 'Sib';
$lang->testcase->stepSubLevel      = 'Sub';
$lang->testcase->expectDisabledTip = 'Expect disabled when has sub steps.';
$lang->testcase->dragNestedTip     = 'Supports up to three levels of nesting, cannot be dragged here';

$lang->testcase->index                   = "Case Home";
$lang->testcase->create                  = "Add Case";
$lang->testcase->batchCreate             = "Batch Add";
$lang->testcase->delete                  = "Delete";
$lang->testcase->deleteAction            = "Delete Case";
$lang->testcase->view                    = "Case Detail";
$lang->testcase->review                  = "Need Review";
$lang->testcase->reviewAB                = "Review";
$lang->testcase->reviewAction            = "Review Case";
$lang->testcase->batchReview             = "Batch Review";
$lang->testcase->edit                    = "Edit Case";
$lang->testcase->batchEdit               = "Batch Edit ";
$lang->testcase->batchChangeModule       = "Batch Change Modules";
$lang->testcase->confirmLibcaseChange    = "Confirm CaseLib Change";
$lang->testcase->ignoreLibcaseChange     = "Ignore CaseLib Change";
$lang->testcase->batchChangeBranch       = "Batch Change Branches";
$lang->testcase->groupByStories          = 'Group by Story';
$lang->testcase->batchDelete             = "Batch Delete ";
$lang->testcase->batchConfirmStoryChange = "Batch Confirm";
$lang->testcase->batchChangeType         = "Batch Change Types";
$lang->testcase->browse                  = "Case List";
$lang->testcase->listView                = "View by List";
$lang->testcase->groupCase               = "View By Group";
$lang->testcase->groupView               = "Group View";
$lang->testcase->zeroCase                = "Stories without cases";
$lang->testcase->import                  = "Import";
$lang->testcase->importAction            = "Import Case";
$lang->testcase->importCaseAction        = "Import Case";
$lang->testcase->fileImport              = "Import CSV";
$lang->testcase->importFile              = "Import File";
$lang->testcase->importFromLib           = "Import From Library";
$lang->testcase->showImport              = "Show Import";
$lang->testcase->exportTemplate          = "Export Template";
$lang->testcase->export                  = "Export Data";
$lang->testcase->exportAction            = "Export Case";
$lang->testcase->reportChart             = 'Report Chart';
$lang->testcase->reportAction            = 'Case Report';
$lang->testcase->confirmChange           = 'Confirm Case Change';
$lang->testcase->confirmStoryChange      = 'Confirm Story Change';
$lang->testcase->copy                    = 'Copy Case';
$lang->testcase->group                   = 'Group';
$lang->testcase->groupName               = 'Group Name';
$lang->testcase->step                    = 'Steps';
$lang->testcase->stepChild               = 'Child Steps';
$lang->testcase->viewAll                 = 'All Cases';
$lang->testcase->importToLib             = "Import To Library";
$lang->testcase->showScript              = 'Show Script';
$lang->testcase->autoScript              = 'Script';
$lang->testcase->autoCase                = 'Automation';

$lang->testcase->new = 'New';

$lang->testcase->num      = 'Case Rows';
$lang->testcase->encoding = 'Encoding';

$lang->testcase->deleteStep   = 'Delete';
$lang->testcase->insertBefore = 'Inserted Before';
$lang->testcase->insertAfter  = 'Inserted After';

$lang->testcase->assignToMe   = 'AssignedToMe';
$lang->testcase->openedByMe   = 'CreatedByMe';
$lang->testcase->allCases     = 'All';
$lang->testcase->allTestcases = 'All Cases';
$lang->testcase->needConfirm  = 'Story Changed';
$lang->testcase->bySearch     = 'Search';
$lang->testcase->unexecuted   = 'Pending';

$lang->testcase->lblStory       = 'Linked Story';
$lang->testcase->lblLastEdited  = 'EditedBy';
$lang->testcase->lblTypeValue   = 'Type Value';
$lang->testcase->lblStageValue  = 'Phase Value';
$lang->testcase->lblStatusValue = 'Status Value';

$lang->testcase->legendBasicInfo   = 'Basic Information';
$lang->testcase->legendAttach      = 'Files';
$lang->testcase->legendLinkBugs    = 'Bugs';
$lang->testcase->legendOpenAndEdit = 'Create/Edit';
$lang->testcase->legendComment     = 'Comment';
$lang->testcase->legendOther       = 'Other Related';

$lang->testcase->confirmDelete         = 'Do you want to delete this case?';
$lang->testcase->confirmBatchDelete    = 'Do you want to batch delete cases?';
$lang->testcase->ditto                 = 'Ditto';
$lang->testcase->dittoNotice           = "This Case is not linked to the {$lang->productCommon} as the last one is!";
$lang->testcase->confirmUnlinkTesttask = 'The case [%s] is already associated in the testtask order of the previous branch/platform, after adjusting the branch/platform, it will be removed from the test list of the previous branch/platform, please confirm whether to continue to modify.';

$lang->testcase->reviewList[0] = 'NO';
$lang->testcase->reviewList[1] = 'YES';

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
$lang->testcase->typeList['interface']   = 'Interface';
$lang->testcase->typeList['feature']     = 'Feature';
$lang->testcase->typeList['install']     = 'Installation';
$lang->testcase->typeList['config']      = 'Configuration';
$lang->testcase->typeList['performance'] = 'Performance';
$lang->testcase->typeList['security']    = 'Security';
$lang->testcase->typeList['other']       = 'Others';

$lang->testcase->stageList['']           = '';
$lang->testcase->stageList['unittest']   = 'Unit Testing';
$lang->testcase->stageList['feature']    = 'Function Testing';
$lang->testcase->stageList['intergrate'] = 'Integration Testing';
$lang->testcase->stageList['system']     = 'System Testing';
$lang->testcase->stageList['smoke']      = 'Smoking Testing';
$lang->testcase->stageList['bvt']        = 'BVT Testing';

$lang->testcase->reviewResultList['']        = '';
$lang->testcase->reviewResultList['pass']    = 'Pass';
$lang->testcase->reviewResultList['clarify'] = 'To Be Clarified';

$lang->testcase->statusList['']            = '';
$lang->testcase->statusList['wait']        = 'Waiting';
$lang->testcase->statusList['normal']      = 'Normal';
$lang->testcase->statusList['blocked']     = 'Blocked';
$lang->testcase->statusList['investigate'] = 'Studying';

$lang->testcase->resultList['n/a']     = 'Ignore';
$lang->testcase->resultList['pass']    = 'Pass';
$lang->testcase->resultList['fail']    = 'Fail';
$lang->testcase->resultList['blocked'] = 'Blocked';

$lang->testcase->buttonToList = 'Back';

$lang->testcase->whichLine        = 'Line No.%s : ';
$lang->testcase->stepsEmpty       = 'Step %s cannot be empty.';
$lang->testcase->errorEncode      = 'No data. Please select right encoding and upload again!';
$lang->testcase->noFunction       = 'Iconv and mb_convert_encoding are not found. You cannot convert the data to the encoding you want!';
$lang->testcase->noRequire        = "Row %s has“%s ”which is a required field and it should not be blank.";
$lang->testcase->noRequireTip     = "“%s”is a required field and it should not be blank.";
$lang->testcase->noLibrary        = "No library exists. Please create one first.";
$lang->testcase->mustChooseResult = 'Review result is required.';
$lang->testcase->noModule         = '<div>You have no modules.</div><div>Manage it now.</div>';
$lang->testcase->noCase           = 'No cases yet. ';
$lang->testcase->importedCases    = 'The case with ID%s has been imported in the same module and has been ignored.';
$lang->testcase->importedFromLib  = '%s items imported successfully: %s.';

$lang->testcase->searchStories = 'Enter to search for stories';
$lang->testcase->selectLib     = 'Select Library';
$lang->testcase->selectLibAB   = 'Select Library';

$lang->testcase->action = new stdclass();
$lang->testcase->action->fromlib               = array('main' => '$date, imported by <strong>$actor</strong> from <strong>$extra</strong>.');
$lang->testcase->action->reviewed              = array('main' => '$date, recorded by <strong>$actor</strong> and the review result is <strong>$extra</strong>.', 'extra' => 'reviewResultList');
$lang->testcase->action->linked2project        = array('main' => '$date, linked ' . $lang->projectCommon . ' by <strong>$actor</strong> to <strong>$extra</strong>.');
$lang->testcase->action->unlinkedfromproject   = array('main' => '$date, removed by <strong>$actor</strong> from <strong>$extra</strong>.');
$lang->testcase->action->linked2execution      = array('main' => '$date, linked ' . $lang->executionCommon . ' by  <strong>$actor</strong> to <strong>$extra</strong>.');
$lang->testcase->action->unlinkedfromexecution = array('main' => '$date, removed by <strong>$actor</strong> from <strong>$extra</strong>.');

$lang->testcase->featureBar['browse']['all']         = $lang->testcase->allCases;
$lang->testcase->featureBar['browse']['wait']        = 'Waiting';
$lang->testcase->featureBar['browse']['needconfirm'] = $lang->testcase->needConfirm;

$lang->testcase->importXmind     = "Import XMIND";
$lang->testcase->exportXmind     = "Export XMIND";
$lang->testcase->getXmindImport  = "Get Mindmap";
$lang->testcase->showXMindImport = "Display Mindmap";
$lang->testcase->saveXmindImport = "Save Mindmap";

$lang->testcase->xmindImport           = "Imort XMIND";
$lang->testcase->xmindExport           = "Export XMIND";
$lang->testcase->xmindImportEdit       = "XMIND Edit";
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
