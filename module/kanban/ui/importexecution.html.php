<?php
/**
 * The importexecution view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('kanbanID', $kanbanID);
jsVar('regionID', $regionID);
jsVar('groupID',  $groupID);
jsVar('columnID', $columnID);
jsVar('methodName', $this->app->rawMethod);

foreach($executions2Imported as $execution)
{
    $execution->totalEstimate = $execution->hours->totalEstimate;
    $execution->totalConsumed = $execution->hours->totalConsumed;
    $execution->totalLeft     = $execution->hours->totalLeft;
}

featureBar
(
    inputGroup
    (
        span(setClass('input-group-addon'), $lang->kanban->selectedProduct),
        picker(set::name('project'), set::items($projects), set::value($selectedProjectID), set::style(array('width' => '200px')), set('data-on', 'change'), set('data-call', 'changeProject'), set::required(true)),
        span(setClass('input-group-addon'), $lang->kanban->selectedLane),
        picker(set::name('lane'), set::items($lanePairs), set::style(array('width' => '200px')), set::required(true))
    )
);

$config->execution->dtable->fieldList['rawID']['name'] = 'id';
$config->execution->dtable->fieldList['nameCol']['name'] = 'name';
$config->execution->dtable->fieldList['nameCol']['type'] = 'title';
$config->execution->dtable->fieldList['PM']['type']   = 'user';
unset($config->execution->dtable->fieldList['nameCol']['link']);
unset($config->execution->dtable->fieldList['nameCol']['nestedToggle']);
unset($config->execution->dtable->fieldList['project']);
unset($config->execution->dtable->fieldList['openedDate']);
unset($config->execution->dtable->fieldList['begin']);
unset($config->execution->dtable->fieldList['realBegan']);
unset($config->execution->dtable->fieldList['realEnd']);
unset($config->execution->dtable->fieldList['progress']);
unset($config->execution->dtable->fieldList['burn']);
unset($config->execution->dtable->fieldList['actions']);

foreach($config->execution->dtable->fieldList as $id => $field) $config->execution->dtable->fieldList[$id]['sortType'] = false;

formBase
(
    setID('linkForm'),
    set::actions(''),
    setClass('mt-2'),
    dtable
    (
        set::fixedLeftWidth('0.33'),
        set::checkable(true),
        set::userMap($users),
        set::cols(array_values($config->execution->dtable->fieldList)),
        set::data(array_values($executions2Imported)),
        set::footToolbar(array('items' => array(array('text' => $lang->kanban->importAB, 'btnType' => 'primary', 'className' => 'size-sm batch-btn', 'data-url'  => inlink('importExecution', "kanbanID=$kanbanID&regionID=$regionID&groupID=$groupID&columnID=$columnID"))))),
        set::footPager(usePager())
    )
);
