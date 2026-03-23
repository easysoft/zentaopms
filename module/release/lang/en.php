<?php
/**
 * The release module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->release->create           = 'Create Release';
$lang->release->edit             = 'Edit Release';
$lang->release->linkStory        = 'Link Story';
$lang->release->linkBug          = 'Link Bug';
$lang->release->delete           = 'Delete Release';
$lang->release->deleted          = 'Deleted';
$lang->release->view             = 'Release Details';
$lang->release->browse           = 'Release List';
$lang->release->publish          = 'Publish';
$lang->release->changeStatus     = 'Change Status';
$lang->release->batchUnlink      = 'Batch Unlink';
$lang->release->batchUnlinkStory = 'Batch Unlink Stories';
$lang->release->batchUnlinkBug   = 'Batch Unlink Bugs';
$lang->release->manageSystem     = 'Manage ' . $lang->product->system;
$lang->release->addSystem        = 'Add ' . $lang->product->system;
$lang->release->consumed         = 'Cost';

$lang->release->confirmDelete      = 'Are you sure you want to delete this release?';
$lang->release->syncFromBuilds     = "Link completed {$lang->SRCommon} and fixed bugs from the build to the release.";
$lang->release->confirmUnlinkStory = 'Are you sure you want to unlink this story?';
$lang->release->confirmUnlinkBug   = 'Are you sure you want to unlink this bug?';
$lang->release->existBuild         = 'The 『build』 already contains the record 『%s』. You can change the 『release name』 or select a different 『build』.';
$lang->release->noRelease          = 'No releases yet.';
$lang->release->errorDate          = 'Release date should not be later than today.';
$lang->release->confirmActivate    = 'Are you sure you want to activate this release?';
$lang->release->confirmTerminate   = 'Are you sure you want to terminate this release?';
$lang->release->confirmPublish     = 'Are you sure you want to publish this release?';

$lang->release->basicInfo = 'Basic Info';

$lang->release->id             = 'ID';
$lang->release->product        = $lang->productCommon;
$lang->release->branch         = 'Platform / Branch';
$lang->release->project        = $lang->projectCommon;
$lang->release->build          = 'Build';
$lang->release->includedBuild  = 'Include Build';
$lang->release->includedSystem = 'Include ' . $lang->product->system;
$lang->release->releases       = $lang->release->includedSystem;
$lang->release->includedApp    = 'Included ' . $lang->product->system;
$lang->release->relatedProject = 'Related ' . $lang->projectCommon;
$lang->release->system         = $lang->product->system;
$lang->release->selectSystem   = 'Select ' . $lang->product->system;
$lang->release->name           = $lang->product->system . ' Version';
$lang->release->marker         = 'Milestone';
$lang->release->date           = 'Planned Release Date';
$lang->release->releasedDate   = 'Actual Release Date';
$lang->release->desc           = 'Description';
$lang->release->files          = 'Files';
$lang->release->status         = 'Release Status';
$lang->release->subStatus      = 'Substatus';
$lang->release->last           = 'Latest Version';
$lang->release->unlinkStory    = 'Unlink Story';
$lang->release->unlinkBug      = 'Unlink Bug';
$lang->release->stories        = 'Completed Story';
$lang->release->bugs           = 'Fixed Bug';
$lang->release->leftBugs       = 'Active Bug';
$lang->release->generatedBugs  = 'Active Bug';
$lang->release->createdBy      = 'Creator';
$lang->release->createdDate    = 'Created on';
$lang->release->finishStories  = '%s stories completed';
$lang->release->resolvedBugs   = '%s bugs fixed';
$lang->release->createdBugs    = '%s bugs remaining active';
$lang->release->export         = 'Export HTML';
$lang->release->yesterday      = 'Released Yesterday';
$lang->release->all            = 'All';
$lang->release->allProject     = 'All Projects';
$lang->release->notify         = 'Notify';
$lang->release->notifyUsers    = 'Notify Users';
$lang->release->mailto         = 'Mail to';
$lang->release->mailContent    = '<p>Dear user,</p><p style="margin-left:30px;">The following requirements and bugs you reported have been released in version %s. Please contact your account manager to check the latest version.</p>';
$lang->release->storyList      = '<p style="margin-left:30px;">Story List：%s。</p>';
$lang->release->bugList        = '<p style="margin-left:30px;">Bug List：%s。</p>';
$lang->release->pageAllSummary = ' <strong>%s</strong> releases onthis page: <strong>%s</strong> released, <strong>%s</strong> terminated.';
$lang->release->pageSummary    = " <strong>%s</strong> releases onthis page: ";
$lang->release->fileName       = 'File name';
$lang->release->exportRange    = 'Data to export';

$lang->release->storyTitle = 'Story Name';
$lang->release->bugTitle   = 'Bug Name';

$lang->release->filePath = 'Download: ';
$lang->release->scmPath  = 'SCM Path: ';

$lang->release->exportTypeList['all']     = 'All';
$lang->release->exportTypeList['story']   = $lang->release->stories;
$lang->release->exportTypeList['bug']     = $lang->release->bugs;
$lang->release->exportTypeList['leftbug'] = $lang->release->leftBugs;

$lang->release->resultList['normal'] = 'Released';
$lang->release->resultList['fail']   = 'Release Failed';

$lang->release->statusList['wait']      = 'Waiting';
$lang->release->statusList['normal']    = 'Released';
$lang->release->statusList['fail']      = 'Release Failed';
$lang->release->statusList['terminate'] = 'Terminate';

$lang->release->changeStatusList['wait']      = 'Publish';
$lang->release->changeStatusList['normal']    = 'Activate';
$lang->release->changeStatusList['terminate'] = 'Terminate';
$lang->release->changeStatusList['publish']   = 'Publish';
$lang->release->changeStatusList['active']    = 'Activate';
$lang->release->changeStatusList['pause']     = 'Terminate';

$lang->release->action = new stdclass();
$lang->release->action->changestatus = array('main' => '$date, $extra by  <strong>$actor</strong>.', 'extra' => 'changeStatusList');
$lang->release->action->notified     = array('main' => '$date, notification sended by <strong>$actor</strong> .');
$lang->release->action->published    = array('main' => '$date, published by <strong>$actor</strong> and the result is <strong>$extra</strong>.', 'extra' => 'resultList');

$lang->release->notifyList['FB'] = "Feedback Provider";
$lang->release->notifyList['PO'] = "{$lang->productCommon} Owner";
$lang->release->notifyList['QD'] = 'Test Manager';
$lang->release->notifyList['SC'] = 'Story Creator';
$lang->release->notifyList['ET'] = "{$lang->execution->common} Team Members";
$lang->release->notifyList['PT'] = "{$lang->projectCommon} Team Members";
$lang->release->notifyList['CT'] = "Mail to";

$lang->release->featureBar['browse']['all']       = $lang->release->all;
$lang->release->featureBar['browse']['wait']      = $lang->release->statusList['wait'];
$lang->release->featureBar['browse']['normal']    = $lang->release->statusList['normal'];
$lang->release->featureBar['browse']['fail']      = $lang->release->statusList['fail'];
$lang->release->featureBar['browse']['terminate'] = $lang->release->statusList['terminate'];

$lang->release->markerList[1] = 'Yes';
$lang->release->markerList[0] = 'No';

$lang->release->failTips        = 'Deployment/Release failed';
$lang->release->versionErrorTip = 'Version number can only contain letters, digits, hyphens (-), dots (.), and underscores (_).';
$lang->release->integratedLabel = 'Intergration';
