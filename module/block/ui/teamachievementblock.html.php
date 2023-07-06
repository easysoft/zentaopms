<?php
declare(strict_types=1);
/**
* The teamachievement block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

panel
(
    set::class('teamAchievement-block'),
    set::bodyClass('p-0 px-4'),
    set::title($block->title),
    div
    (
        div
        (
            set('class', 'flex shadow-sm p-4'),
            cell
            (
                set::width('50%'),
                set::class('border-r'),
                div
                (
                    '登录系统人次'
                ),
                div
                (
                    set::class('mt-4'),
                    '今日 132 较昨日 +2'
                )
            ),
            cell
            (
                set::width('50%'),
                set::class('ml-4'),
                div
                (
                    '动态数量'
                ),
                div
                (
                    set::class('mt-4'),
                    '今日 132'
                )
            )
        ),
        div
        (
            set('class', 'flex shadow-sm p-4 mt-4'),
            cell
            (
                set::width('50%'),
                div
                (
                    '完成任务数量'
                ),
                div
                (
                    set::class('mt-4'),
                    '今日 132'
                )
            ),
            cell
            (
                set::width('50%'),
                div
                (
                    '创建需求数量'
                ),
                div
                (
                    set::class('mt-4'),
                    '今日 1322 较昨日 +2'
                )
            )
        ),
        div
        (
            set('class', 'flex shadow-sm p-4 mt-4'),
            cell
            (
                set::width('50%'),
                div
                (
                    '消耗工时'
                ),
                div
                (
                    set::class('mt-4'),
                    '今日 132h'
                )
            ),
            cell
            (
                set::width('50%'),
                div
                (
                    '累计工作量'
                ),
                div
                (
                    set::class('mt-4'),
                    '1322 人天 今日 +2人天'
                )
            )
        )
    )
);

render();
