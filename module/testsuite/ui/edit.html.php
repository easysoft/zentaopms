<?php
declare(strict_types=1);
/**
 * The edit view file of testsuite module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testsuite
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->testsuite->edit),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('name'),
            set::label($lang->testsuite->name),
            set::value($suite->name)
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->testsuite->desc),
            set::required(strpos(",{$config->testsuite->edit->requiredFields},", ',desc,') !== false),
            editor
            (
                set::name('desc'),
                set::rows('5'),
                html($suite->desc)
            ),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->testsuite->author),
            radioList
            (
                set::inline(true),
                set::name('type'),
                set::items($lang->testsuite->authorList),
                set::value($suite->type),
            )
        )
    ),
);

render();
