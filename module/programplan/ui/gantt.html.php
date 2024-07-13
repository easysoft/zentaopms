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

$typeHtml  = '<a data-toggle="dropdown" href="#browseTypeList"><span class="text">' . $lang->programplan->ganttBrowseType[$ganttType] . '</span><span class="caret"></span></a>';
$typeHtml .= '<menu class="dropdown-menu menu" id="browseTypeList">';
foreach($lang->programplan->ganttBrowseType as $browseType => $typeName)
{
    $link = $this->createLink('programplan', 'browse', "projectID=$projectID&productID=$productID&type=$browseType");
    if($app->rawModule == 'review' and $app->rawMethod == 'assess') $this->createLink('review', 'assess', "reivewID=$reviewID&from=&type=$browseType");

    $typeHtml .= '<li class="menu-item' . ($ganttType == $browseType ? " active" : '') . '">' . html::a($link, $typeName) . '</li>';
}
$typeHtml .= '</menu>';
