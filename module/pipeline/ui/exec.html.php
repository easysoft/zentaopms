<?php
declare(strict_types=1);
/**
 * The exec view file of pipeline module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     pipeline
 * @link        https://www.zentao.net
 */
namespace zin;
$varList = array();

if(!empty($variables))
{
    foreach($variables as $variable)
    {
        if(!$variable->runtime) continue;

        $varList[] = h::tr
        (
            setClass('tr-row'),
            h::td(set::width(150), setClass('text-center'), $variable->name),
            h::td(set::width(150), setClass('text-center'), $variable->key),
            h::td
            (
                $variable->key == 'gitRef' ?
                picker
                (
                    set::name($variable->key),
                    set::items($branchList),
                    set::value(zget($variable, 'defaultValue', ''))
                ) :
                input
                (
                    set::name($variable->key),
                    set::value(zget($variable, 'defaultValue', ''))
                )
            )
        );
    }
}

formPanel
(
    set::title($lang->pipeline->execTitle),
    on::click('.cancel', 'closeModal'),
    set::actions(array('submit', array('type' => 'cancel', 'text' => $lang->cancel))),
    set::submitBtnText($lang->pipeline->okBtn),
    set::cancelBtnText($lang->cancel),
    formGroup
    (
        set::name(''),
        set::label(''),
        set::control('static'),
        set::width('full'),
        set::labelWidth('32px'),
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
                            $lang->pipeline->varName
                        ),
                        h::th
                        (
                            setClass('nowrap'),
                            'Key'
                        ),
                        h::th
                        (
                            setClass('nowrap'),
                            $lang->pipeline->value
                        )
                    )
                ),
                h::tbody
                (
                    setClass('conditions'),
                    setData('index', count($varList)),
                    $varList
                )
            )
        )
    )
);
