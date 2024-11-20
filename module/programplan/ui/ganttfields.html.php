<?php
declare(strict_types=1);
/**
 * The ganttData view file of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     programplan
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;

h::css("#browseTypeList .menu-item .item-content{height:30px;}");
h::css("#browseTypeList .menu-item.active .item-content{color: var(--menu-selected-color); font-weight: 700;}");
h::css("#browseTypeList .menu-item.active .item-content:hover{color: #fff;}");

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
foreach($lang->programplan->ganttBrowseType as $ganttBrowseType => $typeName)
{
    $link = $this->createLink('programplan', 'browse', "projectID=$projectID&productID=$productID&type=$ganttBrowseType");
    if($app->rawModule == 'review' and $app->rawMethod == 'assess') $this->createLink('review', 'assess', "reivewID=$reviewID&from=&type=$ganttBrowseType");

    $typeHtml .= '<li class="menu-item' . ($ganttType == $ganttBrowseType ? " active" : '') . '">' . html::a($link, $typeName, '', "class='item-content'") . '</li>';
}
$typeHtml .= '</menu>';

$ganttFields = array();
$ganttFields['column_text']         = $typeHtml;
$ganttFields['column_owner_id']     = $lang->programplan->PMAB;
$ganttFields['column_status']       = $lang->statusAB;
$ganttFields['column_percent']      = $lang->programplan->percentAB;
$ganttFields['column_taskProgress'] = $lang->programplan->taskProgress;
$ganttFields['column_begin']        = $lang->programplan->begin;
$ganttFields['column_start_date']   = $lang->programplan->begin;
$ganttFields['column_deadline']     = $lang->programplan->end;
$ganttFields['column_end_date']     = $lang->programplan->end;
$ganttFields['column_realBegan']    = $lang->programplan->realBegan;
$ganttFields['column_realEnd']      = $lang->programplan->realEnd;
$ganttFields['column_duration']     = $lang->programplan->duration;
$ganttFields['column_estimate']     = $lang->programplan->estimate;
$ganttFields['column_consumed']     = $lang->programplan->consumed;
$ganttFields['column_delay']        = $lang->programplan->delay;
$ganttFields['column_delayDays']    = $lang->programplan->delayDays;
