<?php
declare(strict_types=1);
/**
 * The hours view file of custom module of ZenTaoPMS.
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
        set::actions(array('submit')),
        set::actionsClass('w-1/3'),
        setClass('flex-auto ml-4'),
        span
        (
            setClass('text-md font-bold'),
            $type == 'hours' ? $lang->custom->setHours : $lang->custom->setWeekend
        ),
        formGroup
        (
            setClass($type == 'hours' ? '' : 'hidden'),
            set::label($lang->custom->workingHours),
            set::width('1/3'),
            set::name('defaultWorkhours'),
            set::value($workhours)
        ),
        formGroup
        (
            setClass($type == 'weekend' ? '' : 'hidden'),
            set::label($lang->custom->weekendRole),
            set::width('1/3'),
            radioList
            (
                set::name('weekend'),
                set::items($lang->custom->weekendList),
                set::value($weekend),
                set::inline(true)
            )
        ),
        formGroup
        (
            setClass($weekend == 1 && $type == 'weekend'? '' : 'hidden'),
            setID('restDayBox'),
            set::width('1/3'),
            set::label($lang->custom->setWeekend),
            picker
            (
                set::name('restDay'),
                set::items($lang->custom->restDayList),
                set::value($restDay),
                set::required(true)
            )
        ),
        formHidden('type', $type)
    )
);

/* ====== Render page ====== */
render();
