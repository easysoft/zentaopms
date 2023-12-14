<?php
declare(strict_types=1);
/**
 * The limitTaskDate view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     custom
 * @link        https://www.zentao.net
 */
namespace zin;
include 'sidebar.html.php';

div
(
    setClass('flex'),
    $sidebarMenu,
    formPanel
    (
        setID('limitTaskDateForm'),
        set::actions(array('submit')),
        set::actionsClass('w-2/3'),
        setClass('flex-auto ml-4'),
        span
        (
            setClass('text-md font-bold'),
            $lang->custom->$module->fields['limitTaskDate']
        ),
        formGroup
        (
            set::width('2/3'),
            set::label($lang->custom->beginAndEndDateRange),
            radioList
            (
                set::name('limitTaskDate'),
                set::items($lang->custom->limitTaskDate),
                set::value(isset($config->limitTaskDate) ? $config->limitTaskDate : 0),
                set::inline(true)
            )
        )
    )
);

/* ====== Render page ====== */
render();
