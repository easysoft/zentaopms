<?php
declare(strict_types=1);
/**
 * The create view file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     gitlab
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::id('gitlabCreateForm'),
    set::title($lang->gitlab->lblCreate),
    formRow
    (
        formGroup
        (
            set::name('name'),
            set::label($lang->gitlab->name),
            set::value($gitlab->name),
            set::placeholder($lang->gitlab->placeholder->name)
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('url'),
            set::label($lang->gitlab->url),
            set::value($gitlab->url),
            set::placeholder($lang->gitlab->placeholder->url)
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('token'),
            set::label($lang->gitlab->token),
            set::value($gitlab->token),
            set::placeholder($lang->gitlab->placeholder->token)
        )
    ),
);
