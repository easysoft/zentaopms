<?php
declare(strict_types=1);
/**
* The project block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Wangyuting <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$blockNavID = 'nav-' . uniqid();
$selected   = key($projects);
$navTabs    = array();
foreach($projects as $project)
{
    $navTabs[] = li
    (
        set('class', 'nav-item' . ($project->id == $selected ? ' active' : '')),
        a
        (
            set('data-toggle', 'tab'),
            set('href', "#tab3{$blockNavID}Content{$project->id}"),
            $project->name
        )
    );
}

$tabItems = array();
foreach($projects as $project)
{
    $tabItems[] = div
    (
        set('class', 'tab-pane' . ($project->id == $selected ? ' active' : '')),
        set('id', "tab3{$blockNavID}Content{$project->id}"),
        in_array($project->model, array('scrum', 'kanban', 'agileplus')) ? div
        (
            div
            (
                set('class', 'flex justify-around'),
                div
                (
                    set('class', 'text-center'),
                    h4($lang->block->storyCount),
                    div
                    (
                        div
                        (
                            set('class', 'statistic-title'),
                            $lang->block->allStories . ":",
                        ),
                        div
                        (
                            set('class', 'statistic-data'),
                            $project->allStories,
                        )
                    ),
                    div
                    (
                        div
                        (
                            set('class', 'statistic-title'),
                            $lang->block->finish . ":",
                        ),
                        div
                        (
                            set('class', 'statistic-data'),
                            $project->doneStories,
                        )
                    ),
                    div
                    (
                        div
                        (
                            set('class', 'statistic-title'),
                            $lang->project->surplus . ":",
                        ),
                        div
                        (
                            set('class', 'statistic-data'),
                            $project->leftStories,
                        )
                    )
                ),
                div
                (
                    set('class', 'text-center'),
                    h4($lang->block->investment),
                    div
                    (
                        div
                        (
                            set('class', 'statistic-title'),
                            $lang->block->totalPeople . ":",
                        ),
                        div
                        (
                            set('class', 'statistic-data'),
                            $project->teamCount,
                        )
                    ),
                    div
                    (
                        div
                        (
                            set('class', 'statistic-title'),
                            $lang->block->estimate . ":",
                        ),
                        div
                        (
                            set('class', 'statistic-data'),
                            $project->estimate . $lang->execution->workHourUnit,
                        )
                    ),
                    div
                    (
                        div
                        (
                            set('class', 'statistic-title'),
                            $lang->block->consumedHours . ":",
                        ),
                        div
                        (
                            set('class', 'statistic-data'),
                            $project->consumed . $lang->execution->workHourUnit,
                        )
                    )
                ),
                div
                (
                    set('class', 'text-center'),
                    h4($lang->block->taskCount),
                    div
                    (
                        div
                        (
                            set('class', 'statistic-title'),
                            $lang->block->wait . ":",
                        ),
                        div
                        (
                            set('class', 'statistic-data'),
                            $project->waitTasks,
                        )
                    ),
                    div
                    (
                        div
                        (
                            set('class', 'statistic-title'),
                            $lang->block->doing . ":",
                        ),
                        div
                        (
                            set('class', 'statistic-data'),
                            $project->doingTasks,
                        )
                    ),
                    div
                    (
                        div
                        (
                            set('class', 'statistic-title'),
                            $lang->block->done . ":",
                        ),
                        div
                        (
                            set('class', 'statistic-data'),
                            $project->rndDoneTasks,
                        )
                    )
                ),
                div
                (
                    set('class', 'text-center'),
                    h4($lang->block->bugCount),
                    div
                    (
                        div
                        (
                            set('class', 'col col-title'),
                            $lang->block->totalBug . ":",
                        ),
                        div
                        (
                            set('class', 'col col-data'),
                            $project->allBugs,
                        )
                    ),
                    div
                    (
                        div
                        (
                            set('class', 'col col-title'),
                            $lang->bug->statusList['resolved'] . ":",
                        ),
                        div
                        (
                            set('class', 'col col-data'),
                            $project->doneBugs,
                        )
                    ),
                    div
                    (
                        div
                        (
                            set('class', 'col col-title'),
                            $lang->bug->unResolved . ":",
                        ),
                        div
                        (
                            set('class', 'col col-data'),
                            $project->leftBugs,
                        )
                    )
                )
            ),
            (!empty($project->executions) and $project->multiple) ? div
            (
                set('class', 'flex'),
                div
                (
                    set('class', 'flex-1 text-right'), 
                    h4($lang->block->last)
                ),
                div
                (
                    set('class', 'flex-1 text-center'),
                    a
                    (
                        set('href', $this->createLink('execution', 'task', "executionID={$project->executions[0]->id}")),
                        set('title', $project->executions[0]->name),
                        $project->executions[0]->name,
                    )
                ),
                div
                (
                    set('class', 'flex-1'),
                    div
                    (
                        set('class', 'progress'),
                        span
                        (
                            set('class', 'mr-4'),
                            $project->executions[0]->hours->progress . '%'
                        ),
                        div
                        (
                            set('class', 'progress-bar'),
                            set('role', 'progressbar'),
                            setStyle(['width' => $project->executions[0]->hours->progress . '%']),
                        )
                    )
                )
            ) : null
        ) : div
        (
            set('class', 'weekly-row'),
            div
            (
                span
                (
                    set('class', 'weekly-title'),
                    $lang->project->weekly
                ),
                span
                (
                    set('class', 'weekly-stage'),
                    $project->current
                )
            ),
            div
            (
                set('class', 'flex'),
                div
                (
                    set('class', 'flex-1'),
                    div
                    (
                        set('class', 'progress'),
                        span
                        (
                            set('class', 'mr-4'),
                            $lang->project->progress . ' : ' . $project->progress . '%'
                        ),
                        div
                        (
                            set('class', 'progress-bar'),
                            set('role', 'progressbar'),
                            setStyle(['width' => $project->progress . '%']),
                        )
                    )
                ),
                div
                (
                    set('class', 'flex-1 text-center'),
                    $lang->project->teamCount . ' : ' . $project->teamCount
                ),
                div
                (
                    set('class', 'flex-1 text-left'),
                    $lang->project->budget . ' : ' . ($project->budget != 0 ? $project->budget : $this->lang->project->future)
                ),
                div(set('class', 'flex-1'))
            )
        )
    );
}

panel
(
    set('class', 'projectstatistic-block'),
    div
    (
        set('class', 'flex'),
        ul
        (
            set('class', 'nav nav-tabs nav-stacked'),
            $navTabs
        ),
        div
        (
            set('class', 'tab-content'),
            $tabItems
        )
    )
);

render();
