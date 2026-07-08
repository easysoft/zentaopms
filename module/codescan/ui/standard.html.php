<?php
declare(strict_types=1);
/**
 * The standard view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('numberUnit', $lang->codescan->pcs);

unset($lang->codescan->severityList['all']);
unset($lang->codescan->typeList['all']);

$standardList = array();
if(!empty($conditions))
{
    foreach($conditions as $index => $condition)
    {
        $standardList[] = h::tr
            (
                setClass('tr-row'),
                h::td
                (
                    picker
                    (
                        set::width(80),
                        set::name("severity[{$index}]"),
                        set::value(empty($condition->priority) || $condition->priority == -1 ? 'all' : $condition->priority),
                        set::items($lang->codescan->severityList)
                    )
                ),
                h::td
                (
                    picker
                    (
                        set::width(100),
                        set::name("type[{$index}]"),
                        set::value(empty($condition->type) ? 'all' : $condition->type),
                        set::items($lang->codescan->typeList)
                    )
                ),
                h::td
                (
                    picker
                    (
                        set::width(100),
                        set::name("metric[{$index}]"),
                        set::value($condition->unit),
                        set::items($lang->codescan->metricList)
                    )
                ),
                h::td
                (
                    setClass('text-center'),
                    '<='
                ),
                h::td
                (
                    inputControl
                    (
                        input
                        (
                            set::name("threshold[{$index}]"),
                            set::type('number'),
                            set::value($condition->threshold),
                            set::min(0)
                        ),
                        to::suffix($condition->unit == 'percent' ? '%' : $lang->codescan->pcs),
                        set::suffixWidth(30)
                    )
                ),
                h::td
                (
                    setClass('text-center'),
                    btn
                    (
                        set::icon('trash'),
                        set::type('ghost del-item'),
                    )
                )
            );
    }
}

$metricList = array();
foreach($lang->codescan->metricList as $code => $text) $metricList[] = array('value' => $code, 'text' => $text, 'key' => "$code $text");

$typeList = array();
foreach($lang->codescan->typeList as $code => $text) $typeList[] = array('value' => $code, 'text' => $text, 'key' => "$code $text");

$severityList = array();
foreach($lang->codescan->severityList as $code => $text) $severityList[] = array('value' => $code, 'text' => $text, 'key' => "$code $text");

jsVar('metricList', $metricList);
jsVar('typeList', $typeList);
jsVar('severityList', $severityList);

h::table
(
    setClass('hidden'),
    setID('conditionTpl'),
    h::tr
    (
        setClass('tr-row'),
        h::td(div(setClass('form-group-wrapper picker-box'), setID('severity%index%'))),
        h::td(div(setClass('form-group-wrapper picker-box'), setID('type%index%'))),
        h::td(div(setClass('form-group-wrapper picker-box'), setID('metric%index%'))),
        h::td(setClass('text-center'), '<='),
        h::td
        (
            inputControl
            (
                input
                (
                    set::name('threshold[%index%]'),
                    set::type('number'),
                    set::value(''),
                    set::min(0)
                ),
                to::suffix($lang->codescan->pcs),
                set::suffixWidth(30)
            )
        ),
        h::td(setClass('text-center'), btn(set::icon('trash'), set::type('ghost del-item')))
    )
);

$standardBlock = formGroup
(
    set::name(''),
    set::label(''),
    set::control('static'),
    set::width(isInModal() ? 'full' : '2/3'),
    set::labelWidth(isInModal() ? '32px' : ''),
    div
    (
        setClass('w-full'),
        h::table
        (
            setClass('table bordered'),
            on::click('.del-item')->call('removeCondition', jsRaw('event')),
            on::change('[name^=metric]')->call('changeMetric', jsRaw('this')),
            h::thead
            (
                h::tr
                (
                    h::th
                    (
                        setClass('nowrap'),
                        $lang->codescan->severity
                    ),
                    h::th
                    (
                        setClass('nowrap'),
                        $lang->codescan->type
                    ),
                    h::th
                    (
                        setClass('nowrap'),
                        $lang->codescan->metric
                    ),
                    h::th
                    (
                        setClass('nowrap'),
                        $lang->codescan->logic
                    ),
                    h::th
                    (
                        setClass('nowrap'),
                        $lang->codescan->threshold
                    ),
                    h::th
                    (
                        setClass('nowrap'),
                        $lang->actions
                    )
                )
            ),
            h::tbody
            (
                setClass('conditions'),
                setData('index', count($standardList)),
                $standardList
            )
        ),
        btn
        (
            on::click()->call('addCondition'),
            set::icon('plus'),
            set::type('primary mt-4'),
            set::text($lang->codescan->addCondition)
        )
    )
);
