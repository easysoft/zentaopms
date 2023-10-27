<?php
declare(strict_types = 1);
/**
 * The product summary view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$cols = $config->pivot->dtable->productSummary->fieldList;
$cols['PO']['map'] = $users;

$generateData = function() use ($module, $method, $lang, $title, $cols, $products)
{
    if(empty($module) || empty($method)) return div(setClass('bg-white center text-gray w-full h-40'), $lang->error->noData);

    return array
    (
        div
        (
            setID('conditions'),
            setClass('bg-white p-2'),
            checkList
            (
                on::change('loadProductSummary'),
                set::inline(true),
                set::items(array(array('text' => $lang->pivot->closedProduct, 'value' => 'closedProduct'), array('text' => $lang->pivot->overduePlan, 'value' => 'overduePlan')))
            )
        ),
        panel
        (
            setID('pivotPanel'),
            set::title($title),
            set::shadow(false),
            set::bodyClass('pt-0'),
            dtable
            (
                set::striped(true),
                set::bordered(true),
                set::cols($cols),
                set::data($products),
                set::emptyTip($lang->error->noData),
                set::height(jsRaw('getHeight')),
                set::plugins(array('cellspan')),
                set::getCellSpan(jsRaw('getCellSpan')),
                set::cellSpanOptions(array(array('cols' => array('name', 'PO'), 'rowspan' => 'rowspan')))
            )
        )
    );
};
