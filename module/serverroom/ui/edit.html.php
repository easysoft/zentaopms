<?php
declare(strict_types=1);
/**
 * The edit view file of serverroom module of ZenTaoPMS.
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
    set::title($lang->serverroom->edit),
    formRow
    (
        formGroup
        (
            set::width('600px'),
            set::name('name'),
            set::label($lang->serverroom->name),
            set::value($serverRoom->name),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('600px'),
            set::name('bandwidth'),
            set::label($lang->serverroom->bandwidth),
            set::value($serverRoom->bandwidth),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('300px'),
            set::label($lang->serverroom->city),
            set::control('picker'),
            set::name('city'),
            set::items($lang->serverroom->cityList),
            set::value($serverRoom->city)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('300px'),
            set::label($lang->serverroom->line),
            set::control('picker'),
            set::name('line'),
            set::items($lang->serverroom->lineList),
            set::value($serverRoom->line)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('300px'),
            set::label($lang->serverroom->provider),
            set::control('picker'),
            set::name('provider'),
            set::items($lang->serverroom->providerList),
            set::value($serverRoom->provider)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('300px'),
            set::label($lang->serverroom->owner),
            set::control('picker'),
            set::name('owner'),
            set::items($users),
            set::value($serverRoom->owner)
        )
    ),
);
