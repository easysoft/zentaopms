<?php
/**
 * The build module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->build->common           = "Build";
$lang->build->browse           = "Build List";
$lang->build->create           = "Create Build";
$lang->build->edit             = "Edit Build";
$lang->build->linkStory        = "Link {$lang->SRCommon}";
$lang->build->linkBug          = "Link Bug";
$lang->build->delete           = "Delete Build";
$lang->build->deleted          = "Deleted";
$lang->build->view             = "Build Details";
$lang->build->batchUnlink      = 'Batch Unlink';
$lang->build->batchUnlinkStory = "Batch Unlink {$lang->SRCommon}";
$lang->build->batchUnlinkBug   = 'Batch Unlink Bugs';
$lang->build->viewBug          = 'Bugs';
$lang->build->bugList          = 'Bug List';
$lang->build->system           = $lang->product->system;
$lang->build->addSystem        = 'Add ' . $lang->product->system;
$lang->build->consumed         = 'Cost';

$lang->build->confirmDelete      = "Are you sure you want to delete the build?";
$lang->build->confirmUnlinkStory = "Are you sure you want to unlink the {$lang->SRCommon}?";
$lang->build->confirmUnlinkBug   = "Are you sure you want to unlink the bug?";

$lang->build->basicInfo = 'Basic Info';

$lang->build->id             = 'ID';
$lang->build->product        = $lang->productCommon;
$lang->build->project        = $lang->projectCommon;
$lang->build->branch         = 'Platform/Branch';
$lang->build->branchAll      = 'All Linked %s';
$lang->build->branchName     = '%s';
$lang->build->execution      = $lang->executionCommon;
$lang->build->executionAB    = 'execution';
$lang->build->integrated     = 'Integrated Build';
$lang->build->singled        = 'Single Build';
$lang->build->builds         = 'Inclusive Build';
$lang->build->released       = 'Release';
$lang->build->name           = 'Name';
$lang->build->nameAB         = 'Name';
$lang->build->date           = 'Build Date';
$lang->build->builder        = 'Build Author';
$lang->build->url            = 'URL';
$lang->build->scmPath        = 'SCM Path';
$lang->build->filePath       = 'Download Address';
$lang->build->desc           = 'Description';
$lang->build->mailto         = 'Mail to';
$lang->build->files          = 'Upload Files';
$lang->build->last           = 'Last Build';
$lang->build->createdBy      = 'Creator';
$lang->build->createdDate    = 'Created on';
$lang->build->packageType    = 'Type';
$lang->build->unlinkStory    = "Unlink {$lang->SRCommon}";
$lang->build->unlinkBug      = 'Unlink Bug';
$lang->build->stories        = "Completed {$lang->SRCommon}";
$lang->build->bugs           = 'Resolved Bugs';
$lang->build->generatedBugs  = 'Reported Bugs';
$lang->build->noProduct      = " <span id='noProduct' style='color:red'> Build cannot be created since the {$lang->executionCommon} has no linked {$lang->productCommon}. Please <a data-url='%s' data-app='%s' data-toggle='modal' class='cursor-pointer'> link a {$lang->productCommon} first. </a></span>";
$lang->build->noBuild        = 'No builds yet.';
$lang->build->emptyExecution = $lang->executionCommon . 'should be not empty.';
$lang->build->linkedBuild    = 'Link Build';
$lang->build->createTest     = 'Submit Test Request';

$lang->build->integratedLabel = 'Integration';

$lang->build->notice = new stdclass();
$lang->build->notice->changeProduct   = "A build that has already been linked to a {$lang->SRCommon}, a bug, or a submitted test request cannot have its associated {$lang->productCommon} changed.";
$lang->build->notice->changeExecution = "A build with a submitted test request cannot have its associated {$lang->executionCommon} changed.";
$lang->build->notice->changeBuilds    = "A build with a submitted test request cannot have its linked builds changed.";
$lang->build->notice->autoRelation    = "The completed {$lang->SRCommon}s, resolved bugs, and newly generated bugs under the related builds will be automatically linked to the {$lang->projectCommon} build.";
$lang->build->notice->createTest      = "The execution linked to this build has been deleted. Test requests cannot be submitted.";

$lang->build->confirmChangeBuild = "After unlinking %s [%s], %s {$lang->SRCommon}(s) and %s bug(s) under %s will be removed from the build. Do you want to continue?";
$lang->build->confirmRemoveStory = "After unlinking %s [%s], %s {$lang->SRCommon}(s) under %s will be removed from the plan. Do you want to continue?";
$lang->build->confirmRemoveBug   = "After unlinking %s [%s], %s bug(s) under %s will be removed from the plan. Do you want to continue?";
$lang->build->confirmRemoveTips  = "Are you sure you want to delete %s『%s』?";

$lang->build->finishStories = "%s {$lang->SRCommon} completed this time.";
$lang->build->resolvedBugs  = '%s Bugs resolved this time.';
$lang->build->createdBugs   = '%s Bugs reported this time.';

$lang->build->placeholder = new stdclass();
$lang->build->placeholder->scmPath        = 'The software source code repository, such as a Subversion or Git repository address.';
$lang->build->placeholder->filePath       = 'The storage location for downloading the software package of this build.';
$lang->build->placeholder->multipleSelect = "Multiple build selections are supported.";

$lang->build->action = new stdclass();
$lang->build->action->buildopened = '$date, the build <strong>$extra</strong> was created by <strong>$actor</strong>. ' . "\n";

$lang->backhome = 'back';

$lang->build->isIntegrated = array();
$lang->build->isIntegrated['no']  = 'No';
$lang->build->isIntegrated['yes'] = 'Yes';
