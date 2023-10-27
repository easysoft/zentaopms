<?php
declare(strict_types = 1);
/**
 * The show view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$generateData = function() use ($module, $method, $lang, $title, $pivot, $data, $configs)
{
    if(empty($module) || empty($method)) return div(setClass('bg-white center text-gray w-full h-40'), $lang->pivot->noPivot);

    list($cols, $rows, $cellSpan) = $this->convertDataForDtable($data, $configs);

    return array
    (
        panel
        (
            setID('pivotPanel'),
            set::title($title),
            set::shadow(false),
            set::bodyClass('pt-0'),
            $pivot->desc ? to::titleSuffix(
                icon
                (
                    setClass('cursor-pointer'),
                    setData(array('toggle' => 'tooltip', 'title' => $pivot->desc, 'placement' => 'right', 'className' => 'text-gray border border-light', 'type' => 'white')),
                    'help'
                )
            ) : null,
            dtable
            (
                set::striped(true),
                set::bordered(true),
                set::cols($cols),
                set::data($rows),
                set::emptyTip($lang->error->noData),
                $cellSpan ? set::plugins(array('cellspan')) : null,
                $cellSpan ? set::getCellSpan(jsRaw('getCellSpan')) : null,
                $cellSpan ? set::cellSpanOptions($cellSpan) : null
            )
        )
    );
};
