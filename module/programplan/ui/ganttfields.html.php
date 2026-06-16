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
$ganttLang->exporting           = $lang->programplan->exporting;
$ganttLang->exportFail          = $lang->programplan->exportFail;
$ganttLang->zooming             = $lang->execution->gantt->zooming;
$ganttLang->hideCriticalPath    = $lang->programplan->hideCriticalPath;
$ganttLang->showCriticalPath    = $lang->programplan->showCriticalPath;
$ganttLang->fullScreen          = $lang->execution->gantt->fullScreen;
$ganttLang->taskStatusList      = $lang->task->statusList;
$ganttLang->ganttSetting        = $lang->execution->ganttSetting;
$ganttLang->errorTaskDrag       = $lang->programplan->error->taskDrag;
$ganttLang->errorPlanDrag       = $lang->programplan->error->planDrag;
$ganttLang->edit                = $lang->programplan->edit;
$ganttLang->submit              = $lang->programplan->submit;
$ganttLang->today               = $lang->programplan->today;
$ganttLang->scrollToToday       = $lang->execution->gantt->scrollToToday;
$ganttLang->deleteRelation      = $lang->execution->gantt->confirmDelete;
$ganttLang->wrongRelation       = $lang->execution->error->wrongGanttRelation;
$ganttLang->wrongRelationSource = $lang->execution->error->wrongGanttRelationSource;
$ganttLang->wrongRelationTarget = $lang->execution->error->wrongGanttRelationTarget;
$ganttLang->wrongKanbanTasks    = $lang->execution->error->wrongKanbanTasks;
$ganttLang->warningNoToday      = $lang->execution->gantt->warning->noTodayMarker;
$ganttLang->deadline            = $lang->programplan->end;

if($from == 'doc')
{
    $typeHtml = $lang->programplan->ganttBrowseType['gantt'];
}
else
{
    $typeHtml  = '<span class="toggle-all-icon"><i class="icon-expand-alt"></i></span><a data-toggle="dropdown" href="#browseTypeList"><span class="text">' . $lang->programplan->ganttBrowseType[$ganttType] . '</span><span class="caret"></span></a>';
    $typeHtml .= '<menu class="dropdown-menu menu" id="browseTypeList">';
    foreach($lang->programplan->ganttBrowseType as $ganttBrowseType => $typeName)
    {
        $link = $this->createLink('programplan', 'browse', "projectID=$projectID&productID=$productID&type=$ganttBrowseType");
        if($app->rawModule == 'review' and $app->rawMethod == 'assess') $this->createLink('review', 'assess', "reivewID=$reviewID&from=&type=$ganttBrowseType");

        $typeHtml .= '<li class="menu-item' . ($ganttType == $ganttBrowseType ? " active" : '') . '">' . html::a($link, $typeName, '', "class='item-content'") . '</li>';
    }
    $typeHtml .= '</menu>';
}

$notSort = array('delay', 'delayDays');
$ganttFields = [];
$ganttFields['column_text']       = $typeHtml;
$ganttFields['column_percent']    = $lang->programplan->ganttCustom['progress'];
$ganttFields['column_start_date'] = array('text' => $lang->programplan->ganttCustom['begin']);
$ganttFields['column_end_date']   = array('text' => $lang->programplan->ganttCustom['deadline']);
foreach($lang->programplan->ganttCustom as $field => $name)
{
    $ganttField = "column_{$field}";
    if(isset($ganttFields[$ganttField])) continue;
    $ganttFields[$ganttField] = in_array($field, $notSort) ? $name : array('text' => $name);
}

list($orderField, $orderDirect) = $this->loadModel('execution')->parseOrderBy($orderBy);
foreach($ganttFields as $colName => $value)
{
    $field = str_replace('column_', '', $colName);
    if(is_null($value) || is_array($value))
    {
        list($fieldOrderBy, $fieldClass) = $this->execution->buildKanbanOrderBy($field, $orderField, $orderDirect);
        $text  = (is_array($value) && !empty($value['text'])) ? $value['text'] : $lang->execution->ganttCustom[$field];
        $value = \html::a(createLink('programplan', 'browse', "projectID={$projectID}&productID={$productID}&type={$type}&orderBy={$fieldOrderBy}&baselineID=&browseType={$browseType}&queryID={$queryID}&from=$from&blockID=$blockID&versionID=$versionID"), $text, '', "class='$fieldClass'");
        if($versionID != 0) $value = $text;
    }

    $ganttFields[$colName] = $value;
}
if(empty($config->setPercent))
{
    $showFields = str_replace(',progress,', ',', $showFields);
    unset($ganttFields['column_percent']);
}
