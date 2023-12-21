<?php
declare(strict_types=1);
/**
 * The index view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

/* zin: Define the feature bar on main menu. */
featureBar
(
    set::current($browseType),
    set::linkParams("projectID={$project->id}&browseType={key}")
);

/* zin: Define the toolbar on main menu. */
if(hasPriv('execution', 'create'))
{
    toolbar
    (
        item(set(array
        (
            'icon'  => 'plus',
            'text'  => $lang->project->createKanban,
            'class' => 'primary',
            'url'   => createLink('execution', 'create', "projectID={$project->id}")
        )))
    );
}

$app->loadLang('kanban');
$kanbanCards = array();
if(!empty($kanbanList))
{
    foreach($kanbanList as $kanban)
    {
        $status      = ($kanban->end < helper::today() && !in_array($kanban->status, array('done', 'closed', 'suspended'))) ? 'delay' : $kanban->status;
        $statusLabel = $config->project->statusLabelList[$status];

        $count         = 0;
        $members       = zget($memberGroup, $kanban->id, array());
        $memberAvatars = null;
        $lastMember    = end($members);
        foreach($members as $key => $member)
        {
            if(!isset($userIdPairs[$member->account]))
            {
                $kanban->teamCount --;
                unset($members[$key]);
                continue;
            }
            if($count > 2) break;

            $memberAvatars[] = div
            (
                setClass('avatar circle size-sm'),
                set::title($userIdPairs[$member->account]),
                avatar
                (
                    set::size('sm'),
                    set::src(zget($usersAvatar, $member->account, '')),
                    set::text(zget($member, 'realname', $member->account))
                )
            );
            $count ++;
        }

        $actionItems = array();
        foreach($actionList as $action)
        {
            if(!common::hasPriv('execution', $action)) continue;
            if(!$this->execution->isClickable($kanban, $action)) continue;

            $actionItem = $config->execution->actionList[$action];
            $actionItem['url'] = createLink('execution', $action, "executionID={$kanban->id}");
            if($action == 'edit')
            {
                $actionItem['text'] = $lang->kanban->edit;
                $actionItem['hint'] = $lang->kanban->edit;
            }
            elseif($action == 'delete')
            {
                $actionItem['text'] = $lang->kanban->delete;
                $actionItem['hint'] = $lang->kanban->delete;
            }

            $actionItems[] = $actionItem;
        }

        $cardCount =  count($kanbanCards);
        $kanbanCards[] = div
        (
            setClass('col flex-none w-1/4 open-url cursor-pointer'),
            hasPriv('execution', 'kanban') ? set('data-url', createLink('execution', 'kanban', "kanbanID={$kanban->id}")) : null,
            div
            (
                setClass('border py-2 pl-4', $cardCount % 4 == 0 ? '' : 'ml-4', $cardCount > 3 ? 'mt-4' : ''),
                div
                (
                    setClass('flex justify-between items-center'),
                    div
                    (
                        span
                        (
                            setClass("project-status label rounded-full {$statusLabel}"),
                            $lang->project->statusList[$status]
                        ),
                        a
                        (
                            setClass('project-name ml-2'),
                            set::href(createLink('execution', 'kanban', "kanbanID={$kanban->id}")),
                            set::title($kanban->name),
                            h::strong($kanban->name)
                        )
                    ),
                    div
                    (
                        $actionItems ? dropdown
                        (
                            set::caret(false),
                            btn
                            (
                                setClass('ghost square mr-2 open-url not-open-url'),
                                set::icon('ellipsis-v')
                            ),
                            set::items($actionItems)
                        ) : null
                    )
                ),
                div
                (
                    div
                    (
                        setClass('kanban-desc h-24 overflow-hidden pt-2 mr-4 text-gray'),
                        set::title(strip_tags(htmlspecialchars_decode($kanban->desc))),
                        strip_tags(htmlspecialchars_decode($kanban->desc))
                    ),
                    div
                    (
                        setClass('project-footer pt-2'),
                        div
                        (
                            setClass('flex justify-between items-center'),
                            div
                            (
                                setClass('project-members avatar-group gap-4'),
                                $memberAvatars,
                                $kanban->teamCount > 4 ? span
                                (
                                    '…'
                                ) : null,
                                $kanban->teamCount > 3 ? div
                                (
                                    setClass('avatar size-sm circle'),
                                    set::title($lastMember->realname),
                                    avatar
                                    (
                                        set::size('sm'),
                                        set::text($lastMember->realname),
                                        set::src(zget($usersAvatar, $lastMember->account, ''))
                                    ),
                                ) : null,
                                span
                                (
                                    setClass('project-members-total pl-2 mt-1'),
                                    sprintf($lang->project->teamSumCount, count($members))
                                )
                            ),
                            div
                            (
                                setClass('kanban-acl flex items-center justify-center pr-4'),
                                icon
                                (
                                    $kanban->acl == 'private' ? 'lock' : 'inherit-space',
                                    setClass('mr-1')
                                ),
                                zget($lang->execution->kanbanAclList, $kanban->acl, '')
                            )
                        )
                    )
                )
            )
        );
    }
}

panel
(
    setID('cards'),
    setClass('row cell canvas'),
    set::bodyClass('w-full'),
    empty($kanbanList) ? div
    (
        setClass('dtable-empty-tip'),
        div
        (
            setClass('row gap-4 items-center'),
            span
            (
                setClass('text-gray'),
                $lang->noData
            ),
            hasPriv('execution', 'create') ? btn(set(array
            (
                'icon'  => 'plus',
                'text'  => $lang->project->createKanban,
                'class' => 'btn primary-pale border-primary',
                'url'   => createLink('execution', 'create', "projectID={$project->id}")
            ))) : null
        )
    ) : div(
        setClass('flex flex-wrap'),
        $kanbanCards
    ),
    !empty($kanbanList) ? div
    (
        setClass('pt-4 flex justify-end'),
        pager
        (
            set::items(usePager(array(
                'linkCreator' => helper::createLink('project', 'index', "projectID={$project->id}&browseType={$browseType}&recTotal={recTotal}&recPerPage={recPerPage}&pageID={page}")
            )))
        )
    ) : null
);

/* ====== Render page ====== */
render();
