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
        formGroup(
            inputGroup
            (
                h::button
                (
                    setClass('btn primary'),
                    $lang->instance->log->autoRefresh,
                    span(
                    setID('autoRefreshBtn'),
                    icon(setClass('icon icon-pause'))
                ),
                on::click()->call('toggleAutoRefresh', $instance->id)
                ),
                $lang->instance->name,
                input
                (
                    set::class('input-group-addon'),
                    set::name('name'),
                    set::required(true),
                    set::value($instance->name),
                    set::disabled(true)
                ),
                $lang->instance->component,
                picker
                (
                    setClass('input-group-addon w-48'),
                    setID('component-logs'),
                    set::name('component'),
                    set::control('picker'),
                    set::required(true),
                    on::change()->call('changeComponent', $instance->id)
                ),
                $lang->instance->pod,
                picker
                (
                    setClass('input-group-addon w-72'),
                    setID('pod-logs'),
                    set::name('pod'),
                    set::required(true),
                    set::items(array()),
                    set::inline(true),
                    on::inited()->call('initComponent', $instance->id)
                )
            )
        ),
        formGroup
        (
            setClass('flex mx-2'),
            setID('is-previous'),
            set::name('previous'),
            set::control('radioListInline'),
            set::items($lang->instance->isPreviousList),
            set::value(0),
            set::inline(true),
            on::change()->call('showLogs', $instance->id)
        ),
        formGroup(
            setClass('flex mx-2 text-sm text-gray-500'),
            set::label( setClass('text-danger'), $lang->instance->log->tips)
        )
    ),
    div
    (
        setClass('px-1 mt-2 w-full h-full'),
        h::pre(
            setID('logs-content'),
            setClass('bg-gray-800 text-white block h-screen scrollbar-thin')
        ),
        set::actions(false)
    )
);
render('pagebase');