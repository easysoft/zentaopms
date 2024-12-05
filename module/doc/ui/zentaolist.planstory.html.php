<?php
declare(strict_types=1);

namespace zin;

$fnGenerateFormRows = function () use ($lang)
{
    $productList = $this->loadModel('product')->getPairs();
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
                set::items($productList)
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
                set::items(array()),
            )
        )
    );
};
