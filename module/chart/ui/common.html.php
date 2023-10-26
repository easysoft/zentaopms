<?php
declare(strict_types = 1);
/**
 * The preview view file of chart module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     chart
 * @link        https://www.zentao.net
 */

namespace zin;

function initEchart($options)
{
    return echarts
    (
        set($options),
    )->size('100%', 400);
}

function initFilter($filter, $lang)
{
    $type    = $filter['type'];
    $name    = $filter['name'];
    $default = $filter['default'];
    $option  = $filter['option'];

    if($type == 'form-date' or $type == 'form-datetime')
    {
        $dateType = $type == 'form-date' ? 'date' : 'datetime-local';
        return inputGroup
        (
            setClass('filter-item'),
            $name,
            input(set(array('name' => 'default[begin]', 'type' => $dateType, 'value' => $default))),
            $lang->chart->colon,
            input(set(array('name' => 'default[end]', 'type' => $dateType, 'value' => $default))),
        );
    }
    elseif($type == 'select')
    {
        return inputGroup
        (
            setClass('filter-item'),
            $name,
            picker
            (
                setClass('w-full'),
                set(array('name' => 'default', 'items' => $option, 'value' => $default))
            )
        );
    }
    elseif($type == 'input')
    {
        return inputGroup
        (
            setClass('filter-item'),
            $name,
            input
            (
                setClass('w-full'),
                set(array('name' => 'default', 'value' => $default))
            )
        );
    }

}
