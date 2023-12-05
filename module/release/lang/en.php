<?php
/**
 * The release module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->release->create           = 'Create Release';
$lang->release->edit             = 'Edit Release';
$lang->release->linkStory        = 'Link Story';
$lang->release->linkBug          = 'Link Bug';
$lang->release->delete           = 'Delete Release';
$lang->release->deleted          = 'Deleted';
$lang->release->view             = 'Release Detail';
$lang->release->browse           = 'Release List';
$lang->release->changeStatus     = 'Change Status';
$lang->release->batchUnlink      = 'Batch Unlink';
$lang->release->batchUnlinkStory = 'Batch Unlink Stories';
$lang->release->batchUnlinkBug   = 'Batch Unlink Bugs';

$lang->release->confirmDelete      = 'Do you want to delete this release?';
$lang->release->syncFromBuilds     = 'Link the stories completed in the version and the bugs solved to the release';
$lang->release->confirmUnlinkStory = 'Do you want to remove this story?';
$lang->release->confirmUnlinkBug   = 'Do you want to remove this bug?';
$lang->release->existBuild         = '『Build』『%s』existed. You could change『name』or choose a『build』.';
$lang->release->noRelease          = 'No releases yet.';
$lang->release->errorDate          = 'The release date should not be greater than today.';
$lang->release->confirmActivate    = 'Do you want to activate this release?';
$lang->release->confirmTerminate   = 'Do you want to pause this release?';

$lang->release->basicInfo = 'Basic Info';

$lang->release->id             = 'ID';
$lang->release->product        = $lang->productCommon;
$lang->release->branch         = 'Platform/Branch';
$lang->release->project        = $lang->projectCommon;
$lang->release->build          = 'Build';
$lang->release->includedBuild  = 'Included Builds';
$lang->release->relatedProject = 'Related ' . $lang->projectCommon;
$lang->release->name           = 'Name';
$lang->release->marker         = 'Milestone';
$lang->release->date           = 'Release Date';
$lang->release->desc           = 'Description';
$lang->release->files          = 'Files';
$lang->release->status         = 'Status';
$lang->release->subStatus      = 'Sub Status';
$lang->release->last           = 'Last Release';
$lang->release->unlinkStory    = 'Unlink Story';
$lang->release->unlinkBug      = 'Unlink Bug';
$lang->release->stories        = 'Finished Story';
$lang->release->bugs           = 'Resolved Bug';
$lang->release->leftBugs       = 'Active Bug';
$lang->release->generatedBugs  = 'Active Bug';
$lang->release->finishStories  = 'Finished %s Stories';
$lang->release->resolvedBugs   = 'Resolved %s Bugs';
$lang->release->createdBugs    = 'Unresolved %s Bug';
$lang->release->export         = 'Export as HTML';
$lang->release->yesterday      = 'Released Yesterday';
$lang->release->all            = 'All';
$lang->release->allProject     = 'All';
$lang->release->notify         = 'Notify';
$lang->release->notifyUsers    = 'Notify Users';
$lang->release->mailto         = 'Mailto';
$lang->release->mailContent    = '<p>Dear users,</p><p style="margin-left:30px;">The following requirements and bugs you feedback have been released in the %s. Please contact your account manager to check the latest version.</p>';
$lang->release->storyList      = '<p style="margin-left:30px;">Story List：%s。</p>';
$lang->release->bugList        = '<p style="margin-left:30px;">Bug List：%s。</p>';
$lang->release->pageAllSummary = 'Total releases: <strong>%s</strong>, Normal: <strong>%s</strong>, Terminate: <strong>%s</strong>.';
$lang->release->pageSummary    = "Total releases: <strong>%s</strong>.";
$lang->release->fileName       = 'File name';
$lang->release->exportRange    = 'Data to export';

$lang->release->storyTitle = 'Story Name';
$lang->release->bugTitle   = 'Bug Name';

$lang->release->filePath = 'Download : ';
$lang->release->scmPath  = 'SCM Path : ';

$lang->release->exportTypeList['all']     = 'All';
$lang->release->exportTypeList['story']   = 'Story';
$lang->release->exportTypeList['bug']     = 'Bug';
$lang->release->exportTypeList['leftbug'] = 'Active Bug';

$lang->release->statusList['']          = '';
$lang->release->statusList['normal']    = 'Active';
$lang->release->statusList['terminate'] = 'Terminated';

$lang->release->changeStatusList['normal']    = 'Active';
$lang->release->changeStatusList['terminate'] = 'Terminated';

$lang->release->action = new stdclass();
$lang->release->action->changestatus = array('main' => '$date, $extra by  <strong>$actor</strong>.', 'extra' => 'changeStatusList');
$lang->release->action->notified     = array('main' => '$date, <strong>$actor</strong> send notify.');

$lang->release->notifyList['FB'] = "Feedback By";
$lang->release->notifyList['PO'] = "{$lang->productCommon} Owner";
$lang->release->notifyList['QD'] = 'QA Manager';
$lang->release->notifyList['SC'] = 'Story Creator';
$lang->release->notifyList['ET'] = "{$lang->execution->common} Team Members";
$lang->release->notifyList['PT'] = "{$lang->projectCommon} Team Members";
$lang->release->notifyList['CT'] = "Copy To";

$lang->release->featureBar['browse']['all']       = $lang->release->all;
$lang->release->featureBar['browse']['normal']    = $lang->release->statusList['normal'];
$lang->release->featureBar['browse']['terminate'] = $lang->release->statusList['terminate'];

$lang->release->markerList[1] = 'Yes';
$lang->release->markerList[0] = 'No';
