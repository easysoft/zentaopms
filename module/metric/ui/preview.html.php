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

if($viewType == 'single')
{
    $metricTree = array();
    foreach($metrics as $key => $metric)
    {
        $class = 'metric-item';
        if($key == 0) $class .= ' metric-current';
        $metricTree[] = div(setClass($class), $metric->name);
    }
}
else
{
    $metricCheckList = array();
    $checkedList = array($key);
    foreach($metrics as $key => $metric) $metricCheckList[] = array('text' => $metric->name, 'value' => $key, 'checked' => in_array($key, $checkedList));
}

featureBar
(
    set::current($scope),
    set::linkParams("scope={key}"),
);

$exchangeType = $viewType == 'single' ? 'multiple' : 'single';
toolbar
(
    btn
    (
        setClass('btn text-black ghost primary-hover-500'),
        set::icon('exchange'),
        set::iconClass('icon-18'),
        set::url(helper::createLink('metric', 'preview', "scope=$scope&viewType=$exchangeType")),
        $lang->metric->viewType->$exchangeType,
    ),
    common::hasPriv('metric', 'preview') ? btn
    (
        setClass('btn primary'),
        set::url(helper::createLink('metric', 'browse')),
        $lang->metric->manage
    ) : null,
);

div
(
    setClass('side'),
    div
    (
        setClass('canvas'),
        div
        (
            setClass('title flex items-center'),
            span
            (
                setClass('name-color'),
                $metricList
            ),
        ),
        div
        (
            setClass('metric-tree'),
            $viewType == 'single' ? $metricTree : checkList
            (
                set::primary(true),
                set::name('metric'),
                set::inline(false),
                set::items($metricCheckList),
            ),
        ),
    ),
);
div
(
    setClass('main'),
    div
    (
        setClass('canvas'),
        div
        (
            setClass('metric-name flex flex-between items-center'),
            div
            (
                span
                (
                    setClass('metric-name-weight'),
                    $current->name
                ),
            ),
            div
            (
                setClass('flex-start'),
                toolbar
                (
                    haspriv('bug', 'report') ? item(set(array
                    (
                        'text'  => $this->lang->metric->details,
                        'class' => 'ghost details',
                        'url'   => '#',
                    ))) : null,
                    haspriv('bug', 'report') ? item(set(array
                    (
                        'icon'  => 'menu-backend',
                        'text'  => $this->lang->metric->filters,
                        'class' => 'ghost',
                        'url'   => '#',
                    ))) : null,
                    haspriv('bug', 'report') ? item(set(array
                    (
                        'icon'  => 'chart-line',
                        'text'  => $this->lang->metric->zAnalysis,
                        'class' => 'ghost chart-line-margin',
                        'url'   => '#',
                    ))) : null,

                )
            ),
        ),
    ),
);
render();
