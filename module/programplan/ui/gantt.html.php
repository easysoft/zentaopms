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

if($app->rawModule == 'programplan')
{
    if($project->stageBy == 'product')
    {
        $viewName = $productID != 0 ? zget($productList, $productID) : $lang->product->allProduct;
        $items    = array();
        foreach($productList as $key => $productName) $items[] = array('text' => $productName, 'url' => $this->createLink('programplan', 'browse', "projectID=$projectID&productID=$key&type=gantt"), 'active' => $productID == $key);
        featureBar(
            dropdown
            (
                btn(set::type('link'), setClass('no-underline'), $viewName),
                set::items($items)
            )
        );
    }
    else
    {
        featureBar(span(setClass('text font-bold'), $lang->programplan->gantt));
    }
    toolbar
    (
        div
        (
            btn(setClass('primary switchBtn'), set::title($lang->programplan->gantt), icon('gantt-alt')),
            btn(setClass('switchBtn'), set::title($lang->project->bylist), set::url($this->createLink('project', 'execution', "status=all&projectID=$projectID")), icon('list'))
        ),
        btn(setClass('no-underline'), set::type('link'), setID('fullScreenBtn'), icon('fullscreen'), $lang->programplan->full),
        dropdown
        (
            btn(set::type('link'), setClass('no-underline'), $lang->export),
            set::items(array
            (
                array('text' => $lang->execution->gantt->exportImg, 'url' => 'javascript:exportGantt()'),
                array('text' => $lang->execution->gantt->exportPDF, 'url' => 'javascript:exportGantt("pdf")')
            ))
        ),
        btn(set::url($this->createLink('programplan', 'ajaxcustom')), icon('cog-outline'), $lang->settings, setClass('no-underline'), set::type('link'), set('data-toggle', 'modal'), set('data-size', 'sm')),
        (common::hasPriv('programplan', 'create') && empty($product->deleted)) ? btn(set::url($this->createLink('programplan', 'create', "projectID=$projectID")), icon('plus'), $lang->programplan->create, setClass('primary')) : null
    );
}
