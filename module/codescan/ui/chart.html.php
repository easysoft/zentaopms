<?php
declare(strict_types=1);
/**
 * The chart view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;
if(!isset($blockHeight)) $blockHeight = 300;

$pieChart = function(array $data, string $title, string $type) use($blockHeight)
{
    $colorList = array(
        'status'   => array('#EE6666', '#FAC858', '#5C7BD9', '#91CC75', '#C0C7D1'),
        'type'     => array('#5C7BD9', '#EE6666', '#FAC858', '#91CC75'),
        'priority' => array('#EE6666', '#FAC858', '#E1E1E2')
    );

    $dataJson = json_encode($data);
    return echarts
    (
        set::height($blockHeight),
        set::color($colorList[$type]),
        set::title
        (
            array
            (
                'text'    => $title,
                'subtext' => '',
                'left'    => 'left'
            )
        ),
        set::tooltip
        (
            array
            (
                'trigger' => 'item'
            )
        ),
        set::legend
        (
            array
            (
                'type'      => 'scroll',
                'orient'    => 'vertical',
                'left'      => 'left',
                'top'       => 'center',
                'formatter' => "RAWJS<function(name){return window.initLegends(name, '$dataJson');}>RAWJS"
            )
        ),
        set::series
        (
            array
            (
                'name'   => '',
                'type'   => 'pie',
                'center' => array('75%', '50%'),
                'radius' => '50%',
                'data'   => $data,
                'label'  => array
                (
                    'show'     => false,
                    'position' => 'center'
                ),
                'labelLine' => array
                (
                    'show' => false
                ),
                'emphasis' => array
                (
                    'itemStyle' => array
                    (
                        'shadowBlur'    => 10,
                        'shadowOffsetX' => 0,
                        'shadowColor'   => 'rgba(0, 0, 0, 0.5)'
                    )
                )
            )
        )
    );
};

$stackedChart = function($title = '', $data = array()) use($blockHeight, $lang)
{
    $category = array_column($data, 'name');
    $series = array(
        array('name' => $lang->codescan->severityList['high'], 'type' => 'bar', 'stack' => 'total', 'emphasis' => array('focus' => 'series'), 'data' => array_column($data, 'high')),
        array('name' => $lang->codescan->severityList['medium'], 'type' => 'bar', 'stack' => 'total', 'emphasis' => array('focus' => 'series'), 'data' => array_column($data, 'medium')),
        array('name' => $lang->codescan->severityList['low'], 'type' => 'bar', 'stack' => 'total', 'emphasis' => array('focus' => 'series'), 'data' => array_column($data, 'low')),
    );

    return echarts
    (
        set::height($blockHeight),
        set::title(array('text' => $title, 'left' => 'left')),
        set::color(array('#FF4F4F', '#FB923C', '#E1E1E2')),
        set::tooltip(array('trigger' => 'axis', 'axisPointer' => array('type' => 'shadow'))),
        set::xAxis(array('type' => 'value')),
        set::legend(array(
            'left' => 'right',
            'data' => array(
                array('name' => $lang->codescan->severityList['high'],   'icon' => 'circle'),
                array('name' => $lang->codescan->severityList['medium'], 'icon' => 'circle'),
                array('name' => $lang->codescan->severityList['low'],    'icon' => 'circle')
            )
        )),
        set::yAxis(array('type' => 'category', 'inverse' => true, 'data' => $category)),
        set::series($series)
    );
};

$rankingChart = function($title = '', $metrics = array()) use($blockHeight)
{
    return echarts
    (
        set::title(array('text' => $title)),
        set::height($blockHeight),
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
                'data'       => array_keys($metrics),
                'axisLabel'  => array('formatter' => "RAWJS<function(name) {if(name.includes('/')) {const splitName =  name.split('/'); name = splitName[splitName.length - 1];};return name;}>RAWJS")
            )
        ),
        set::xAxis(array('type' => 'value', 'boundaryGap' => array(0, 0.01))),
        set::grid(array('left' => '1%', 'right' => '1%', 'bottom' => '1%', 'containLabel' => true)),
        set::series
        (
            array
            (
                'name'  => '',
                'type'  => 'bar',
                'data'  => array_values($metrics),
                'label' => array
                (
                    'show'     => true,
                    'position' => 'right'
                )
            )
        )
    );
};

h::js
(
<<<JS
window.initLegends = function(name, pieData)
{
    pieData = JSON.parse(pieData);

    const total   = pieData.reduce((sum, item) => sum + item.value, 0);
    const item    = pieData.find(d => d.name === name);
    const percent = total ? (item.value / total * 100).toFixed(0) : 0;
    return `\${name} \${percent}%`;
};
JS
);
