<?php
declare(strict_types=1);
/**
 * The gantt view file of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     programplan
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;

data('fileName', 'gantt-export-' . $projectID);

$ganttLang = new stdclass();
$ganttLang->exporting        = $lang->programplan->exporting;
$ganttLang->exportFail       = $lang->programplan->exportFail;
$ganttLang->zooming          = $lang->execution->gantt->zooming;
$ganttLang->hideCriticalPath = $lang->programplan->hideCriticalPath;
$ganttLang->showCriticalPath = $lang->programplan->showCriticalPath;
$ganttLang->taskStatusList   = $lang->task->statusList;
$ganttLang->errorTaskDrag    = $lang->programplan->error->taskDrag;
$ganttLang->errorPlanDrag    = $lang->programplan->error->planDrag;
$ganttLang->edit             = $lang->programplan->edit;
$ganttLang->submit           = $lang->programplan->submit;
$ganttLang->today            = $lang->programplan->today;
