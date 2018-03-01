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
$lang->testcase->lib              = "Library";
$lang->testcase->branch           = "Branch/Platform";
$lang->testcase->moduleAB         = 'Module';
$lang->testcase->story            = 'Story';
$lang->testcase->title            = 'Title';
$lang->testcase->precondition     = 'Prerequisite';
$lang->testcase->pri              = 'Priority';
$lang->testcase->type             = 'Type';
$lang->testcase->status           = 'Status';
$lang->testcase->steps            = 'Steps';
$lang->testcase->openedBy         = 'Creator';
$lang->testcase->openedDate       = 'Create on';
$lang->testcase->lastEditedBy     = 'Edited by';
$lang->testcase->result           = 'Result';
$lang->testcase->real             = 'Details';
$lang->testcase->keywords         = 'Keywords';
$lang->testcase->files            = 'Files';
$lang->testcase->linkCase         = 'Linked Cases';
$lang->testcase->linkCases        = 'Link Cases';
$lang->testcase->unlinkCase       = 'Unlink Cases';
$lang->testcase->stage            = 'Stage';
$lang->testcase->reviewedBy       = 'Reviewed By';
$lang->testcase->reviewedDate     = 'Reviewed on';
$lang->testcase->reviewResult     = 'Review Result';
$lang->testcase->reviewedByAB     = 'By';
$lang->testcase->reviewedDateAB   = 'Date';
$lang->testcase->reviewResultAB   = 'Result';
$lang->testcase->forceNotReview   = 'No Review';
$lang->testcase->lastEditedByAB   = 'Edited By';
$lang->testcase->lastEditedDateAB = 'Edited on';
$lang->testcase->lastEditedDate   = 'Edited on';
$lang->testcase->version          = 'Case Version';
$lang->testcase->lastRunner       = 'LastRun';
$lang->testcase->lastRunDate      = 'Time';
$lang->testcase->assignedTo       = 'To';
$lang->testcase->colorTag         = 'Color Tag';
$lang->testcase->lastRunResult    = 'Result';
$lang->testcase->desc             = 'Step';
$lang->testcase->expect           = 'Expect';
$lang->testcase->allProduct       = "All {$lang->productCommon}";
$lang->testcase->fromBug          = 'From Bug';
$lang->testcase->toBug            = 'To Bug';
$lang->testcase->changed          = 'Changed';
$lang->testcase->bugs             = 'Bugs Generated';
$lang->testcase->bugsAB           = 'B';
$lang->testcase->results          = 'Results';
$lang->testcase->resultsAB        = 'R';
$lang->testcase->stepNumber       = 'Number of steps';
$lang->testcase->stepNumberAB     = 'S';
$lang->testcase->createBug        = 'Convert to Bug';
$lang->testcase->fromModule       = 'Source Module';
$lang->case = $lang->testcase;  // For dao checking using. Because 'case' is a php keywords, so the module name is testcase, table name is still case.

$lang->testcase->stepID      = 'ID';
$lang->testcase->stepDesc    = 'Step';
$lang->testcase->stepExpect  = 'Expect';
$lang->testcase->stepVersion = 'Version';

$lang->testcase->common                  = 'Case';
$lang->testcase->index                   = "Home";
$lang->testcase->create                  = "Create Case";
$lang->testcase->batchCreate             = "Batch Create Cases";
$lang->testcase->delete                  = "Delete";
$lang->testcase->view                    = "Overview";
$lang->testcase->review                  = "Review";
$lang->testcase->batchReview             = "Batch Review";
$lang->testcase->edit                    = "Edit";
$lang->testcase->batchEdit               = "Batch Edit ";
$lang->testcase->batchChangeModule       = "Batch Change Module";
$lang->testcase->batchChangeBranch       = "Batch Change Branch";
$lang->testcase->delete                  = "Delete";
$lang->testcase->batchDelete             = "Batch Delete ";
$lang->testcase->batchConfirmStoryChange = "Batch Confirm Story Change";
$lang->testcase->batchCaseTypeChange     = "Batch Change type";
$lang->testcase->browse                  = "Cases";
$lang->testcase->groupCase               = "View By Group";
$lang->testcase->import                  = "Import";
$lang->testcase->importFile              = "Import CSV";
$lang->testcase->importFromLib           = "Import From Library";
$lang->testcase->showImport              = "Show Import";
$lang->testcase->exportTemplet           = "Export Template";
$lang->testcase->export                  = "Export Data";
$lang->testcase->reportChart             = 'Report Chart';
$lang->testcase->confirmChange           = 'Confirm Case Change';
$lang->testcase->confirmStoryChange      = 'Confirm Story Change';
$lang->testcase->copy                    = 'Duplicate Case';
$lang->testcase->group                   = 'Group';
$lang->testcase->groupName               = 'Group Name';
$lang->testcase->step                    = 'Step';
$lang->testcase->stepChild               = 'Child';

$lang->testcase->new = 'New';

$lang->testcase->num = 'Cases:';

