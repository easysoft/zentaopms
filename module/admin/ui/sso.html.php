<?php
declare(strict_types=1);
/**
 * The sso view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::title($title),
    formRow
    (
        formGroup
        (
            set::label($lang->sso->turnon),
            radioList
            (
                set::name('turnon'),
                set::items($lang->sso->turnonList),
                set::inline(true),
                set::value($turnon)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->sso->redirect),
            radioList
            (
                set::name('redirect'),
                set::items($lang->sso->turnonList),
                set::inline(true),
                set::value($redirect)
            )
        )
    ),
    formRow
    (
        set::width('1/2'),
        formGroup
        (
            set::label($lang->sso->addr),
            input
            (
                set::name('addr'),
                set::value($addr),
                set::placeholder($lang->sso->addrNotice)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label(''),
            div
            (
                icon('help text-warning mr-1'),
                html($lang->sso->help->addr)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->sso->code),
            input
            (
                set::name('code'),
                set::value($code)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label(''),
            div
            (
                icon('help text-warning mr-1'),
                html($lang->sso->help->code)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->sso->key),
            input
            (
                set::name('key'),
                set::value($key)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label(''),
            div
            (
                icon('help text-warning mr-1'),
                html($lang->sso->help->key)
            )
        )
    )
);

render();
