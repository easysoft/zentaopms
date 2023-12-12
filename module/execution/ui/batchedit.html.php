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

$setCode    = (isset($config->setCode) and $config->setCode == 1);
$showMethod = $app->tab == 'project' && isset($project) && ($project->model == 'agileplus' || $project->model == 'waterfallplus');
$typeTip    = $this->app->tab == 'execution' ? $lang->execution->waterfallTip . lcfirst($lang->execution->typeTip) : $lang->execution->typeTip;

jsVar('weekend', $config->execution->weekend);
jsVar('stageList', $lang->stage->typeList);
jsVar('confirmSync', $lang->execution->confirmSync);

formBatchPanel
(
    set::mode('edit'),
    set::data(array_values($executions)),
    set::onRenderRow(jsRaw('renderRowData')),
    on::change('[data-name="project"]', 'changeProject'),
    on::change('[data-name="begin"]', "computeWorkDays($(e.target).attr('name'))"),
    on::change('[data-name="end"]', "computeWorkDays($(e.target).attr('name'))"),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('hidden'),
        set::hidden(true)
    ),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('index'),
        set::width('38px')
    ),
    isset($project) && $project->model == 'scrum' ? formBatchItem
    (
        set::label($lang->execution->projectName),
        set::control('picker'),
        set::name("project"),
        set::items($allProjects),
        set::required(true),
        set::width('136px')
    ) : null,
    formBatchItem
    (
        set::name('name'),
        set::label($lang->execution->name),
        set::width('240px'),
        set::required(true)
    ),
    $showMethod ? formBatchItem
    (
        set::name('type'),
        set::label($lang->execution->method),
        set::control('picker'),
        set::items($lang->execution->typeList),
        set::disabled(true),
        set::width('80px')
    ) : null,
    $setCode ? formBatchItem
    (
        set::name('code'),
        set::label($lang->execution->code),
        set::width('136px')
    ) : null,
    formBatchItem
    (
        set::name('PM'),
        set::label($lang->execution->PM),
        set::control('picker'),
        set::ditto(true),
        set::defaultDitto('off'),
        set::items($pmUsers),
        set::width('112px')
    ),
    formBatchItem
    (
        set::name('PO'),
        set::label($lang->execution->PO),
        set::control('select'),
        set::ditto(true),
        set::defaultDitto('off'),
        set::items($poUsers),
        set::width('80px'),
        set::hidden(true)
    ),
    formBatchItem
    (
        set::name('QD'),
        set::label($lang->execution->QD),
        set::control('select'),
        set::ditto(true),
        set::defaultDitto('off'),
        set::items($qdUsers),
        set::width('80px'),
        set::hidden(true)
    ),
    formBatchItem
    (
        set::name('RD'),
        set::label($lang->execution->RD),
        set::control('select'),
        set::ditto(true),
        set::defaultDitto('off'),
        set::items(array()),
        set::width('80px'),
        set::hidden(true)
    ),
    formBatchItem
    (
        set::name('lifetime'),
        set::label(
            $lang->execution->type
        ),
        set::control('picker'),
        set::items($lang->execution->lifeTimeList),
        set::width('64px'),
        set::tipIcon('help'),
        $showMethod ? set::tip($typeTip) : null,
        $showMethod ? set
        (
            'tipProps',
            array
            (
                'id'             => 'tooltipHover',
                'data-toggle'    => 'tooltip',
                'data-placement' => 'right'
            )
        ) : null
    ),
    formBatchItem
    (
        set::name('begin'),
        set::label($lang->execution->begin),
        set::control('date'),
        set::width('76px'),
        set::required(true)
    ),
    formBatchItem
    (
        set::name('end'),
        set::label($lang->execution->end),
        set::control('date'),
        set::width('76px'),
        set::required(true)
    ),
    formBatchItem
    (
        set::name('team'),
        set::label($lang->execution->teamName),
        set::control('text'),
        set::width('136px'),
        set::hidden(true),
    ),
    formBatchItem
    (
        set::name('desc'),
        set::label($lang->execution->desc),
        set::control('textarea'),
        set::width('160px'),
        set::hidden(true)
    ),
    formBatchItem
    (
        set::name('days'),
        set::label($lang->execution->days),
        set::control
        (
            array
            (
                'type'   => 'inputControl',
                'suffix' => $lang->execution->day,
                'suffixWidth' => 20
            )
        ),
        set::width('64px')
    )
);

render();
