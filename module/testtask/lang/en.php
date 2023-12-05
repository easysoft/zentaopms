<?php
/**
 * The testtask module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: en.php 4490 2013-02-27 03:27:05Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->testtask->index            = "Request Home";
$lang->testtask->create           = "Submit Request";
$lang->testtask->reportChart      = 'Report';
$lang->testtask->delete           = "Delete Request";
$lang->testtask->importUnitResult = "Import Unit Result";
$lang->testtask->importUnit       = "Import Unit Result"; //Fix bug custom required testtask.
$lang->testtask->browseUnits      = "Unit Test List";
$lang->testtask->unitCases        = "Browse Unit Cases List";
$lang->testtask->view             = "Request Detail";
$lang->testtask->edit             = "Edit Request";
$lang->testtask->browse           = "Test Request";
$lang->testtask->linkCase         = "Link Case";
$lang->testtask->selectVersion    = "Select Version";
$lang->testtask->unlinkCase       = "Unlink";
$lang->testtask->batchUnlinkCases = "Batch Unlink Cases";
$lang->testtask->batchAssign      = "Batch Assign";
$lang->testtask->runCase          = "Run";
$lang->testtask->running          = "Excuting";
$lang->testtask->runningLog       = "Execution Log";
$lang->testtask->runNode          = "Executed by %s,Execute on node %s %s";
$lang->testtask->batchRun         = "Batch Run";
$lang->testtask->results          = "Results";
$lang->testtask->createBug        = "Bug(+)";
$lang->testtask->assign           = 'Assign';
$lang->testtask->cases            = 'Case List';
$lang->testtask->groupCase        = "View By Group";
$lang->testtask->pre              = 'Prev.';
$lang->testtask->next             = 'Next';
$lang->testtask->start            = "Start";
$lang->testtask->close            = "Close";
$lang->testtask->wait             = "Waiting";
$lang->testtask->block            = "Block";
$lang->testtask->activate         = "Activate";
$lang->testtask->testing          = "Testing";
$lang->testtask->blocked          = "Blocked";
$lang->testtask->done             = "Tested";
$lang->testtask->totalStatus      = "All";
$lang->testtask->all              = 'All';
$lang->testtask->allTasks         = 'All Requests';
$lang->testtask->collapseAll      = 'Collapse';
$lang->testtask->expandAll        = 'Expand';
$lang->testtask->auto             = 'Test Automation Tasks';
$lang->testtask->task             = 'Test Task';
$lang->testtask->run              = 'Test Run ID';
$lang->testtask->job              = 'Job';
$lang->testtask->compile          = 'Compile';
$lang->testtask->duration         = 'Duration';
$lang->testtask->myInvolved       = 'Involved';

$lang->testtask->viewAction     = "View Request";
$lang->testtask->casesAction    = 'Browse Cases List';
$lang->testtask->activateAction = "Activate Request";
$lang->testtask->blockAction    = "Block Request";
$lang->testtask->closeAction    = "Close Request";
$lang->testtask->startAction    = "Start Request";
$lang->testtask->resultsAction  = "Case Result";
$lang->testtask->reportAction   = 'Report';

$lang->testtask->id                = 'ID';
$lang->testtask->common            = 'Request';
$lang->testtask->product           = $lang->productCommon;
$lang->testtask->project           = $lang->projectCommon;;
$lang->testtask->execution         = $lang->execution->common;
$lang->testtask->type              = 'Type';
$lang->testtask->build             = 'Build';
$lang->testtask->owner             = 'Owner';
$lang->testtask->members           = 'Participant';
$lang->testtask->executor          = 'Executor';
$lang->testtask->execTime          = 'Exec Time';
$lang->testtask->pri               = 'Priority';
$lang->testtask->name              = 'Request Name';
$lang->testtask->unitName          = 'Name Of Unit Test';
$lang->testtask->begin             = 'Begin';
$lang->testtask->end               = 'End';
$lang->testtask->realBegan         = 'Actual Started Date';
$lang->testtask->realFinishedDate  = 'Actual Finished Date';
$lang->testtask->desc              = 'Description';
$lang->testtask->mailto            = 'Mailto';
$lang->testtask->status            = 'Status';
$lang->testtask->subStatus         = 'Sub Status';
$lang->testtask->testreport        = 'Test Report';
$lang->testtask->assignedTo        = 'Assigned';
$lang->testtask->linkVersion       = 'Build';
$lang->testtask->lastRunAccount    = 'RunBy';
$lang->testtask->lastRunTime       = 'Last Run';
$lang->testtask->lastRunResult     = 'Result';
$lang->testtask->reportField       = 'Report';
$lang->testtask->files             = 'Upload';
$lang->testtask->case              = 'Case List';
$lang->testtask->version           = 'Version';
$lang->testtask->caseResult        = 'Test Result';
$lang->testtask->stepResults       = 'Step Result';
$lang->testtask->lastRunner        = 'RunBy';
$lang->testtask->lastRunDate       = 'Last Run';
$lang->testtask->date              = 'Tested on';;
$lang->testtask->deleted           = "Deleted";
$lang->testtask->resultFile        = "Result File";
$lang->testtask->caseCount         = 'Case Count';
$lang->testtask->passCount         = 'Pass';
$lang->testtask->failCount         = 'Fail';
$lang->testtask->summary           = '%s cases, %s failures, %s time.';
$lang->testtask->stepSummary       = 'Total %s steps, %s passes, %s failures.';
$lang->testtask->unitSummary       = 'Total %s unit test results.';
$lang->testtask->pageSummary       = 'Total testtasks: <strong>%s</strong>.';
$lang->testtask->mySummary         = 'Total testtasks: <strong>%s</strong>, Wait: <strong>%s</strong>, Testing: <strong>%s</strong>, Blocked: <strong>%s</strong>.';
$lang->testtask->allSummary        = 'Total testtasks: <strong>%s</strong>, Wait: <strong>%s</strong>, Testing: <strong>%s</strong>, Blocked: <strong>%s</strong>, Done: <strong>%s</strong>.';
$lang->testtask->checkedAllSummary = 'Seleted: <strong>%total%</strong>, Wait: <strong>%wait%</strong>, Testing: <strong>%testing%</strong>, Blocked: <strong>%blocked%</strong>.';

$lang->testtask->beginAndEnd = 'Duration';
$lang->testtask->to          = 'To';

$lang->testtask->legendDesc      = 'Description';
$lang->testtask->legendReport    = 'Report';
$lang->testtask->legendBasicInfo = 'Basic Info';

$lang->testtask->statusList['wait']    = 'Waiting';
$lang->testtask->statusList['doing']   = 'Doing';
$lang->testtask->statusList['done']    = 'Closed';
$lang->testtask->statusList['blocked'] = 'Blocked';

$lang->testtask->priList[1] = '1';
$lang->testtask->priList[2] = '2';
$lang->testtask->priList[3] = '3';
$lang->testtask->priList[4] = '4';

$lang->testtask->unlinkedCases = 'Unlinked Case';
$lang->testtask->linkByBuild   = 'Copy from build';
$lang->testtask->linkByStory   = 'Link by Story';
$lang->testtask->linkByBug     = 'Link by Bug';
$lang->testtask->linkBySuite   = 'Link by Suite';
$lang->testtask->browseBySuite = 'Browse by Suite';
$lang->testtask->passAll       = 'Pass All';
$lang->testtask->pass          = 'Pass';
$lang->testtask->fail          = 'Failed';
$lang->testtask->showResult    = 'Run <label class="label primary-pale rounded-full h-3 px-1.5 mx-1">%s</label> times';
$lang->testtask->showFail      = 'Failed <label class="label danger-pale rounded-full h-3 px-1.5 mx-1">%s</label> times';
$lang->testtask->runInTask     = ', in <strong>%s</strong> ';
$lang->testtask->runCaseResult = ', executed <strong>%s</strong> by <strong>%s</strong> , the results is <span class="text-%s font-bold">%s</span>.';

$lang->testtask->confirmDelete     = 'Do you want to delete this build?';
$lang->testtask->confirmUnlinkCase = 'Do you want to unlink this case?';
$lang->testtask->noticeNoOther     = "No test builds for this {$lang->productCommon}.";
$lang->testtask->noTesttask        = 'No requests. ';
$lang->testtask->checkLinked       = "Please check whether the {$lang->productCommon} that the test request is linked to has been linked to a {$lang->executionCommon}.";
$lang->testtask->noImportData      = 'The imported XML does not parse the data.';
$lang->testtask->unitXMLFormat     = 'Please select a file in JUnit XML format.';
$lang->testtask->titleOfAuto       = "%s automated testing";
$lang->testtask->cannotBeParsed    = 'The content of the imported XML file is in the wrong format and cannot be parsed.';
$lang->testtask->finishedDateLess  = 'Actual Finished Date cannot be <= Begin Date %s';
$lang->testtask->finishedDateMore  = 'Actual Finished Date cannot be > Today';
$lang->testtask->emptyUnitTip      = 'No unit test results.';

$lang->testtask->assignedToMe  = 'AssignedToMe';
$lang->testtask->allCases      = 'All Cases';

$lang->testtask->lblCases      = 'Case';
$lang->testtask->lblUnlinkCase = 'Unlink Case';
$lang->testtask->lblRunCase    = 'Run Case';
$lang->testtask->lblResults    = 'Result';

$lang->testtask->placeholder = new stdclass();
$lang->testtask->placeholder->begin = 'Begin';
$lang->testtask->placeholder->end   = 'End';

$lang->testtask->mail = new stdclass();
$lang->testtask->mail->create = new stdclass();
$lang->testtask->mail->edit   = new stdclass();
$lang->testtask->mail->close  = new stdclass();
$lang->testtask->mail->create->title = "%s created test request #%s:%s";
$lang->testtask->mail->edit->title   = "%s finished test request #%s:%s";
$lang->testtask->mail->close->title  = "%s closed test request #%s:%s";

$lang->testtask->action = new stdclass();
$lang->testtask->action->testtaskopened  = '$date,  <strong>$actor</strong> submitted Test Request<strong>$extra</strong>.' . "\n";
$lang->testtask->action->testtaskstarted = '$date,  <strong>$actor</strong> started Test Request<strong>$extra</strong>.' . "\n";
$lang->testtask->action->testtaskclosed  = '$date,  <strong>$actor</strong> completed Test Request<strong>$extra</strong>.' . "\n";

$lang->testtask->unexecuted = 'Pending';

/* Statistical statement. */
$lang->testtask->report = new stdclass();
$lang->testtask->report->common = 'Report';
$lang->testtask->report->select = 'Select Report Type';
$lang->testtask->report->create = 'Create Report';

