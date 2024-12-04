<?php
declare(strict_types=1);

namespace zin;

$fnGenerateFormRows = function () use ($lang)
{
    $productList = $this->loadModel('product')->getPairs();
    $product     = current(array_keys($productList));
    $planList    = $this->loadModel('productplan')->getPairs($product, '', '', true);
    $plan        = current(array_keys($planList));
    return array
    (
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('product'),
                set::label($lang->doc->product),
                set::control(array('contorl' => 'picker', 'required' => true, 'maxItemsCount' => 50)),
                set::items($productList),
                set::value($product)
            )
        ),
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('plan'),
                set::label($lang->doc->plan),
                set::control(array('contorl' => 'picker', 'required' => true, 'maxItemsCount' => 50)),
                set::items($planList),
                set::value($plan)
            )
        )
    );
};
