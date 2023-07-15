<?php
declare(strict_types=1);
/**
* The recentproject block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$cards = array();
foreach($projects as $projectID => $project)
{
    $viewLink = $this->createLink('project', 'index', "projectID=$project->id");

    if($project->model == 'waterfall')
    {
        $stages = array();
        foreach($project->executions as $stage)
        {
            if($stage->grade == 1) $stages[] = $stage;
        }

        $stageItems = array();
        foreach($stages as $stage)
        {
            $stageItems[] = div
            (
                set::class("project-stage-item is-{$stage->status}" . ($stage->status != 'wait' ? ' is-going' : '')),
                div($stage->name)
            );
        }
    }
    else
    {
        $execution = empty($project->executions) ? '' : end($project->executions);
    }

    $cards[] = cell
    (
        set('width', $longBlock ? '32%' : '100%'),
        set('class', 'border p-4 overflow-hidden  ' . ($longBlock ? '' : ' mb-2')),
        div
        (
            set('class', 'pb-2'),
            a
            (
                set('class', 'text-black'),
                set('href', $viewLink),
                $project->name
            )
        ),
        $project->model == 'waterfall' && empty($stages) ? label
        (
            zget($lang->project->statusList, $project->status),
            setClass('label-outline')
        ) : null,
        $project->model == 'waterfall' && !empty($stages) ? p
        (
            set::class('text-gray'),
            $lang->project->ongoingStage
        ) : null,
        $project->model == 'waterfall' && !empty($stages) ? div
        (
            set::class('project-stages-container'),
            div
            (
                set::class('project-stages-row'),
                $stageItems,
            )
        ) : null,
        ($project->model != 'waterfall' && $project->multiple && $execution) ?  div
        (
            set('class', 'card-body py-1.5'),
            div
            (
                set('class', 'py-1.5'),
                span
                (
                    set('class', 'text-gray'),
                    $execution->type == 'kanban' ? $lang->project->lastKanban : $lang->project->lastIteration . ' : '
                ),
                a(
                    set('href', createLink('execution', 'task', "executionID={$execution->id}")),
                    $execution->name
                ),
                label
                (
                    set('class', 'warning-pale circle ml-2'),
                    $lang->execution->statusList[$execution->status]
                )
            ),
            div
            (
                set('class', 'py-1.5'),
                span
                (
                    set('class', 'text-gray'),
                    $lang->block->projectMember . ' : ',
                ),
                sprintf($lang->block->totalMember, $project->teamCount)
            ),
            div
            (
                set('class', 'py-1.5'),
                span
                (
                    set('class', 'text-gray'),
                    $lang->project->end . ' : '
                ),
                $project->end
            )
        ) : div
        (
            set::class('card-body'),
        )
    );
}

blockPanel
(
    set::bodyClass('row flex-wrap cards justify-between'),
    $cards
);

setPageData('zinDebug', null);
render();
