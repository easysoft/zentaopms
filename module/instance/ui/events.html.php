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
    set::actions(array(
        array(
            'text'    => $lang->instance->event->button,
            'type'    => 'primary',
            'onclick' => 'window.showEvents('.$instance->id.')',
        )
    )),
    setID('events-panel'),
    setClass('w-full'),
    formRow
    (
        formGroup
        (
            set::name('name'),
            set::labelWidth('160px'),
            set::required(true),
            set::label($lang->instance->name),
            set::value($instance->name),
            set::disabled(true),
        )
    ),
    formRow
    (
        formGroup
        (
            setID('component-events'),
            set::name('component'),
            set::labelWidth('160px'),
            set::control('picker'),
            set::required(true),
            set::label($lang->instance->component),
            on::inited()->call('initComponent', $instance->id),
            on::change()->call('showEvents', $instance->id)
        )
    ),
    div
    (
        setClass('px-1 mt-2 w-full'),
        panel
        (
            setClass('py-2'),
            form
            (
                h::pre(setID('events-content'), setClass('bg-gray-800 text-white progress block h-96')),
                set::actions(false)
            )
        )
    )
);