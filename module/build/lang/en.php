<?php
/**
 * The build module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->build->common       = "Build";
$lang->build->create       = "Create";
$lang->build->edit         = "Edit";
$lang->build->linkStory    = "Link Story";
$lang->build->linked2build = "Link build";
$lang->build->linkBug      = "Link Bug";
$lang->build->delete       = "Delete Build";
$lang->build->deleted      = "Deleted";
$lang->build->view         = "Build Details";
$lang->build->batchUnlink          = 'Batch Unlink';
$lang->build->batchUnlinkStory     = 'Batch Story Unlink';
$lang->build->batchUnlinkBug       = 'Batch Bug Unlink';

$lang->build->confirmDelete      = "Do you want to delete this Build?";
$lang->build->confirmUnlinkStory = "Do you want to unlink this Story?";
$lang->build->confirmUnlinkBug   = "Do you want to unlink this Bug?";

$lang->build->basicInfo = 'Basic Info';

$lang->build->id        = 'ID';
$lang->build->product   = $lang->productCommon;
$lang->build->project   = $lang->projectCommon;
$lang->build->name      = 'Name';
$lang->build->date      = 'Date';
$lang->build->builder   = 'Builder';
$lang->build->scmPath   = 'SCM Path';
$lang->build->filePath  = 'File Path';
$lang->build->desc      = 'Description';
$lang->build->files     = 'Upload Files';
$lang->build->last      = 'Last Build';
$lang->build->unlinkStory        = 'Unlink Story';
$lang->build->unlinkBug          = 'Unlink Bug';
$lang->build->stories            = 'Finished Story';
$lang->build->bugs               = 'Solved Bug';
$lang->build->generatedBugs      = 'Remained Bug';
$lang->build->noProduct          = " <span style='color:red'>This {$lang->projectCommon} has not linked to {$lang->productCommon}, so Build cannot be created. Please first <a href='%s'> link {$lang->productCommon}</a></span>";

$lang->build->finishStories = '  %s Stories have been finished.';
$lang->build->resolvedBugs  = '  %s Bugs have been solved.';
$lang->build->createdBugs   = '  %s Bugs have been created.';

$lang->build->placeholder = new stdclass();
$lang->build->placeholder->scmPath  = ' Source code repository, e.g. Subversion/Git Library path';
$lang->build->placeholder->filePath = ' Path of this Build package for downloading.';

$lang->build->action = new stdclass();
$lang->build->action->buildopened = '$date, created by <strong>$actor</strong>, Build <strong>$extra</strong>.' . "\n";
