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

$fnGenerateQueryForm = function($viewType) use($metricRecordType, $current, $dateLabels, $defaultDate)
{
    if(!$metricRecordType) return null;
    $formGroups = array();
    if($current->scope != 'system') $objectPairs = $this->metric->getPairsByScope($current->scope);

    if($metricRecordType == 'scope' || $metricRecordType == 'scope-date')
    {
        $name = $viewType == 'single' ? 'scope' : "scope_{$current->id}";
        $formGroups[] = formGroup
        (
            setClass('query-inline picker-nowrap w-40'),
            set::name($name),
            set::control(array('control' => 'picker', 'multiple' => true)),
            set::items($objectPairs),
            set::placeholder($this->lang->metric->placeholder->{$current->scope})
        );
    }

    if($metricRecordType == 'scope' || $metricRecordType == 'system')
    {
        $btnLabels = array();
        foreach($this->lang->metric->query->dayLabels as $key => $label)
        {
            $active = $key == '7' ? ' selected' : '';
            $btnLabels[] = btn
            (
                setClass("$active default w-16 p-0"),
                set::key($key),
                $label
            );
        }
        $formGroups[] = formGroup
        (
            setClass('query-calc-date query-inline w-64'),
            btngroup
            (
                $btnLabels
            ),
            on::click('.query-calc-date button.btn', 'window.handleCalcDateClick(target)'),
        );
    }

    if($metricRecordType == 'date' || $metricRecordType == 'scope-date')
    {
        $btnLabels = array();
        foreach($dateLabels as $key => $label)
        {
            $active = $key == $defaultDate ? ' selected' : '';
            $btnLabels[] = btn
            (
                setClass("$active default w-16 p-0"),
                set::key($key),
                $label
            );
        }
        $formGroups[] = formGroup
        (
            setClass('query-date query-inline w-64'),
            btngroup
            (
                $btnLabels
            ),
            on::click('.query-date button.btn', 'window.handleDateLabelClick(target)'),
        );

        $beginID = $viewType == 'single' ? 'dateBegin' : 'dateBegin' . $current->id;
        $endID   = $viewType == 'single' ? 'dateEnd' : 'dateEnd' . $current->id;

        $formGroups[] = formGroup
        (
            setClass('query-inline w-80'),
            inputGroup
            (
                datePicker
                (
                    setClass('query-date-picker'),
                    set::name('dateBegin'),
                    set('id', $beginID),
                    set::placeholder($this->lang->metric->placeholder->select)
                ),
                $this->lang->metric->to,
                datePicker
                (
                    setClass('query-date-picker'),
                    set::name('dateEnd'),
                    set('id', $endID),
                    set::placeholder($this->lang->metric->placeholder->select)
                )
            ),
            on::change('.query-date-picker', 'window.handleDatePickerChange(target)'),
        );
    }

    return form
    (
        set::id('queryForm' . $current->id),
        setClass('ml-4'),
        formRow
        (
            set::width('max'),
            $formGroups,
            !empty($formGroups) ? formGroup
            (
                setClass('query-btn'),
                btn
                (
                    setClass('btn secondary'),
                    set::text($this->lang->metric->query->action),
                    set::onclick("window.handleQueryClick($current->id, '$viewType')")
                )
            ) : null
        ),
        set::actions(array())
    );
};
