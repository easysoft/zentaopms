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
$lang->testcase->subStatus        = 'Sub Status';
$lang->testcase->steps            = 'Steps';
$lang->testcase->openedBy         = 'CreatedBy';
$lang->testcase->openedDate       = 'CreatedDate';
$lang->testcase->lastEditedBy     = 'EditedBy';
$lang->testcase->result           = 'Result';
$lang->testcase->real             = 'Details';
$lang->testcase->keywords         = 'Tags';
$lang->testcase->files            = 'Files';
$lang->testcase->linkCase         = 'Linked Cases';
$lang->testcase->linkCases        = 'Link Case';
$lang->testcase->unlinkCase       = 'Unlink Cases';
$lang->testcase->stage            = 'Phase';
$lang->testcase->reviewedBy       = 'ReviewedBy';
$lang->testcase->reviewedDate     = 'ReviewedDate';
$lang->testcase->reviewResult     = 'Review Result';
$lang->testcase->reviewedByAB     = 'ReviewedBy';
$lang->testcase->reviewedDateAB   = 'ReviewedDate';
$lang->testcase->reviewResultAB   = 'Result';
$lang->testcase->forceNotReview   = 'No Review Required';
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
$lang->testcase->deleted          = 'Deleted';
$lang->case = $lang->testcase;  // For dao checking using. Because 'case' is a php keywords, so the module name is testcase, table name is still case.

$lang->testcase->stepID      = 'ID';
$lang->testcase->stepDesc    = 'Step';
$lang->testcase->stepExpect  = 'Expectation';
$lang->testcase->stepVersion = 'Version';

$lang->testcase->common                  = 'Case';
$lang->testcase->index                   = "Case Home";
$lang->testcase->create                  = "Add Case";
$lang->testcase->batchCreate             = "Batch Add";
$lang->testcase->delete                  = "Delete";
$lang->testcase->deleteAction            = "Delete Case";
$lang->testcase->view                    = "Case Detail";
$lang->testcase->review                  = "Need Review";
$lang->testcase->reviewAB                = "Review";
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
$lang->testcase->batchCaseTypeChange     = "Batch Change Types";
$lang->testcase->browse                  = "Case List";
$lang->testcase->groupCase               = "View By Group";
$lang->testcase->import                  = "Import";
$lang->testcase->importAction            = "Import Case";
$lang->testcase->fileImport              = "Import CSV";
$lang->testcase->importFromLib           = "Import From Library";
$lang->testcase->showImport              = "Show Import";
$lang->testcase->exportTemplet           = "Export Template";
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

$lang->testcase->new = 'New';

$lang->testcase->num = 'Case Rows:';

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

$lang->testcase->legendBasicInfo    = 'Basic Information';
$lang->testcase->legendAttatch      = 'Files';
$lang->testcase->legendLinkBugs     = 'Bugs';
$lang->testcase->legendOpenAndEdit  = 'Create/Edit';
$lang->testcase->legendComment      = 'Comment';

$lang->testcase->summary            = "Total <strong>%s</strong> cases on this page, and <strong>%s</strong> cases run.";
$lang->testcase->confirmDelete      = 'Do you want to delete this case?';
$lang->testcase->confirmBatchDelete = 'Do you want to batch delete cases?';
$lang->testcase->ditto              = 'Ditto';
$lang->testcase->dittoNotice        = 'This Case is not linked to the Product as the last one is!';

$lang->testcase->reviewList[0] = 'NO';
$lang->testcase->reviewList[1] = 'YES';

$lang->testcase->priList[0] = '';
$lang->testcase->priList[3] = 3;
$lang->testcase->priList[1] = 1;
$lang->testcase->priList[2] = 2;
$lang->testcase->priList[4] = 4;

/* Define the types. */
$lang->testcase->typeList['']            = '';
$lang->testcase->typeList['feature']     = 'Feature';
$lang->testcase->typeList['performance'] = 'Performance';
$lang->testcase->typeList['config']      = 'Configuration';
$lang->testcase->typeList['install']     = 'Installation';
$lang->testcase->typeList['security']    = 'Security';
$lang->testcase->typeList['interface']   = 'Interface';
$lang->testcase->typeList['unit']        = 'Unit';
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

$lang->testcase->errorEncode      = 'No data. Please select right encoding and upload again!';
$lang->testcase->noFunction       = 'Iconv and mb_convert_encoding are not found. You cannot convert the data to the encoding you want!';
$lang->testcase->noRequire        = "Row %s has“%s ”which is a required field and it should not be blank.";
$lang->testcase->noLibrary        = "No library exists. Please create one first.";
$lang->testcase->mustChooseResult = 'Review result is required.';
$lang->testcase->noModule         = '<div>You have no modules.</div><div>Manage it now.</div>';
$lang->testcase->noCase           = 'No cases yet. ';

$lang->testcase->searchStories = 'Enter to search for stories';
$lang->testcase->selectLib     = 'Select Library';

$lang->testcase->action = new stdclass();
$lang->testcase->action->fromlib  = array('main' => '$date, imported by <strong>$actor</strong> from <strong>$extra</strong>.');
$lang->testcase->action->reviewed = array('main' => '$date, recorded by <strong>$actor</strong> and the review result is <strong>$extra</strong>.', 'extra' => 'reviewResultList');

$lang->testcase->featureBar['browse']['all']         = $lang->testcase->allCases;
$lang->testcase->featureBar['browse']['wait']        = 'Waiting';
$lang->testcase->featureBar['browse']['needconfirm'] = $lang->testcase->needConfirm;
$lang->testcase->featureBar['browse']['group']       = '';
$lang->testcase->featureBar['browse']['suite']       = 'Suite';
$lang->testcase->featureBar['browse']['zerocase']    = '';
$lang->testcase->featureBar['groupcase']             = $lang->testcase->featureBar['browse'];
