<?php
declare(strict_types=1);

namespace zin;

$fnGenerateFormRows = function () use ($lang, $settings)
{
    $productList = $this->loadModel('product')->getPairs();
    $this->app->loadLang('testcase');
    $conditions = array_filter($lang->testcase->featureBar['browse'], fn($value) => $value !== '-');
    $conditions['customSearch'] = $lang->doc->customSearch;
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
                set::name('condition'),
                set::label($lang->doc->searchCondition),
                set::required(),
                set::control(array('contorl' => 'picker', 'required' => false, 'maxItemsCount' => 50)),
                set::items($conditions),
                set::value(isset($settings['condition']) ? $settings['condition'] : ''),
                span
                (
                    setClass('error-tip text-danger hidden'),
                    $lang->doc->emptyError
                )
            )
        )
    );
};
