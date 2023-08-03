<?php
declare(strict_types=1);
/**
 * The view view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

$progress = ($execution->totalConsumed + $execution->totalLeft) ? floor($execution->totalConsumed / ($execution->totalConsumed + $execution->totalLeft) * 1000) / 1000 * 100 : 0;
$isKanban = isset($execution->type) && $execution->type == 'kanban';
$chartURL = createLink('execution', 'ajaxGetBurn', "executionID={$execution->id}");

$programDom = null;
if($config->systemMode == 'ALM' && $execution->projectInfo->grade > 1)
{
    foreach($programList as $programID => $name)
    {
        if(common::hasPriv('program', 'product'))
        {
            $programList[$programID] = html::a
            (
                $this->createLink('program', 'product', "programID={$programID}"),
                $name
            );
        }
        else
        {
            $programList[$programID] = span($name);
        }
    }

    $programDom = div
    (
        icon('program mr-2'),
        html(implode('/ ', $programList))
    );
}

div
(
    setClass('flex clip'),
    div
    (
        setClass('flex-auto canvas flex p-4 w-2/3'),
        div
        (
            setClass('text-center w-1/3 flex flex-col justify-center items-center'),
            div
            (
                set('class', 'chart pie-chart'),
                echarts
                (
                    set::color(array('#2B80FF', '#E3E4E9')),
                    set::series
                    (
                        array
                        (
                            array
                            (
                                'type'      => 'pie',
                                'radius'    => array('80%', '90%'),
                                'itemStyle' => array('borderRadius' => '40'),
                                'label'     => array('show' => false),
                                'data'      => array($progress, 100 - $progress)
                            )
                        )
                    )
                )->size(120, 120),
                div
                (
                    set::class('pie-chart-title text-center'),
                    div(span(set::class('text-2xl font-bold'), $progress . '%')),
                    div
                    (
                        span
                        (
                            setClass('text-sm text-gray'),
                            $lang->execution->progress . '%',
                            icon
                            (
                                'help',
                                toggle::tooltip(array('title' => $lang->execution->lblStats)),
                                setClass('text-light')
                            )
                        )
                    )
                )
            ),
            div
            (
                setClass('border w-3/4 flex justify-center items-center pl-4 py-2'),
                $features['story'] ? div
                (
                    setClass('w-1/3'),
                    div
                    (
                        setClass('article-h3'),
                        $statData->storyCount
                    ),
                    $lang->story->common
                ) : null,
                div
                (
                    setClass('w-1/3'),
                    div
                    (
                        setClass('article-h3'),
                        $statData->taskCount
                    ),
                    $lang->task->common
                ),
                $features['qa'] ? div
                (
                    setClass('w-1/3'),
                    div
                    (
                        setClass('article-h3'),
                        $statData->bugCount
                    ),
                    $lang->bug->common
                ) : null,
            )
        ),
        div
        (
            setClass('flex-none w-2/3'),
            div
            (
                setClass('flex'),
                label
                (
                    setClass('rounded-full'),
                    $execution->id
                ),
                span
                (
                    setClass('article-h2 ml-2'),
                    $execution->name
                ),
                !empty($config->setCode) ? label
                (
                    setClass('dark-outline text-dark mx-2 mr-2'),
                    $execution->code
                ) : null,
                $execution->deleted ? label
                (
                    setClass('danger-outline text-danger'),
                    $lang->execution->deleted
                ) : null,
                isset($execution->delay) ? label
                (
                    setClass('danger-pale ring-danger ml-2'),
                    $lang->execution->delayed
                ) : label
                (
                    setClass("success-pale ring-success ml-2"),
                    $this->processStatus('execution', $execution)
                ),
            ),
            div
            (
                setClass('flex mt-4'),
                $config->systemMode == 'ALM' ? div
                (
                    setClass('clip w-1/2'),
                    $programDom
                ) : null,
                div
                (
                    setClass('clip w-1/2'),
                    icon('project mr-1'),
                    common::hasPriv('project', 'index') ? a
                    (
                        setClass('clip'),
                        set::href($this->createLink('project', 'index', "projectID={$execution->project}")),
                        $execution->projectInfo->name,
                    ) : span
                    (
                        setClass('clip w-full'),
                        $execution->projectInfo->name
                    ),
                ),
            ),
        ),
        div
        (
            set::class('detail-content mt-4'),
            html($execution->desc),
        ),
    ),
    panel
    (
        setClass('flex-none w-1/3 canvas ml-4'),
        $isKanban ? to::heading
        (
            div
            (
                set('class', 'panel-title'),
                $execution->name . ($isKanban ? $lang->execution->CFD : $lang->execution->burn),
            )
        ) : null,
        $isKanban ? to::headingActions
        (
            common::hasPriv('execution', $isKanban ? 'cfd' : 'burn') ? btn
            (
                setClass('ghost text-gray'),
                set::url(createLink('execution', $isKanban ? 'cfd' : 'burn', "executionID={$execution->id}")),
                $lang->more
            ) : null
        ) : null,
        div
        (
            setID('chartLine'),
            h::js("$('#chartLine').load('{$chartURL}')")
        )
    )
);

/* Construct suitable actions for the current execution. */
$execution->rawID = $execution->id;
$operateMenus = array();
foreach($config->execution->view->operateList['main'] as $operate)
{
    if(!common::hasPriv('execution', $operate)) continue;
    if(!$this->execution->isClickable($execution, $operate)) continue;

    $operateMenus[] = $config->execution->actionList[$operate];
}

/* Construct common actions for execution. */
$commonActions = array();
foreach($config->execution->view->operateList['common'] as $operate)
{
    if(!common::hasPriv('execution', $operate)) continue;

    $settings = $config->execution->actionList[$operate];
    $settings['text'] = '';
    if($operate == 'edit') unset($settings['data-toggle']);

    $commonActions[] = $settings;
}

div
(
    setClass('w-2/3 text-center fixed actions-menu'),
    floatToolbar
    (
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), $lang->goback)),
        set::main($operateMenus),
        set::suffix($commonActions),
        set::object($execution)
    )
);
