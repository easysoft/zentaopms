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

formPanel
(
    set::title($lang->instance->event->title),
    set::actions(false),
    setID('events-panel'),
    setClass('w-full'),
    inputGroup
    (
        input
        (
            setClass('hidden'),
            set::name('name'),
            set::required(true),
            set::label($lang->instance->name),
            set::value($instance->name),
            set::inline(true),
        ),
        inputGroup
        (
            setID('component-events'),
            $lang->instance->component,
            picker
            (
                set::name('component'),
                set::control('picker'),
                set::required(true),
                set::label($lang->instance->component),
                set::inline(true),
                on::inited()->call('initComponent', $instance->id),
                on::change()->call('showEvents', $instance->id, $lang->instance->event->noEvents)
            )
        )
    ),
    div
    (
        setClass('w-full'),
        h::pre(
            setID('events-content'),
            /** 颜色值为: --color-gray-900 */
            setStyle('--scrollbar-bar-bg','#30394a'),
            setClass('bg-black text-white block h-96 scrollbar-thin')
        ),
        set::actions(false)
    )
);