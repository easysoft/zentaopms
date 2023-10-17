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
    $viewLink  = createLink('project', 'index', "projectID=$project->id");
    $execution = empty($project->executions) ? '' : end($project->executions);

    $cards[] = cell
    (
        setClass('p-2', $longBlock ? 'w-1/3' : 'w-full h-40'),
        div
        (
            setClass('border rounded-sm h-full px-4 hover:shadow hover:border-primary cursor-pointer open-url group', $longBlock ? 'py-4' : 'py-2'),
            setData('url', $viewLink),
            div
            (
                setClass('mb-4'),
                a
                (
                    setClass('font-bold text-fore text-md group-hover:text-primary'),
                    set('href', $viewLink),
                    $project->name
                )
            ),
            div
            (
                ($project->multiple && $execution) ? array
                (
                    setClass('space-y-3'),
                    div
                    (
                        span
                        (
                            setClass('text-gray mr-1'),
                            $execution->type == 'kanban' ? $lang->project->lastKanban : $lang->project->lastIteration . ': '
                        ),
                        a
                        (
                            set('href', createLink('execution', 'task', "executionID={$execution->id}")),
                            $execution->name
                        ),
                        label
                        (
                            setClass('label warning-pale circle ml-2'),
                            $lang->execution->statusList[$execution->status]
                        )
                    ),
                    div
                    (
                        span
                        (
                            setClass('text-gray mr-1'),
                            $lang->block->projectMember . ': ',
                        ),
                        sprintf($lang->block->totalMember, $project->teamCount)
                    ),
                    div
                    (
                        span
                        (
                            setClass('text-gray mr-1'),
                            $lang->project->end . ': '
                        ),
                        $project->end
                    )
                ) : null
            )
        )
    );
}

blockPanel
(
    set::bodyClass('row flex-wrap justify-between p-1.5 pt-1'),
    set::headingClass('pb-0 border-b-0'),
    $cards
);
