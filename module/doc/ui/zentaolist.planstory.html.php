<?php
declare(strict_types=1);

namespace zin;

$fnGenerateFormRows = function () use ($lang, $settings)
{
    $productList = $this->loadModel('product')->getPairs();
    $productID   = isset($settings['product']) ? (int)$settings['product'] : 0;
    $planList    = $productID ? $this->loadModel('productplan')->getPairs($productID, '', '', true) : array();
    $planID      = isset($settings['plan']) ? (int)$settings['plan'] : 0;

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
                set::value($productID),
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
                set::value($planID),
                span
                (
                    setClass('error-tip text-danger hidden'),
                    $lang->doc->emptyError
                )
            )
        )
    );
};
