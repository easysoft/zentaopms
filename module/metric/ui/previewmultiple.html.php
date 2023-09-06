<?php
declare(strict_types=1);
/**
 * The preview multiple file of company module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     company
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('resultHeader', $resultHeader);
jsVar('resultData',   $resultData);
jsVar('current', $current);

$items = array();
foreach($lang->metric->featureBar['preview'] as $value => $text)
{
    $isActive = $value == $scope;
    $badge = $isActive && $recTotal != '' ? array('text' => $recTotal, 'class' => 'size-sm rounded-full white') : null;
    $items[] = item
    (
        set::id($value),
        set::text($text),
        set::active($isActive),
        set::badge($badge),
    );
}

nav
(
    set::className('nav-feature nav-ajax'),
    $items,
);

toolbar
(
    btn
    (
        setClass('btn text-black ghost primary-hover-500'),
        set::icon('exchange'),
        set::iconClass('icon-18'),
        set::url(helper::createLink('metric', 'preview', "scope=$scope&viewType=single&metricID={$current->id}")),
        $lang->metric->viewType->single,
    ),
    common::hasPriv('metric', 'preview') ? btn
    (
        setClass('btn primary'),
        set::url(helper::createLink('metric', 'browse')),
        $lang->metric->manage
    ) : null,
);

$metricCheckList = array();
foreach($metrics as $key => $metric)
{
    $class  = $metric->id == $current->id ? 'metric-current' : '';
    $class .= ' font-medium checkbox';
    $metricCheckList[] = array('text' => $metric->name, 'value' => $metric->id, 'typeClass' => $class, 'checked' => $metric->id == $current->id);
}

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
            checkList
            (
                set::className('check-list-metric'),
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
            setClass('checked-content'),
            row
            (
                set::align('center'),
                cell
                (
                    setClass('checked-label-content'),
                    set::flex('auto'),
                    set::align('flex-start'),
                    label
                    (
                        'Circle',
                        setClass('circle gray-pale')
                    ),
                    label
                    (
                        'Circle',
                        setClass('circle gray-pale')
                    ),
                ),
                cell
                (
                    set::width(100),
                    set::flex('none'),
                    span
                    (
                        setClass('checked-tip'),
                        '已选择1项',
                    ),
                ),
            ),
        ),
        div
        (
            setClass('metric-name flex flex-between items-center'),
            div
            (
                span
                (
                    setClass('metric-name-weight'),
                    isset($current) ? $current->name : null,
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
        div
        (
            setClass('table-and-chart'),
            div
            (
                setClass('table-side'),
                div
                (
                    setClass('dtable'),
                )
            ),
            div
            (
                setClass('chart-side chart-center'),
                'chart'
            ),
        ),
    ),
);
render();
