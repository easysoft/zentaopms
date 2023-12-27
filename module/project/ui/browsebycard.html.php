<?php
declare(strict_types=1);
/**
 * The browsebycard view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('browseType', $browseType);
jsVar('param', $param);
jsVar('recTotal', $pager->recTotal);
jsVar('recPerPage', $pager->recPerPage);
jsVar('pageID', $pager->pageID);

/* zin: Define the feature bar on main menu. */
unset($programs[0]);
featureBar
(
    to::before
    (
        div
        (
            picker
            (
                set::name('programID'),
                set::value($programID),
                set::items($programs),
                set::width('200px'),
                set::placeholder($lang->project->selectProgram),
                on::change('changeProgram')
            )
        )
    ),
    set::current($browseType),
    set::linkParams("programID={$programID}&status={key}&param=&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    checkbox
    (
        set::rootClass('ml-2'),
        set::name('involved'),
        set::text($lang->project->mine),
        set::checked($this->cookie->involved ? 'checked' : '')
    ),
    li(searchToggle(set::module('project')))
);

/* zin: Define the toolbar on main menu. */
toolbar
(
    item(set(array
    (
        'type'  => 'btnGroup',
        'items' => array(array
        (
            'icon'      => 'list',
            'class'     => 'switchButton btn-icon',
            'data-type' => 'bylist'
        ), array
        (
            'icon'      => 'cards-view',
            'class'     => 'btn-icon switchButton text-primary',
            'data-type' => 'bycard'
        ))
    ))),
    hasPriv('project', 'export') ? item(set(array
    (
        'icon'        => 'export',
        'text'        => $lang->project->export,
        'class'       => 'ghost export',
        'url'         => createLink('project', 'export', "status={$browseType}&orderBy={$orderBy}"),
        'data-toggle' => 'modal'
    ))) : null,
    hasPriv('project', 'createGuide') ? item(set(array
    (
        'icon'          => 'plus',
        'text'          => $lang->project->create,
        'class'         => 'primary create-project-btn',
        'url'           => createLink('project', 'createGuide'),
        'data-toggle'   => 'modal',
        'data-position' => 'center'
    ))) : null
);

$projectCards = null;
if(!empty($projectStats))
{
    foreach($projectStats as $project)
    {
        $status        = isset($project->delay) ? 'delay' : $project->status;
        $statusLabel   = $config->project->statusLabelList[$status];
        $project->end  = $project->end == LONG_TIME ? $this->lang->project->longTime : $project->end;
        $project->date = str_replace('-', '.', $project->begin) . ' - ' . str_replace('-', '.', $project->end);

        $count         = 0;
        $memberAvatars = null;
        $lastMember    = end($project->teamMembers);
        if(!empty($project->teamMembers))
        {
            foreach($project->teamMembers as $key => $member)
            {
                if(!isset($users[$member]))
                {
                    $project->teamCount --;
                    unset($project->teamMembers[$key]);
                    continue;
                }
                if($count > 2) continue;

                $memberAvatars[] = div
                (
                    setClass('avatar circle size-sm'),
                    set::title($users[$member]),
                    avatar
                    (
                        set::size('sm'),
                        set::text($users[$member]),
                        set::src(zget($usersAvatar, $member, ''))
                    )
                );
                $count ++;
            }
        }

        $actionItems  = array();
        $actionParams = "projectID={$project->id}";
        $actionList   = array('edit', 'start', 'suspend', 'close', 'activate');
        foreach($actionList as $action)
        {
            if(!common::hasPriv('project', $action)) continue;
            $actionItem = $config->project->actionList[$action];
            if($this->project->isClickable($project, $action))
            {
                $actionItem['url']       = createLink('project', $action, $actionParams);
                $actionItem['className'] = 'text-primary';
            }
            else
            {
                unset($actionItem['url']);
                $actionItem['disabled'] = true;

            }

            $actionItems[] = $actionItem;
        }

        $projectCards[] = div
        (
            setClass('col'),
            set('data-id', $project->id),
            div
            (
                setClass('panel'),
                div
                (
                    setClass('panel-heading'),
                    span
                    (
                        setClass('label project-type-label'),
                        setClass(in_array($project->model, array('waterfall', 'waterfallplus')) ? 'warning-pale ring-warning' : 'secondary-pale ring-secondary'),
                        icon($project->model == 'scrum' ? 'sprint' : $project->model)
                    ),
                    a
                    (
                        setClass('project-name'),
                        set::href(createLink('project', 'index', "projectID={$project->id}")),
                        set::title($project->name),
                        h::strong($project->name)
                    ),
                    span
                    (
                        setClass("project-status label rounded-full {$statusLabel}"),
                        $lang->project->statusList[$status]
                    )
                ),
                div
                (
                    setClass('panel-body'),
                    div
                    (
                        setClass('project-infos pl-8'),
                        span
                        (
                            set::title($project->budget),
                            setClass('label lighter mr-2'),
                            $project->budget
                        ),
                        span
                        (
                            set::title($project->date),
                            setClass('label lighter mr-2'),
                            setClass($status == 'delay' ? 'text-danger' : ''),
                            $project->date
                        )
                    ),
                    div
                    (
                        setClass('project-detail pl-8 pt-2'),
                        div
                        (
                            setClass('row'),
                            div
                            (
                                setClass('w-1/3'),
                                div
                                (
                                    span
                                    (
                                        setClass('statistics-title'),
                                        $lang->projectCommon . $lang->project->progress
                                    )
                                ),
                                div
                                (
                                    setClass('pl-4'),
                                    set('data-zui', 'ProgressCircle'),
                                    set('data-percent', $project->progress),
                                    set('data-size', 24),
                                    set('data-circle-color', 'var(--color-success-500)')
                                )
                            ),
                            div
                            (
                                setClass('w-1/3'),
                                span
                                (
                                    setClass('statistics-title'),
                                    $lang->project->leftTasks
                                ),
                                span
                                (
                                    setClass('leftTasks'),
                                    set::title($project->leftTasks),
                                    $project->leftTasks
                                )
                            ),
                            div
                            (
                                setClass('w-1/3'),
                                span
                                (
                                    setClass('statistics-title'),
                                    $lang->project->leftHours
                                ),
                                span
                                (
                                    setClass('totalLeft'),
                                    set::title(empty($project->left) ? '— ' : $project->left . 'h'),
                                    empty($project->left) ? '— ' : $project->left . 'h'
                                )
                            )
                        )
                    ),
                    div
                    (
                        setClass('project-footer pt-2'),
                        div
                        (
                            setClass('project-team'),
                            div
                            (
                                setClass('project-members avatar-group gap-4'),
                                $memberAvatars,
                                $project->teamCount > 4 ? span
                                (
                                    '…'
                                ) : null,
                                $project->teamCount > 3 ? div
                                (
                                    setClass('avatar size-sm circle'),
                                    set::title($users[$lastMember]),
                                    avatar
                                    (
                                        set::size('sm'),
                                        set::text($users[$lastMember]),
                                        set::src(zget($usersAvatar, $lastMember, ''))
                                    )
                                ) : null,
                                a
                                (
                                    setClass('project-members-total pl-2 mt-1'),
                                    set::href(createLink('project', 'team', "projectID={$project->id}")),
                                    sprintf($lang->project->teamSumCount, $project->teamCount)
                                )
                            )
                        ),
                        div
                        (
                            setClass('project-actions'),
                            $actionItems ? dropdown
                            (
                                set::caret(false),
                                btn
                                (
                                    setClass('ghost btn square btn-default'),
                                    set::icon('ellipsis-v')
                                ),
                                set::placement('left-end'),
                                set::menu(array('class' => 'flex p-2 project-menu-actions')),
                                set::items($actionItems)
                            ) : null
                        )
                    )
                )
            )
        );
    }
}

div
(
    setID('cards'),
    setClass('row cell'),
    empty($projectStats) ? div
    (
        setClass('table-empty-tip w-full'),
        span
        (
            setClass('text-gray'),
            $lang->project->empty
        ),
        hasPriv('project', 'createGuide') ? btn(set(array
        (
            'icon'          => 'plus',
            'text'          => $lang->project->create,
            'class'         => 'ml-2',
            'url'           => createLink('project', 'createGuide'),
            'data-toggle'   => 'modal',
            'data-position' => 'center'
        ))) : null
    ) : $projectCards,
    !empty($projectStats) ? div
    (
        setID('cardsFooter'),
        pager()
    ) : null
);

/* ====== Render page ====== */
render();
