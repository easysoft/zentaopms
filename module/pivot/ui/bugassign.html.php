<?php
declare(strict_types = 1);
/**
 * The bug assign view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$cols = $config->pivot->dtable->bugAssign->fieldList;
$cols['assignedTo']['map'] = $users;

$generateData = function() use ($module, $method, $lang, $title, $cols, $bugs)
{
    if(empty($module) || empty($method)) return div(setClass('bg-white center text-gray w-full h-40'), $lang->pivot->noPivot);

    return panel
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
            set::data($bugs),
            set::emptyTip($lang->error->noData),
            set::height(jsRaw('getHeight')),
            set::plugins(array('cellspan')),
            set::getCellSpan(jsRaw('getCellSpan')),
            set::cellSpanOptions(array('assignedTo' => array(), 'total' => array()))
        )
    );
};
