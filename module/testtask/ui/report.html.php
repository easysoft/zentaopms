<?php
declare(strict_types=1);
/**
 * The report view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::level(1),
            set::text($lang->testtask->report->common)
        )
    ),
);

$reports = array();
foreach($lang->testtask->report->charts as $charts => $label) $reports[] = array('text' => $label, 'value' => $charts);

div
(
    set::class('flex'),
    cell
    (
        set::width('240'),
        set::class('bg-white p-4 mr-5'),
        div(set::class('pb-2'), span(set::class('font-bold'), $lang->testtask->report->select)),
        div
        (
            set::class('pb-2'),
            control
            (
                set::type('checkList'),
                set::name('charts'),
                set::items($reports)
            )
        ),
        btn($lang->selectAll),
        btn(set::class('primary ml-4'), $lang->testtask->report->create)
    ),
    cell
    (
        set::flex('1'),
        set::class('bg-white'),
        123123
    )
);

render();
