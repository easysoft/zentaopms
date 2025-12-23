<?php
declare(strict_types=1);
/**
 * The batchedit view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

if(!empty($frozenStages))
{
    $frozenTip = sprintf($lang->execution->frozenTip, $frozenStages);
    pageJS("zui.Modal.alert({message: '{$frozenTip}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'});\n");
}

$setCode    = (isset($config->setCode) and $config->setCode == 1);
$showMethod = $app->tab == 'project' && isset($project) && ($project->model == 'agileplus' || $project->model == 'waterfallplus');

jsVar('weekend', $config->execution->weekend);
jsVar('stageList', $lang->stage->typeList);
jsVar('ipdTypeList', $lang->stage->ipdTypeList);
jsVar('confirmSync', $lang->execution->confirmSync);
jsVar('noticeChangeAttr', $lang->programplan->noticeChangeAttr);
jsVar('parents', $parents);

$items = array();
$items[] = array
(
    'name'    => 'id',
    'label'   => $lang->idAB,
    'control' => 'hidden',
    'hidden'  => true
);
$items[] = array
(
    'name'    => 'id',
    'label'   => $lang->idAB,
    'control' => 'index',
    'width'   => '38px'
);
if(isset($project) && $project->model == 'scrum' && $project->hasProduct) $items[] = array
(
    'label'    => $lang->execution->projectName,
    'control'  => 'picker',
    'name'     => "project",
    'items'    => $allProjects,
    'required' => true,
    'width'    => '136px'
);
$items[] = array
(
    'name'     => 'name',
    'label'    => $app->tab == 'execution' ? $lang->execution->execName : $lang->execution->name,
    'width'    => '240px',
    'required' => true
);
if($showMethod) $items[] = array
(
    'name'     => 'type',
    'label'    => $lang->execution->method,
    'control'  => array('control' => 'picker', 'required' => true),
    'items'    => $lang->execution->typeList,
    'disabled' => true,
    'width'    => '100px'
);
if($setCode) $items[] = array
(
    'name'     => 'code',
    'label'    => $app->tab == 'execution' ? $lang->execution->execCode : $lang->execution->code,
    'width'    => '136px',
    'required' => strpos(",{$config->execution->edit->requiredFields},", ',code,') !== false
);
$items[] = array
(
    'name'         => 'PM',
    'label'        => $app->tab == 'execution' ? $lang->execution->execPM : $lang->execution->PM,
    'control'      => 'picker',
    'ditto'        => true,
    'defaultDitto' => 'off',
    'items'        => $pmUsers,
    'hidden'       => strpos("{$showFields}", 'PM') === false,
    'width'        => '112px'
);
$items[] = array
(
    'name'         => 'PO',
    'label'        => $lang->execution->PO,
    'control'      => 'picker',
    'ditto'        => true,
    'defaultDitto' => 'off',
    'items'        => $poUsers,
    'hidden'       => strpos("{$showFields}", 'PO') === false,
    'width'        => '180px'
);
$items[] = array
(
    'name'         => 'QD',
    'label'        => $lang->execution->QD,
    'control'      => 'picker',
    'ditto'        => true,
    'defaultDitto' => 'off',
    'items'        => $qdUsers,
    'hidden'       => strpos("{$showFields}", 'QD') === false,
    'width'        => '180px'
);
$items[] = array
(
    'name'         => 'RD',
    'label'        => $lang->execution->RD,
    'control'      => 'picker',
    'ditto'        => true,
    'defaultDitto' => 'off',
    'items'        => $rdUsers,
    'hidden'       => strpos("{$showFields}", 'RD') === false,
    'width'        => '180px'
);
$items[] = array
(
    'name'     => 'lifetime',
    'label'    => $app->tab == 'execution' ? $lang->execution->execType : $lang->execution->type,
    'control'  => 'picker',
    'items'    => $lang->execution->lifeTimeList,
    'width'    => '120px',
    'tipIcon'  => 'help',
    'hidden'   => strpos("{$showFields}", 'lifetime') === false,
    'tip'      => $lang->execution->typeTip,
    'tipProps' => array
    (
        'id'              => 'tooltipHover',
        'data-toggle'     => 'tooltip',
        'data-placement'  => 'right',
        'data-type'       => 'white',
        'data-class-name' => 'text-gray border border-gray-300'
    )
);
$items[] = array
(
    'name'     => 'begin',
    'label'    => $lang->execution->begin,
    'control'  => 'date',
    'width'    => '120px',
    'required' => true
);
$items[] = array
(
    'name'     => 'end',
    'label'    => $lang->execution->end,
    'control'  => 'date',
    'width'    => '120px',
    'required' => true
);
$items[] = array
(
    'name'   => 'team',
    'label'  => $lang->execution->teamName,
    'width'  => '136px',
    'hidden' => strpos("{$showFields}", 'team') === false
);
$items[] = array
(
    'name'    => 'desc',
    'label'   => $app->tab == 'execution' ? $lang->execution->execDesc : $lang->execution->desc,
    'control' => 'textarea',
    'width'   => '160px',
    'hidden'  => strpos("{$showFields}", 'desc') === false
);
$items[] = array
(
    'name'  => 'days',
    'label' => $lang->execution->days,
    'control' => array
    (
        'control'     => 'inputControl',
        'suffix'      => $lang->execution->day,
        'suffixWidth' => 20
    ),
    'width' => '80px',
    'hidden' => strpos("{$showFields}", 'days') === false
);
formBatchPanel
(
    set::title($lang->execution->batchEditAction),
    set::mode('edit'),
    set::title($lang->execution->batchEdit),
    set::customFields(array('list' => $customFields, 'show' => explode(',', $showFields), 'key' => 'batchEditFields')),
    set::data(array_values($executions)),
    set::onRenderRow(jsRaw('renderRowData')),
    on::change('[data-name="project"]', 'changeProject'),
    on::change('[data-name="begin"]', "computeWorkDays($(e.target).attr('name'))"),
    on::change('[data-name="end"]', "computeWorkDays($(e.target).attr('name'))"),
    on::change('[data-name="attribute"]', 'changeAttribute(e.target)'),
    set::items($items)
);

render();
