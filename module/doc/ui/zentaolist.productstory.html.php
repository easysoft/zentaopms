<?php
declare(strict_types=1);

namespace zin;

$fnGenerateFormRows = function () use ($lang)
{
    $products = $this->loadModel('product')->getPairs();
    return array
    (
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('product'),
                set::label($lang->doc->product),
                set::items($products)
            )
        ),
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('search'),
                set::label($lang->doc->searchCondition),
                set::items(array())
            )
        )
    );
};