$lang->testtask->report->testTaskPerRunResultTip = 'There are %s usecase, including %s passed,%s not executed, and %s failed';

$lang->testtask->report->charts['testTaskPerRunResult'] = 'Test Case Result';
$lang->testtask->report->charts['testTaskPerType']      = 'Test Case Type';
$lang->testtask->report->charts['testTaskPerModule']    = 'Test Case Module';
$lang->testtask->report->charts['testTaskPerRunner']    = 'Test Case RunBy';

$lang->testtask->featureBar['browse']['totalStatus'] = $lang->testtask->totalStatus;
$lang->testtask->featureBar['browse']['myinvolved']  = $lang->testtask->myInvolved;
$lang->testtask->featureBar['browse']['wait']        = $lang->testtask->wait;
$lang->testtask->featureBar['browse']['doing']       = $lang->testtask->testing;
$lang->testtask->featureBar['browse']['blocked']     = $lang->testtask->blocked;
$lang->testtask->featureBar['browse']['done']        = $lang->testtask->done;

$lang->testtask->featureBar['cases']['all']          = $lang->testtask->allCases;
$lang->testtask->featureBar['cases']['assignedtome'] = $lang->testtask->assignedToMe;

$lang->testtask->featureBar['linkcase']['all']     = $lang->all;
$lang->testtask->featureBar['linkcase']['bystory'] = $lang->testtask->linkByStory;
$lang->testtask->featureBar['linkcase']['bysuite'] = $lang->testtask->linkBySuite;
$lang->testtask->featureBar['linkcase']['bybuild'] = $lang->testtask->linkByBuild;
$lang->testtask->featureBar['linkcase']['bybug']   = $lang->testtask->linkByBug;

$lang->testtask->featureBar['browseunits']['all']       = 'All';
$lang->testtask->featureBar['browseunits']['newest']    = 'Recently';
$lang->testtask->featureBar['browseunits']['thisWeek']  = 'This week';
$lang->testtask->featureBar['browseunits']['lastWeek']  = 'Last week';
$lang->testtask->featureBar['browseunits']['thisMonth'] = 'This month';
$lang->testtask->featureBar['browseunits']['lastMonth'] = 'Last month';

$lang->testtask->typeList['integrate']   = 'Integration';
$lang->testtask->typeList['system']      = 'System';
$lang->testtask->typeList['acceptance']  = 'Acceptance';
$lang->testtask->typeList['performance'] = 'Performance';
$lang->testtask->typeList['safety']      = 'Safety';
