<?php
declare(strict_types=1);
/**
* The project statistic block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Wangyuting <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

/**
 * 获取区块左侧的项目列表.
 * Get project tabs on the left side.
 *
 * @param  array    $projects
 * @param  string   $blockNavCode
 * @access public
 * @return array
 */
function getProjectTabs($projects, $blockNavCode): array
{
    $navTabs  = array();
    $selected = key($projects);
    foreach($projects as $project)
    {
        $navTabs[] = li
        (
            set('class', 'nav-item' . ($project->id == $selected ? ' active' : '')),
            a
            (
                set('class', 'ellipsis'),
                set('data-toggle', 'tab'),
                set('href', "#tab3{$blockNavCode}Content{$project->id}"),
                $project->name

            ),
            a
            (
                set('class', 'link flex-1 text-right hidden'),
                set('href', helper::createLink('project', 'index', "projectID=$project->id")),
                icon
                (
                    set('class', 'rotate-90 text-primary'),
                    'export'
                )
            )
        );
    }
    return $navTabs;
}

/**
 * 获取区块右侧显示的项目信息.
 * Get project statistical information.
 *
 * @param  object   $projects
 * @param  string   $blockNavID
 * @access public
 * @return array
 */
function getProjectInfo($projects, $blockNavID): array
{
    $selected = key($projects);
    $tabItems = array();
    foreach($projects as $project)
    {
        $tabItems[] = div
        (
            set('class', 'tab-pane' . ($project->id == $selected ? ' active' : '')),
            set('id', "tab3{$blockNavID}Content{$project->id}"),
            in_array($project->model, array('scrum', 'kanban', 'agileplus')) ? getScrumProjectInfo($project) : getWaterfallProjectInfo($project)
        );
    }
    return $tabItems;
}

/**
 * 获取敏捷类项目的统计信息.
 * Get scrum project info.
 *
 * @param  object    $project
 * @access public
 * @return void
 */
function getScrumProjectInfo($project)
{
    global $lang;

    return div
    (
        /* 区块右侧顶部的项目概况。 */
        div
        (
            set('class', 'flex bg-white h-10 leading-9 px-4 shadow-sm'),
            cell
            (
                set('class', 'text-left mr-6'),
                span
                (
                    set('class', 'text-gray'),
                    '距离项目结束还剩',
                    span
                    (
                        set('class', 'font-bold text-black px-1'),
                        zget($project, 'remainingDays' , 0)
                    ),
                    $lang->block->projectstatistic->day
                )
            ),
            cell
            (
                set('class', 'flex-1 text-left'),
                span
                (
                    set('class', 'text-gray mr-5'),
                    '存在风险 : ',
                    span
                    (
                        set('class', 'font-bold text-warning'),
                        '3'
                    )
                ),
                span
                (
                    set('class', 'text-gray'),
                    '存在问题 : ',
                    span
                    (
                        set('class', 'font-bold text-warning'),
                        '1'
                    )
                )
            ),
            (!empty($project->executions) and $project->multiple) ? cell
            (
                /* 项目最近的一次执行。 */
                set('class', 'flex-1 text-right'),
                span
                (
                    set('class', 'text-gray'),
                    '最近执行 ',
                    a
                    (
                        set('href', helper::createLink('execution', 'task', "executionID={$project->executions[0]->id}")),
                        set('title', $project->executions[0]->name),
                        $project->executions[0]->name,
                    )
                )
            ) : null
        ),
        div
        (
            /* 区块右侧主体显示的项目统计项。 */
            set('class', 'flex'),
            getProjectStatisticItems($project)
        )
    );
}

/**
 * 获取项目的统计项.
 * get project statistic items. 
 *
 * @param  object    $project
 * @access public
 * @return array
 */
function getProjectStatisticItems($project): array
{
    global $config, $lang;

    $cells = array();
    foreach($config->block->projectstatistic->items as $module => $items)
    {
        $cellItems = array();
        foreach($items as $item)
        {
            $field = $item['field'];
            $unit  = $item['unit'];
            $cellItems[] = div
            (
                set('class', 'flex py-4'),
                cell
                (
                    set('width', '50%'),
                    set('class', 'text-right text-gray'),
                    span($lang->block->projectstatistic->{$field} . ' ：')
                ),
                cell
                (
                    set('width', '50%'),
                    set('class', 'text-left'),
                    span
                    (
                        set('class', 'font-bold text-black'),
                        zget($project, $field, 0)
                    ),
                    span($lang->block->projectstatistic->{$unit})
                )
            );
        }
        $cells[] = cell
        (
            set('class', 'flex-1 px-2 py-4'),
            div
            (
                set('class', 'px-2'),
                span
                (
                    set('class', 'font-bold'),
                    $lang->block->projectstatistic->{$module}
                ),
            ),
            $cellItems
        );
    }
    return $cells;
}

/**
 * 获取瀑布类项目的统计信息.
 * get waterfall project info.
 *
 * @param  object    $project
 * @access public
 * @return void
 */
function getWaterfallProjectInfo($project)
{
    global $app, $lang;
    $isChineseLang = in_array($app->getClientLang(), array('zh-cn','zh-tw'));
    return div
    (
        /* 瀑布项目展示概况。 */
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
                $lang->project->budget . ' : ' . ($project->budget != 0 ? $project->budget : $lang->project->future)
            ),
            div(set('class', 'flex-1'))
        ),
        div
        (
            set('class', 'flex'),
            div
            (
                set('class', 'flex-1'),
                $isChineseLang ? $lang->project->pv . '(' . $lang->project->pvTitle . ')' : $lang->project->pv
            ),
            div
            (
                set('class', 'flex-1'),
                $isChineseLang ? $lang->project->ev . '(' . $lang->project->evTitle . ')' : $lang->project->ev
            ),
            div
            (
                set('class', 'flex-1'),
                $isChineseLang ? $lang->project->ac . '(' . $lang->project->acTitle . ')' : $lang->project->ac
            ),
            div
            (
                set('class', 'flex-1'),
                $isChineseLang ? $lang->project->sv . '(' . $lang->project->svTitle . ')' : $lang->project->sv
            ),
            div
            (
                set('class', 'flex-1'),
                $isChineseLang ? $lang->project->cv . '(' . $lang->project->cvTitle . ')' : $lang->project->cv
            )
        ),
        div
        (
            set('class', 'flex'),
            div
            (
                set('class', 'flex-1'),
                $project->pv
            ),
            div
            (
                set('class', 'flex-1'),
                $project->ev
            ),
            div
            (
                set('class', 'flex-1'),
                $project->ac
            ),
            div
            (
                set('class', 'flex-1'),
                $project->sv
            ),
            div
            (
                set('class', 'flex-1'),
                $project->cv
            )
        )
    );
}

$blockNavCode = 'nav-' . uniqid();
div
(
    set('class', 'projectstatistic-block'),
    div
    (
        set('class', 'flex'),
        cell
        (
            set('width', '25%'),
            set('class', 'of-hidden bg-secondary-pale'),
            ul
            (
                set('class', 'nav nav-tabs nav-stacked'),
                getProjectTabs($projects, $blockNavCode)
            ),
        ),
        cell
        (
            set('class', 'tab-content'),
            set('width', '75%'),
            getProjectInfo($projects, $blockNavCode)
        )
    )
);

render();
