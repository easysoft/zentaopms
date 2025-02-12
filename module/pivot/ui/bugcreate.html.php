<?php
declare(strict_types = 1);
/**
 * The bug create view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$cols = $config->pivot->dtable->bugCreate->fieldList;
$cols['openedBy']['map'] = $users;
$data        = new stdclass();
$data->cols  = $this->pivot->processDTableCols($cols);
$data->array = $this->pivot->processDTableData(array_keys($cols), $bugs);

$generateData = function() use ($lang, $title, $cols, $data, $bugs, $products, $executions, $begin, $end, $product, $execution)
{
    return array
    (
        div
        (
            setID('conditions'),
            setClass('flex gap-4 bg-canvas p-2'),
            on::change('loadBugCreate'),
            inputGroup
            (
                setClass('w-5/12'),
                $lang->pivot->bugOpenedDate,
                datePicker(set(array('name' => 'begin', 'value' => $begin))),
                $lang->pivot->to,
                datePicker(set(array('name' => 'end', 'value' => $end)))
            ),
            inputGroup
            (
                setClass('w-1/4'),
                $lang->pivot->product,
                picker
                (
                    setClass('w-full'),
                    set(array('name' => 'product', 'items' => $products, 'value' => $product))
                )
            ),
            inputGroup
            (
                setClass('w-1/3'),
                $lang->execution->common,
                picker
                (
                    setClass('w-full'),
                    set(array('name' => 'execution', 'items' => $executions, 'value' => $execution))
                )
            )
        ),
        panel
        (
            setID('pivotPanel'),
            set::title($title),
            set::shadow(false),
            set::headingClass('h-14'),
            set::bodyClass('pt-0'),
            dtable
            (
                set::striped(true),
                set::bordered(true),
                set::cols($cols),
                set::data($bugs),
                set::emptyTip($lang->error->noData),
                set::height(jsRaw('getHeight'))
            ),
            div
            (
                setID('exportData'),
                setClass('hidden'),
                rawContent(),
                $this->pivot->buildPivotTable($data, array()),
            )
        )
    );
};
