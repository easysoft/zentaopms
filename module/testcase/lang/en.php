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
$lang->testcase->moduleAB         = 'Module';
$lang->testcase->story            = 'Story';
$lang->testcase->title            = 'Title';
$lang->testcase->precondition     = 'precondition';
$lang->testcase->pri              = 'Priority';
$lang->testcase->type             = 'Type';
$lang->testcase->status           = 'Status';
$lang->testcase->steps            = 'Steps';
$lang->testcase->openedBy         = 'Opened by ';
$lang->testcase->openedDate       = 'Opened date';
$lang->testcase->lastEditedBy     = 'Last edited by';
$lang->testcase->result           = 'Result';
$lang->testcase->real             = 'Real';
$lang->testcase->keywords         = 'Keywords';
$lang->testcase->files            = 'Files';
$lang->testcase->linkCase         = 'Related cases';
$lang->testcase->linkCases        = 'Link related cases';
$lang->testcase->unlinkCase       = 'unlink related case';
$lang->testcase->stage            = 'Stage';
$lang->testcase->lastEditedByAB   = 'Last edited by';
$lang->testcase->lastEditedDateAB = 'Last edited date';
$lang->testcase->lastEditedDate   = 'Last edited date';
$lang->testcase->version          = 'Case version';
$lang->testcase->lastRunner       = 'Runner';
$lang->testcase->lastRunDate      = 'Run date';
$lang->testcase->assignedTo       = 'Assigned to';
$lang->testcase->colorTag         = 'Color tag';
$lang->testcase->lastRunResult    = 'Result';
$lang->testcase->allProduct       = "All {$lang->productCommon}";
$lang->testcase->fromBug          = 'From bug';
$lang->testcase->toBug            = 'To bug';
$lang->testcase->changed          = 'Changed';
$lang->testcase->createBug        = 'Transform bug';
$lang->case = $lang->testcase;  // For dao checking using. Because 'case' is a php keywords, so the module name is testcase, table name is still case.

$lang->testcase->stepID     = 'ID';
$lang->testcase->stepDesc   = 'Step';
$lang->testcase->stepExpect = 'Expect';

$lang->testcase->common             = 'Case';
$lang->testcase->index              = "Index";
$lang->testcase->create             = "Create case";
$lang->testcase->batchCreate        = "Batch create";
$lang->testcase->delete             = "Delete";
$lang->testcase->view               = "Info";
$lang->testcase->edit               = "Edit";
$lang->testcase->batchEdit          = "Batch edit";
$lang->testcase->batchChangeModule  = "Batch change module";
$lang->testcase->delete             = "Delete";
$lang->testcase->batchDelete        = "Batch delete ";
$lang->testcase->browse             = "Browse";
$lang->testcase->groupCase          = "View case by group";
$lang->testcase->import             = "Import";
$lang->testcase->showImport         = "Show import";
$lang->testcase->exportTemplet      = "Export templet";
$lang->testcase->export             = "Export data";
$lang->testcase->confirmChange      = 'Confirm case change';
$lang->testcase->confirmStoryChange = 'Confirm story change';

$lang->testcase->new = 'New';
  
$lang->testcase->num    = 'The number of cases';

$lang->testcase->deleteStep   = 'Delete';
$lang->testcase->insertBefore = 'Insert before';
$lang->testcase->insertAfter  = 'Insert after';

$lang->testcase->assignToMe    = 'Cases to me';
$lang->testcase->openedByMe    = 'My Opened cases';
$lang->testcase->allCases      = 'All case';
$lang->testcase->needConfirm   = 'Story changed';
$lang->testcase->bySearch      = 'By search';

$lang->testcase->lblStory                    = 'Story';
$lang->testcase->lblLastEdited               = 'Last edited';
$lang->testcase->lblTypeValue                = 'List of type';
$lang->testcase->lblStageValue               = 'List of stage';
$lang->testcase->lblStatusValue              = 'List of status';

$lang->testcase->legendBasicInfo    = 'Basic info';
$lang->testcase->legendAttatch      = 'Files';
$lang->testcase->legendLinkBugs     = 'Bug';
$lang->testcase->legendOpenAndEdit  = 'Open & edit';
$lang->testcase->legendComment      = 'Comment';

$lang->testcase->confirmDelete      = 'Are you sure to delete this case?';
$lang->testcase->confirmBatchDelete = 'Are you sure to batch delete there cases?';
$lang->testcase->ditto              = 'Ditto';
$lang->testcase->dittoNotice        = 'Current case and case above it do not belong to same product!';

$lang->testcase->priList[3] = 3;
$lang->testcase->priList[1] = 1;
$lang->testcase->priList[2] = 2;
$lang->testcase->priList[4] = 4;

/* Define the types. */
$lang->testcase->typeList['']            = '';
$lang->testcase->typeList['feature']     = 'Feature';
$lang->testcase->typeList['performance'] = 'Performance';
$lang->testcase->typeList['config']      = 'Config';
$lang->testcase->typeList['install']     = 'Install';
$lang->testcase->typeList['security']    = 'Security';
$lang->testcase->typeList['interface']   = 'Interface';
$lang->testcase->typeList['other']       = 'Other';

$lang->testcase->stageList['']            = '';
$lang->testcase->stageList['unittest']    = 'Unit testing';
$lang->testcase->stageList['feature']     = 'Feature testing';
$lang->testcase->stageList['intergrate']  = 'Integrate testing';
$lang->testcase->stageList['system']      = 'System testing';
$lang->testcase->stageList['smoke']       = 'Smoking testing';
$lang->testcase->stageList['bvt']         = 'BVT testing';

$lang->testcase->groups['']      = 'Group view';
$lang->testcase->groups['story'] = 'Group by story';

$lang->testcase->statusList['']            = '';
$lang->testcase->statusList['normal']      = 'Normal';
$lang->testcase->statusList['blocked']     = 'Blocked';
$lang->testcase->statusList['investigate'] = 'Investigate';

$lang->testcase->resultList['n/a']     = 'N/A';
$lang->testcase->resultList['pass']    = 'Pass';
$lang->testcase->resultList['fail']    = 'Fail';
$lang->testcase->resultList['blocked'] = 'Blocked';

$lang->testcase->buttonToList = 'Back';

$lang->testcase->errorEncode = 'No data, please select right encode and upload again!';
$lang->testcase->noFunction  = 'Iconv and mb_convert_encoding does not exist, you can not turn the data into the desired coding!';
$lang->testcase->noRequire   = "In the row of %s, the %s is a required field";

$lang->testcase->searchStories = 'Type to search stories';

$lang->testcase->featureBar['browse']['all']         = $lang->testcase->allCases;
$lang->testcase->featureBar['browse']['needconfirm'] = $lang->testcase->needConfirm;
$lang->testcase->featureBar['browse']['group']       = '';
$lang->testcase->featureBar['browse']['zerocase']    = '';
$lang->testcase->featureBar['groupcase']             = $lang->testcase->featureBar['browse'];
