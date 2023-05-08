<?php
declare(strict_types=1);
/**
 * The manageteam view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zenggang <zenggang@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($formTitle), // The form title is diffrent from the page title,
    formRow
    (
        formGroup
        (
            set::width("1/3"),
            set::name("team[]"),
            set::value("productManager"),
            set::control("picker"),
            set::id("team"),
            set::items($teamOptions)
        ),
        formGroup
        (
            set::width("1/3"),
            inputGroup
            (

            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width("1/3"),
            set::name("team[]"),
            set::value("zenggang"),
            set::control("picker"),
            set::id("team"),
            set::items($teamOptions)
        ),
        formGroup
        (
            set::width("1/3"),
            inputGroup
            (

            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width("1/3"),
            set::name("team[]"),
            set::control("picker"),
            set::id("team"),
            set::items($teamOptions)
        ),
        formGroup
        (
            set::width("1/3"),
            inputGroup
            (

            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width("1/3"),
            set::name("team[]"),
            set::control("picker"),
            set::id("team"),
            set::items($teamOptions)
        ),
        formGroup
        (
            set::width("1/3"),
            inputGroup
            (

            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width("1/3"),
            set::name("team[]"),
            set::control("picker"),
            set::id("team"),
            set::items($teamOptions)
        ),
        formGroup
        (
            set::width("1/3"),
            inputGroup
            (

            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width("1/3"),
            set::name("team[]"),
            set::control("picker"),
            set::id("team"),
            set::items($teamOptions)
        ),
        formGroup
        (
            set::width("1/3"),
            inputGroup
            (

            )
        )
    )
);

render();

