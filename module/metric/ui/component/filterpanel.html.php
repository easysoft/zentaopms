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

$fnGenerateFilterBlock = function($code, $filterItem) use($lang)
{
    $panelClass = $filterItem['class'];
    $items      = $filterItem['items'];

    $removeAction = array
    (
        'class' => 'text-primary ghost',
        'text'  => sprintf($lang->metric->filter->clearAction, $lang->metric->filter->$code),
        'onclick' => 'window.handleFilterClearItem(this)'
    );
    return panel
    (
        setClass($panelClass),
        set::headingClass('clear-padding'),
        set::bodyClass('clear-padding'),
        set::title($lang->metric->filter->$code),
        checkList
        (
            set::primary(true),
            set::name($code),
            set::inline(true),
            set::items($items)
        ),
        set::headingActions(array($removeAction))
    );
};

$fnGenerateFilterContent = function($filterItems) use($lang, $fnGenerateFilterBlock)
{
    return li
    (
        btn
        (
            setClass('search-form-toggle rounded-full gray-300-outline size-sm btn filter-btn'),
            icon('search'),
            bind::click('window.handleFilterToggle($element)'),
            span
            (
                setClass('common filter-btn-text'),
                $lang->metric->filter->common
            ),
            span
            (
                setClass('checked'),
            )
        ),
        panel
        (
            setClass('filter-panel hidden'),
            set::footerClass('filter-actions'),
            set::footerActions
            (
                array
                (
                    array('type' => 'primary', 'text' => $lang->metric->filter->common, 'onclick' => 'window.handleFilterClick(this)'),
                    array('type' => 'default', 'text' => $lang->metric->filter->clear, 'onclick' => 'window.handleFilterClearAll(this)')
                )
            ),
            $fnGenerateFilterBlock('scope',   $filterItems['scope']),
            $fnGenerateFilterBlock('object',  $filterItems['object']),
            $fnGenerateFilterBlock('purpose', $filterItems['purpose'])
        )
    );
};