$lang->testcase->deleteStep   = 'Delete';
$lang->testcase->insertBefore = 'Inserted Before';
$lang->testcase->insertAfter  = 'Inserted After';

$lang->testcase->assignToMe  = 'AssignedToMe';
$lang->testcase->openedByMe  = 'CreatedByMe';
$lang->testcase->allCases    = 'All';
$lang->testcase->needConfirm = 'StoryChanged';
$lang->testcase->bySearch    = 'Search';

$lang->testcase->lblStory       = 'Story';
$lang->testcase->lblLastEdited  = 'Last Edit';
$lang->testcase->lblTypeValue   = 'Type List';
$lang->testcase->lblStageValue  = 'Stage List';
$lang->testcase->lblStatusValue = 'Status List';

$lang->testcase->legendBasicInfo    = 'Basic Info';
$lang->testcase->legendAttatch      = 'File';
$lang->testcase->legendLinkBugs     = 'Bug';
$lang->testcase->legendOpenAndEdit  = 'Create/Edit';
$lang->testcase->legendComment      = 'Remark';

$lang->testcase->confirmDelete      = 'Do you want to delete this Test Case?';
$lang->testcase->confirmBatchDelete = 'Do you want to batch delete thess Test Cases?';
$lang->testcase->ditto              = 'Ditto';
$lang->testcase->dittoNotice        = 'This Case does not belong to the Product as the previous one!';

$lang->testcase->reviewList[0] = 'NO';
$lang->testcase->reviewList[1] = 'YES';

$lang->testcase->priList[3] = 3;
$lang->testcase->priList[1] = 1;
$lang->testcase->priList[2] = 2;
$lang->testcase->priList[4] = 4;

/* Define the types. */
$lang->testcase->typeList['']            = '';
$lang->testcase->typeList['feature']     = 'Function';
$lang->testcase->typeList['performance'] = 'Performance';
$lang->testcase->typeList['config']      = 'Config';
$lang->testcase->typeList['install']     = 'Install';
$lang->testcase->typeList['security']    = 'Security';
$lang->testcase->typeList['interface']   = 'Interface';
$lang->testcase->typeList['other']       = 'Other';

$lang->testcase->stageList['']           = '';
$lang->testcase->stageList['unittest']   = 'Unit Test';
$lang->testcase->stageList['feature']    = 'Function Test';
$lang->testcase->stageList['intergrate'] = 'Integration Test';
$lang->testcase->stageList['system']     = 'System Test';
$lang->testcase->stageList['smoke']      = 'Smoking Test';
$lang->testcase->stageList['bvt']        = 'BVT Test';

$lang->testcase->reviewResultList['']        = '';
$lang->testcase->reviewResultList['pass']    = 'Pass';
$lang->testcase->reviewResultList['clarify'] = 'Clarify';

$lang->testcase->groups['']           = 'Group ';
$lang->testcase->groups['story']      = 'Story Group';
$lang->testcase->groups['assignedTo'] = 'Assign Group';

$lang->testcase->statusList['']            = '';
$lang->testcase->statusList['wait']        = 'ToReview';
$lang->testcase->statusList['normal']      = 'Normal';
$lang->testcase->statusList['blocked']     = 'Blocked';
$lang->testcase->statusList['investigate'] = 'Investigating';

$lang->testcase->resultList['n/a']     = 'Ignore';
$lang->testcase->resultList['pass']    = 'Pass';
$lang->testcase->resultList['fail']    = 'Fail';
$lang->testcase->resultList['blocked'] = 'Blocked';

$lang->testcase->buttonToList = 'Back';

$lang->testcase->errorEncode      = 'No Data. Please select right encoding and upload again!';
$lang->testcase->noFunction       = 'Iconv and mb_convert_encoding is not found. You cannot convert the data into the desired one!';
$lang->testcase->noRequire        = "Row %s has“%s”which is a required field and it should not be blank.";
$lang->testcase->noLibrary        = "No library exists. Please create one first.";
$lang->testcase->mustChooseResult = 'Review result is required.';

$lang->testcase->searchStories = 'Enter to searcu Story';
$lang->testcase->selectLib     = 'Select Library';

$lang->testcase->action = new stdclass();
$lang->testcase->action->fromlib  = array('main' => '$date, imported by <strong>$actor</strong> from Library <strong>$extra</strong>.');
$lang->testcase->action->reviewed = array('main' => '$date, recorded by <strong>$actor</strong> and the review result is <strong>$extra</strong>.', 'extra' => 'reviewResultList');

$lang->testcase->featureBar['browse']['all']         = $lang->testcase->allCases;
$lang->testcase->featureBar['browse']['wait']        = 'Wait';
$lang->testcase->featureBar['browse']['needconfirm'] = $lang->testcase->needConfirm;
$lang->testcase->featureBar['browse']['group']       = '';
$lang->testcase->featureBar['browse']['suite']       = 'Suite';
$lang->testcase->featureBar['browse']['zerocase']    = '';
$lang->testcase->featureBar['groupcase']             = $lang->testcase->featureBar['browse'];
