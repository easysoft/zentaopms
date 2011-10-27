<?php
/**
 * The testcase module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->testcase->id             = 'ID';
$lang->testcase->product        = 'Product';
$lang->testcase->module         = 'Module';
$lang->testcase->story          = 'Story';
$lang->testcase->storyVersion   = 'Story version';
$lang->testcase->title          = 'Title';
$lang->testcase->precondition   = 'precondition';
$lang->testcase->pri            = 'Priority';
$lang->testcase->type           = 'Type';
$lang->testcase->status         = 'Status';
$lang->testcase->steps          = 'Steps';
$lang->testcase->frequency      = 'Frequency';
$lang->testcase->order          = 'Order';
$lang->testcase->openedBy       = 'Opened by ';
$lang->testcase->openedDate     = 'Opened date';
$lang->testcase->lastEditedBy   = 'Last edited by';
$lang->testcase->lastEditedDate = 'Last edited date';
$lang->testcase->version        = 'Version';
$lang->testcase->result         = 'Result';
$lang->testcase->real           = 'Real';
$lang->testcase->keywords       = 'Keywords';
$lang->testcase->files          = 'Files';
$lang->testcase->howRun         = 'How run';
$lang->testcase->scriptedBy     = 'Scripted by';
$lang->testcase->scriptedDate   = 'Scripted date';
$lang->testcase->scriptedStatus = 'Scripted status';
$lang->testcase->scriptedLocation = 'Script location';
$lang->testcase->linkCase         = 'Related cases';
$lang->testcase->stage            = 'Stage';
$lang->testcase->lastEditedByAB   = 'Last edited by';
$lang->testcase->lastEditedDateAB = 'Last edited date';
$lang->testcase->allProduct       = 'All product';
$lang->case = $lang->testcase;  // For dao checking using. Because 'case' is a php keywords, so the module name is testcase, table name is still case.

$lang->testcase->stepID     = 'ID';
$lang->testcase->stepDesc   = 'Step';
$lang->testcase->stepExpect = 'Expect';

$lang->testcase->common         = 'Case';
$lang->testcase->index          = "Index";
$lang->testcase->create         = "Create";
$lang->testcase->batchCreate    = "Batch create";
$lang->testcase->delete         = "Delete";
$lang->testcase->view           = "Info";
$lang->testcase->edit           = "Edit";
$lang->testcase->delete         = "Delete";
$lang->testcase->browse         = "Browse";
$lang->testcase->export         = "Export";
$lang->testcase->confirmStoryChange = 'Confirm story change';

$lang->testcase->deleteStep     = 'Delete';
$lang->testcase->insertBefore   = 'Insert before';
$lang->testcase->insertAfter    = 'Insert after';

$lang->testcase->selectProduct  = 'Select product';
$lang->testcase->byModule       = 'By module';
$lang->testcase->assignToMe     = 'Cases to me';
$lang->testcase->openedByMe     = 'My Opened cases';
$lang->testcase->allCases       = 'All case';
$lang->testcase->needConfirm    = 'Story changed';
$lang->testcase->moduleCases    = 'By module';
$lang->testcase->bySearch       = 'By search';
$lang->testcase->doneByMe       = 'My runed cases';

$lang->testcase->lblProductAndModule         = 'Product & module';
$lang->testcase->lblTypeAndPri               = 'Type & priority';
$lang->testcase->lblSystemBrowserAndHardware = 'OS & browser';
$lang->testcase->lblAssignAndMail            = 'Assigned & mailto';
$lang->testcase->lblStory                    = 'Story';
$lang->testcase->lblLastEdited               = 'Last edited';

$lang->testcase->legendRelated     = 'Related info';
$lang->testcase->legendBasicInfo   = 'Basic info';
$lang->testcase->legendMailto      = 'Mailto';
$lang->testcase->legendAttatch     = 'Files';
$lang->testcase->legendLinkBugs    = 'Bug';
$lang->testcase->legendOpenAndEdit = 'Open & edit';
$lang->testcase->legendStoryAndTask= 'Story';
$lang->testcase->legendCases       = 'Related cases';
$lang->testcase->legendSteps       = 'Steps';
$lang->testcase->legendAction      = 'Action';
$lang->testcase->legendHistory     = 'History';
$lang->testcase->legendComment     = 'Comment';
$lang->testcase->legendProduct     = 'Product & module';
$lang->testcase->legendVersion     = 'Versions';

$lang->testcase->confirmDelete = 'Are you sure to delete this case?';
$lang->testcase->same          = 'The same as above';
$lang->testcase->notes         = '(Notes: the type and title must be written, otherwise it is no use)';

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
$lang->testcase->typeList['other']       = 'Other';

$lang->testcase->stageList['']            = '';
$lang->testcase->stageList['unittest']    = 'Unit testing';
$lang->testcase->stageList['feature']     = 'Feature testing';
$lang->testcase->stageList['intergrate']  = 'Integrate testing';
$lang->testcase->stageList['system']      = 'System testing';
$lang->testcase->stageList['smoke']       = 'Smoking testing';
$lang->testcase->stageList['bvt']         = 'BVT testing';

$lang->testcase->statusList['']            = '';
$lang->testcase->statusList['normal']      = 'Normal';
$lang->testcase->statusList['blocked']     = 'Blocked';
$lang->testcase->statusList['investigate'] = 'Investigate';

$lang->testcase->resultList['n/a']     = 'N/A';
$lang->testcase->resultList['pass']    = 'Pass';
$lang->testcase->resultList['fail']    = 'Fail';
$lang->testcase->resultList['blocked'] = 'Blocked';

$lang->testcase->buttonEdit     = 'Edit';
$lang->testcase->buttonToList   = 'Back';
