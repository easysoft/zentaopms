<?php
declare(strict_types=1);
/**
 * The create view file of testsuite module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testsuite
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    setID('testsuiteCreateForm'),
    set::title($lang->testsuite->create),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('name'),
            set::label($lang->testsuite->name),
            set::value('')
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->testsuite->desc),
            set::required(strpos(",{$config->testsuite->create->requiredFields},", ',desc,') !== false),
            editor
            (
                set::name('desc'),
                set::rows('5')
            )
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
                set::value('private')
            )
        )
    )
);

render();
