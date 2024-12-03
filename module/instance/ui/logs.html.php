<?php
declare(strict_types=1);
/**
 * The setting view file of instance module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      ZhangXiquan<zhangxiquan@chandao.com>
 * @package     instance
 * @link        https://www.zentao.net
 */

namespace zin;
set::zui(true);

div
(
    setID('logs-panel'),
    setClass('px-1 mt-2 w-full'),
    formRow
    (
        setID('logs-header'),
        setClass('flex px-1 top-0 bg-white'),
        inputGroup
        (
            $lang->instance->name,
            input
            (
                set::class('w-48 bg-gray-200'),
                set::name('name'),
                set::required(true),
                set::value($instance->name),
                set::disabled(true)
            ),
            $lang->instance->component,
            picker
            (
                setClass('w-48'),
                setID('component-logs'),
                set::name('component'),
                set::control('picker'),
                set::required(true),
                on::change()->call('changeComponent', $instance->id, $lang->instance->log->noLog)
            ),
            $lang->instance->pod,
            picker
            (
                setClass('w-72'),
                setID('pod-logs'),
                set::name('pod'),
                set::required(true),
                set::items(array()),
                set::inline(true),
                on::inited()->call('initComponent', $instance->id, $lang->instance->log->noLog)
            )
        ),
        formGroup(
            setClass('flex mx-2 text-sm text-gray-500'),
            set::label($lang->instance->previous),
            div(
                setClass('mx-2 mt-1.5'),
                switcher(
                    setID('is-previous'),
                    set::name('previous'),
                    set::inline(true),
                    on::change()->call('showLogs', $instance->id, $lang->instance->log->noLog)
                )
            )
        ),
        formGroup(
            setClass('flex mr-0 ml-auto'),
            set::label($lang->instance->log->autoRefresh),
            div(
                setClass('mx-2 mt-1.5'),
                switcher(
                    set::checked(true),
                    on::click()->call('toggleAutoRefresh', $instance->id, $lang->instance->log->noLog)
                )
            )
        )
    ),
    div
    (
        setClass('px-1 mt-2 w-full h-full'),
        h::pre(
            setID('logs-content'),
            /** 颜色值为: --color-gray-900 */
            setStyle('--scrollbar-bar-bg','#30394a'),
            setClass('bg-black text-white block h-screen overflow-thin')
        ),
        set::actions(false)
    )
);
render('pagebase');