<?php
declare(strict_types=1);
/**
 * The create view file of serverroom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     serverroom
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::id('serverroomCreateForm'),
    set::title($lang->serverroom->create),
    formRow
    (
        formGroup
        (
            set::name('name'),
            set::label($lang->serverroom->name),
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('bandwidth'),
            set::label($lang->serverroom->bandwidth),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->serverroom->city),
            set::control('picker'),
            set::name('city'),
            set::items($lang->serverroom->cityList),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->serverroom->line),
            set::control('picker'),
            set::name('line'),
            set::items($lang->serverroom->lineList),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->serverroom->provider),
            set::control('picker'),
            set::name('provider'),
            set::items($lang->serverroom->providerList),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->serverroom->owner),
            set::control('picker'),
            set::name('owner'),
            set::items($users),
        )
    ),
);
