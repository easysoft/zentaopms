<?php
/**
 * The build module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->build->common           = "Build";
$lang->build->create           = "Create Build";
$lang->build->edit             = "Edit Build";
$lang->build->linkStory        = "Link {$lang->SRCommon}";
$lang->build->linkBug          = "Link Bug";
$lang->build->delete           = "Delete Build";
$lang->build->deleted          = "Deleted";
$lang->build->view             = "Build Detail";
$lang->build->batchUnlink      = 'Batch Unlink';
$lang->build->batchUnlinkStory = "Batch Unlink {$lang->SRCommon}";
$lang->build->batchUnlinkBug   = 'Batch Unlink Bugs';
$lang->build->viewBug          = 'Bugs';
$lang->build->bugList          = 'Bug List';
$lang->build->linkArtifactRepo = 'Link Artifact Repo';

$lang->build->confirmDelete      = "Do you want to delete this build?";
$lang->build->confirmUnlinkStory = "Do you want to unlink this {$lang->SRCommon}?";
$lang->build->confirmUnlinkBug   = "Do you want to unlink this Bug?";

$lang->build->basicInfo = 'Basic Info';

$lang->build->id             = 'ID';
$lang->build->product        = $lang->productCommon;
$lang->build->project        = $lang->projectCommon;
$lang->build->branch         = 'Platform/Branch';
$lang->build->branchAll      = 'All associated %s';
$lang->build->branchName     = '%s';
$lang->build->execution      = $lang->executionCommon;
$lang->build->executionAB    = 'execution';
$lang->build->integrated     = 'Integrated';
$lang->build->singled        = 'Singled';
$lang->build->builds         = 'Included Builds';
$lang->build->released       = 'Released';
$lang->build->name           = 'Name';
$lang->build->nameAB         = 'Name';
$lang->build->date           = 'Date';
$lang->build->builder        = 'Builder';
$lang->build->url            = 'URL';
$lang->build->scmPath        = 'SCM Path';
$lang->build->filePath       = 'File Path';
$lang->build->desc           = 'Description';
$lang->build->mailto         = 'Mailto';
$lang->build->files          = 'Files';
$lang->build->last           = 'Last Build';
$lang->build->packageType    = 'Package Type';
$lang->build->unlinkStory    = "Unlink {$lang->SRCommon}";
$lang->build->unlinkBug      = 'Unlink Bug';
$lang->build->stories        = "Finished {$lang->SRCommon}";
$lang->build->bugs           = 'Resolved Bugs';
$lang->build->generatedBugs  = 'Reported Bugs';
$lang->build->noProduct      = " <span id='noProduct' style='color:red'>This {$lang->executionCommon} is not linked to {$lang->productCommon}, so the Build cannot be created. Please first <a href='%s' data-app='%s' data-toggle='modal' data-type='iframe'> link {$lang->productCommon}</a></span>";
$lang->build->noBuild        = 'No builds yet.';
$lang->build->emptyExecution = $lang->executionCommon . 'should be not empty.';
$lang->build->linkedBuild    = 'Linked Build';
$lang->build->createTest     = 'Submit Request';

$lang->build->notice = new stdclass();
$lang->build->notice->changeProduct   = "The {$lang->SRCommon}, bug, or the version of the submitted test order has been linked, and its {$lang->productCommon} cannot be modified";
$lang->build->notice->changeExecution = "The version of the submitted test order cannot be modified {$lang->executionCommon}";
$lang->build->notice->changeBuilds    = "The version of the submitted test order cannot be modified builds";
$lang->build->notice->autoRelation    = "The completed requirements, resolved bugs, and generated bugs under the relevant version will be automatically associated with the project version";
$lang->build->notice->createTest      = "The execution of this version has been deleted, and the test cannot be submitted";

$lang->build->confirmChangeBuild = "After branch『%s』disassociation,under the %s have %s {$lang->SRCommon} and %s Bug will remove synchronization from version, whether to cancel？";
$lang->build->confirmRemoveStory = "After branch『%s』disassociation,under the %s have %s {$lang->SRCommon} will remove synchronization from version, whether to cancel？";
$lang->build->confirmRemoveBug   = "After branch『%s』disassociation,under the %s have %s Bug will remove synchronization from version, whether to cancel？";
$lang->build->confirmRemoveTips  = "Are you sure to delete %s『%s』?";

$lang->build->finishStories = "  Finished {$lang->SRCommon} %s";
$lang->build->resolvedBugs  = '  Resolved Bug %s';
$lang->build->createdBugs   = '  Reported Bug %s';

$lang->build->placeholder = new stdclass();
$lang->build->placeholder->scmPath        = ' Source code repository, e.g. Subversion/Git Library path';
$lang->build->placeholder->filePath       = ' Download path for this Build.';
$lang->build->placeholder->multipleSelect = "Support select multiple builds";

$lang->build->action = new stdclass();
$lang->build->action->buildopened = '$date, created by <strong>$actor</strong>, Build <strong>$extra</strong>.' . "\n";

$lang->backhome = 'back';

$lang->build->isIntegrated = array();
$lang->build->isIntegrated['no']  = 'No';
$lang->build->isIntegrated['yes'] = 'Yes';
