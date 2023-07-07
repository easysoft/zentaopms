<?php
declare(strict_types=1);
/**
 * The create view file of gogs module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     gogs
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::id('gogsCreateForm'),
    set::title($lang->gogs->lblCreate),
    formRow
    (
        formGroup
        (
            set::name('name'),
            set::label($lang->gogs->name),
            set::value($gogs->name),
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('url'),
            set::label($lang->gogs->url),
            set::value($gogs->url),
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('token'),
            set::label($lang->gogs->token),
            set::value($gogs->token),
        )
    ),
);
