<?php
declare(strict_types=1);

namespace zin;

$fnGenerateFormRows = function () use ($lang, $settings)
{
    $productList = $this->loadModel('product')->getPairs();
    $planList    = isset($settings['product']) ? $this->loadModel('productplan')->getPairs((int)$settings['product']) : array();
    return array
    (
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('product'),
                set::label($lang->doc->product),
                set::required(),
                set::control(array('contorl' => 'picker', 'required' => false, 'maxItemsCount' => 50)),
                set::items($productList),
                set::value(isset($settings['product']) ? $settings['product'] : ''),
                span
                (
                    setClass('error-tip text-danger hidden'),
                    $lang->doc->emptyError
                )
            )
        ),
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('plan'),
                set::label($lang->doc->plan),
                set::required(),
                set::control(array('contorl' => 'picker', 'required' => false, 'maxItemsCount' => 50)),
                set::items($planList),
                set::value(isset($settings['plan']) ? $settings['plan'] : ''),
                span
                (
                    setClass('error-tip text-danger hidden'),
                    $lang->doc->emptyError
                )
            )
        )
    );
};
