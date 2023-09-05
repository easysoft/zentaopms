<?php
declare(strict_types=1);
/**
 * The create view file of account module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     account
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::id('accountCreateForm'),
    set::title($lang->account->create),
    formRow
    (
        formGroup
        (
            set::name('name'),
            set::label($lang->account->name),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->account->provider),
            set::control('picker'),
            set::name('provider'),
            set::items($lang->serverroom->providerList),
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('adminURI'),
            set::label($lang->account->adminURI),
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('account'),
            set::label($lang->account->account),
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('password'),
            set::label($lang->account->password),
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('email'),
            set::label($lang->account->email),
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('mobile'),
            set::label($lang->account->mobile),
        )
    ),
);
