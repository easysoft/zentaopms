<?php
declare(strict_types=1);
/**
 * The browse view file of company module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     company
 * @link        https://www.zentao.net
 */
namespace zin;
$metricID = $metric->id;
$current  = $metric;
$viewType = 'multiple';
include 'component/queryform.html.php';
include 'component/tableandcharts.html.php';

jsVar('updateTimeTip', $lang->metric->updateTimeTip);



$star = (!empty($metric->collector) and strpos($metric->collector, ',' . $app->user->account . ',') !== false) ? 'star' : 'star-empty';
div
(
    setClass('metric-name metric-name-notfirst flex flex-between items-center'),
    div
    (
        span
        (
            setClass('metric-name-weight'),
            $metric->name
        ),
        btn
        (
            setClass('metric-collect metric-collect-' . $metricID),
            set::type('link'),
            set::icon($star),
            set::iconClass($star),
            set::square(true),
            set::size('sm'),
            set::title($lang->metric->collectStar),
            on::click('.metric-collect', "window.collectMetric({$metricID})")
        )
    ),
    div
    (
        setClass('flex-start'),
        toolbar
        (
            haspriv('metric', 'details') ? item(set(array
            (
                'text'  => $this->lang->metric->details,
                'class' => 'ghost details',
                'url'         => helper::createLink('metric', 'details', "metricID=$metricID"),
                'data-toggle' => 'modal'
            ))) : null,
            item(set(array
            (
                'text'    => $this->lang->metric->remove,
                'class'   => 'ghost metric-remove',
                'onclick' => "window.handleRemoveLabel($metricID)"
            ))),
            haspriv('metric', 'filters') ? item(set(array
            (
                'icon'  => 'menu-backend',
                'text'  => $this->lang->metric->filters,
                'class' => 'ghost hidden',
                'url'   => '#'
            ))) : null,
            haspriv('metric', 'zAnalysis') ? item(set(array
            (
                'icon'  => 'chart-line',
                'text'  => $this->lang->metric->zAnalysis,
                'class' => 'ghost chart-line-margin hidden',
                'url'   => '#'
            ))) : null
        )
    )
);
$fnGenerateQueryForm($viewType);
$fnGenerateTableAndCharts($metric);
