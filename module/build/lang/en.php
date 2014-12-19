<?php
/**
 * The build module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->build->common    = 'Build';
$lang->build->create    = "Create";
$lang->build->edit      = "Edit";
$lang->build->linkStory = "Link story";
$lang->build->linkBug   = "Link Bug";
$lang->build->delete    = "Delete";
$lang->build->deleted   = "Deleted";
$lang->build->view      = "Info";
$lang->build->ajaxGetProductBuilds = 'API:Product builds';
$lang->build->ajaxGetProjectBuilds = 'API:Project builds';
$lang->build->batchUnlinkStory     = 'Batch unlink story';
$lang->build->batchUnlinkBug       = 'Batch unlink bug';

$lang->build->confirmDelete      = "Are sure to delete this build?";
$lang->build->confirmUnlinkStory = "Are you sure to remove this story?";
$lang->build->confirmUnlinkBug   = "Are you sure to remove this bug?";

$lang->build->basicInfo = 'Basic Info';

$lang->build->id         = 'ID';
$lang->build->product    = 'Product';
$lang->build->project    = 'Project';
$lang->build->name       = 'Name';
$lang->build->date       = 'Build date';
$lang->build->builder    = 'Builder';
$lang->build->scmPath    = 'Source code path';
$lang->build->filePath   = 'Package file path';
$lang->build->desc       = 'Desc';
$lang->build->files      = 'Upload package';
$lang->build->last       = 'Last build';
$lang->build->linkStoriesAndBugs = 'stories and bugs';
$lang->build->linkStories        = 'Stories';
$lang->build->unlinkStory        = 'Remove story';
$lang->build->linkBugs           = 'Bugs';
$lang->build->unlinkBug          = 'Remove bug';
$lang->build->stories            = 'Resolved stories';
$lang->build->bugs               = 'Resolved bugs';
$lang->build->generatedBugs      = 'Generated bug';

$lang->build->finishStories = 'The total demand for a complete %s';
$lang->build->resolvedBugs  = 'The total solution of bug %s';
$lang->build->createdBugs   = 'The total generated of bug %s';

$lang->build->placeholder = new stdclass();
$lang->build->placeholder->scmPath  = 'Software source code repository, such as Subversion or Git';
$lang->build->placeholder->filePath = 'The path of this build package to download';

$lang->build->action = new stdclass();
$lang->build->action->buildopened = '$date, <strong>$actor</strong> created build. <strong>$objectID</strong>。' . "\n";
