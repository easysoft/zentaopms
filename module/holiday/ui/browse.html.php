<?php
declare(strict_types=1);
/**
 * The browse view file of holiday module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     holiday
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('changeYearLink', createLink('holiday', 'browse', 'year={year}'));

sidebar
(
    set::showToggle(false),
    div
    (
        setClass('cell p-2.5 bg-white'),
        menu
        (
            setClass('menu'),
            hasPriv('custom', 'hours') ? li
            (
                setClass('menu-item'),
                a
                (
                    set::href(createLink('custom', 'hours', 'type=hours')),
                    $lang->custom->setHours
                )
            ) : null,
            hasPriv('custom', 'hours') ? li
            (
                setClass('menu-item'),
                a
                (
                    set::href(createLink('custom', 'hours', 'type=weekend')),
                    $lang->custom->setWeekend
                )
            ) : null,
            li
            (
                setClass('menu-item'),
                a
                (
                    setClass('active'),
                    set::href(createLink('holiday', 'browse')),
                    $lang->custom->setHoliday
                )
            )
        )
    )
);

$tableData = initTableData($holidays, $this->config->holiday->dtable->fieldList, $this->holiday);
panel
(
    div
    (
        setClass('flex'),
        featureBar
        (
            div
            (
                setClass('check-year'),
                span
                (
                    setClass('form-name'),
                    h::strong($lang->holiday->checkYear)
                ),
                picker
                (
                    set::name('year'),
                    set::value($currentYear),
                    set::items($yearList),
                    set::required(true),
                    set::className('w-20'),
                    on::change('changeYear')
                )
            )
        ),
        toolbar
        (
            hasPriv('holiday', 'create') ? btn
            (
                setClass('btn primary'),
                set::icon('plus'),
                set::url(helper::createLink('holiday', 'create')),
                set('data-toggle', 'modal'),
                set('data-size', 'sm'),
                $lang->holiday->create
            ) : null
        )
    ),
    dtable
    (
        set::cols($this->config->holiday->dtable->fieldList),
        set::data($tableData),
        set::footer()
    ),
    center
    (
        setClass('mt-2'),
        div
        (
            setClass('table-import shadow-sm'),
            html(sprintf($lang->holiday->importTip, html::a(helper::createLink('holiday', 'import', "year={$currentYear}"), $lang->import, '', "class='text-primary' data-toggle='modal' data-size='480px'")))
        )
    )
);

render();
