<?php
declare(strict_types=1);

namespace zin;

$fnGenerateFormRows = function ($parsedParams) use ($lang)
{
    $productList = $this->loadModel('product')->getPairs();
    $plans       = isset($parsedParams['product']) ? $this->loadModel('productplan')->getPairs((int)$parsedParams['product'], '', '', true) : array();
    $productID   = isset($parsedParams['product']) ? (int)isset($parsedParams['product']) : '';
    $planID      = isset($parsedParams['plan'])    ? (int)isset($parsedParams['plan'])    : '';
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
                set::items($plans),
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
