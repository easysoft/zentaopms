<?php
declare(strict_types = 1);
/**
 * The project deviation view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$cols = $config->pivot->dtable->projectDeviation->fieldList;

$generateData = function() use ($module, $method, $lang, $cols, $executions)
{
    if(empty($module) || empty($method)) return div(setClass('bg-white center text-gray w-full h-40'), $lang->error->noData);

    return div
    (
        setClass('w-full'),
        div
        (
            setID('conditions'),
            setClass('bg-white mb-4 p-2'),
        ),
        dtable
        (
            set::cols($cols),
            set::data($executions),
            set::fixedLeftWidth('0.25'),
            set::emptyTip($lang->error->noData)
        ),
        echarts
        (
        )
    );
};
