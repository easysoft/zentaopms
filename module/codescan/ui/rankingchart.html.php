<?php
declare(strict_types=1);
/**
 * The ajaxGetIssueResolvedByTop view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;
echarts
(
    set::title(array('text' => $title)),
    set::height(300),
    set::tooltip
    (
        array('trigger' => 'item')
    ),
    set::yAxis
    (
        array
        (
            'type'       => 'category',
            'inverse'    => true,
            'alignTicks' => true,
            'tooltip'    => array('show' => true, 'trigger' => 'axis', 'position' => "RAWJS<function(point) {return ['1%', point[1]];}>RAWJS"),
            'data'       => array_keys($data)
        )
    ),
    set::xAxis(array('max' => 'dataMax')),
    set::series
    (
        array
        (
            'name'  => '',
            'type'  => 'bar',
            'data'  => array_values($data),
            'label' => array
            (
                'show'     => true,
                'position' => 'right'
            )
        )
    )
);
