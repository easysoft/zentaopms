<?php
declare(strict_types=1);
/**
* The welcome view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      LiuRuoGu <liuruogu@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$getMeasureItem = function($data)
{
    global $lang;

    $welcomeLabel = array_merge($lang->block->welcome->assignList, $lang->block->welcome->reviewList);

    $items = array();
    foreach($data as $key => $info)
    {
        if(count($items) >= 5) break;
        $items[] = cell
        (
            div
            (
                set('class', 'text-3xl h-10'),
                !empty($info['href']) ? a(setClass('text-primary'), set('href', $info['href']), $info['number']) : span($info['number'])
            ),
            div(zget($welcomeLabel, $key, '')),
            !empty($info['delay']) ? div
            (
                set('class', 'label danger-pale circle size-sm'),
                $lang->block->delay . ' ' . $info['delay']
            ) : null
        );
    }
    return $items;
};

$blockNavCode = 'nav-' . uniqid();
panel
(
    set('class', 'welcome-block'),
    to::heading
    (
        div
        (
            set('class', 'panel-title flex w-full'),
            cell
            (
                set('width', '22%'),
                set('class', 'center'),
                span($todaySummary)

            ),
            cell
            (
                set::className('pr-8'),
                span(set('class', 'text-sm font-normal'), html(sprintf($lang->block->summary->welcome, $usageDays, $finishTask, $fixBug)))
            )
        )
    ),
    div
    (
        set('class', 'flex h-32'),
        cell
        (
            set('width', '22%'),
            set('align', 'center'),
            set::className('gradient border-right py-2'),
            center
            (
                set('class', 'font-bold'),
                sprintf($lang->block->welcomeList[$welcomeType], $app->user->realname)
            ),
            center
            (
                set::className('my-1'),
                center
                (
                    set::className('rounded-full avatar-border-one'),
                    center
                    (
                        set::className('rounded-full avatar-border-two'),
                        userAvatar
                        (
                            set::className('welcome-avatar ellipsis'),
                            set('user', $this->app->user)
                        )
                    )
                )
            ),
            $honorary ? center(span(set('class', 'label circle honorary text-xs'), $honorary)) : null
        ),
        cell
        (
            set('width', '78%'),
            set::className('px-8'),
            tabs
            (
                empty($lang->block->welcome->reviewList) ? null : tabPane
                (
                    set::key("reviewByMe_$blockNavCode"),
                    set::title($lang->block->welcome->reviewByMe),
                    div
                    (
                        set::className('flex justify-around text-center'),
                        $getMeasureItem($reviewByMe)
                    )
                ),
                tabPane
                (
                    set::key("assignToMe_$blockNavCode"),
                    set::title($lang->block->welcome->assignToMe),
                    set::active(true),
                    div
                    (
                        set::className('flex justify-around text-center'),
                        $getMeasureItem($assignToMe)
                    )
                )
            )
        )
    )
);

render();
