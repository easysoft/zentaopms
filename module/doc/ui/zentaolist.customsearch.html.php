<?php
declare(strict_types=1);
namespace zin;

$fnGenerateCustomSearchItem = function ($index, $form) use ($lang, $config)
{
    list($andor, $field, $operator, $value) = $form;
    return formRow
    (
        setClass('flex gap-x-2'),
        $index !== 0 ? formGroup
        (
            set::width('22'),
            set::name('andor'),
            set::control(array('required' => true)),
            set::items($lang->search->andor),
            set::value($andor)
        ) : null,
        formGroup
        (
            set::width($index === 0 ? '56' : '32'),
            set::label($index === 0 ? $lang->doc->customSearch : null),
            set::name("field$index"),
            set::items($config->product->search['fields']),
            set::value($field)
        ),
        formGroup
        (
            set::width('20'),
            set::name("operator$index"),
            set::control(array('required' => true)),
            set::items($lang->search->operators),
            set::value($operator)
        ),
        formGroup
        (
            set::width('60'),
            set::name("value$index"),
            set::value($value)
        ),
        btnGroup
        (
            btn(set(array('type' => 'ghost', 'icon' => 'plus',  'class' => 'search-add'))),
            btn(set(array('type' => 'ghost', 'icon' => 'minus', 'class' => array('search-remove', 'hidden' => $index == 0))))
        )
    );
};

$fnGenerateCustomSearch = function () use ($lang, $config, $settings, $fnGenerateCustomSearchItem)
{
    $this->loadModel('product');
    $this->loadModel('search');
    $items = array();
    return null;

    return div
    (
        setID('customSearchContent'),
        setClass('flex col gap-y-2'),
        set('data-searchform', $searchForm),
        $items,
        on::click('#customSearchContent .search-add', "addCustomSearchItem"),
        on::click('#customSearchContent .search-remove', "removeCustomSearchItem")
    );
};
