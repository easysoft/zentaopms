<?php
declare(strict_types=1);
/**
 * The create view file of cron module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     cron
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::title($lang->cron->create),
    set::entityText($lang->cron->common)
);

formPanel
(
    set::actions(array('submit')),
    set::submitBtnText($lang->cron->create),
    set::size('md'),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::label($lang->cron->m),
            set::name('m')
        ),
        formGroup
        (
            icon
            (
                'help',
                set('data-toggle', 'tooltip'),
                set('data-title', $lang->cron->notice->m),
                set('data-placement', 'right'),
                set('data-type', 'white'),
                set('data-class-name', 'text-gray border border-light'),
                setClass('ml-2 mt-2 text-gray')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::label($lang->cron->h),
            set::name('h')
        ),
        formGroup
        (
            icon
            (
                'help',
                set('data-toggle', 'tooltip'),
                set('data-title', $lang->cron->notice->h),
                set('data-placement', 'right'),
                set('data-type', 'white'),
                set('data-class-name', 'text-gray border border-light'),
                setClass('ml-2 mt-2 text-gray')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::label($lang->cron->dom),
            set::name('dom')
        ),
        formGroup
        (
            icon
            (
                'help',
                set('data-toggle', 'tooltip'),
                set('data-title', $lang->cron->notice->dom),
                set('data-placement', 'right'),
                set('data-type', 'white'),
                set('data-class-name', 'text-gray border border-light'),
                setClass('ml-2 mt-2 text-gray')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::label($lang->cron->mon),
            set::name('mon')
        ),
        formGroup
        (
            icon
            (
                'help',
                set('data-toggle', 'tooltip'),
                set('data-title', $lang->cron->notice->mon),
                set('data-placement', 'right'),
                set('data-type', 'white'),
                set('data-class-name', 'text-gray border border-light'),
                setClass('ml-2 mt-2 text-gray')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::label($lang->cron->dow),
            set::name('dow')
        ),
        formGroup
        (
            icon
            (
                'help',
                set('data-toggle', 'tooltip'),
                set('data-title', $lang->cron->notice->dow),
                set('data-placement', 'right'),
                set('data-type', 'white'),
                set('data-class-name', 'text-gray border border-light'),
                setClass('ml-2 mt-2 text-gray')
            )
        )
    ),
    formGroup
    (
        set::width('full'),
        set::label($lang->cron->command),
        set::name('command')
    ),
    formGroup
    (
        set::width('full'),
        set::label($lang->cron->remark),
        set::name('remark')
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->cron->type),
        set::name('type'),
        set::items($lang->cron->typeList),
        set::value('zentao')
    )
);

/* ====== Render page ====== */
render();
