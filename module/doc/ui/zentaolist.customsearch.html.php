<?php
declare(strict_types=1);
namespace zin;

$fnGenerateCustomSearchItem = function ($index, $form) use ($lang, $config)
{
    list($andor, $field, $operator, $value) = $form;
    $isFirst = $index === 0;
    return formRow
    (
        setClass('flex gap-x-2'),
        setID('row' . $index),
        formGroup
        (
            setClass(array('hidden' => $isFirst)),
            set::width('22'),
            setID('andor' . $index),
            set::name('andor[]'),
            set::control(array('required' => true)),
            set::items($lang->search->andor),
            set::value($andor)
        ),
        formGroup
        (
            set::width($isFirst ? '56' : '32'),
            set::label($isFirst ? $lang->doc->customSearch : null),
            setID('field' . $index),
            set::name("field[]"),
            set::items($config->product->search['fields']),
            set::value($field)
        ),
        formGroup
        (
            set::width('20'),
            setID('operator' . $index),
            set::name("operator[]"),
            set::control(array('required' => true)),
            set::items($lang->search->operators),
            set::value($operator)
        ),
        formGroup
        (
            set::width('60'),
            setID('value' . $index),
            set::name("value[]"),
            set::value($value)
        ),
        btnGroup
        (
            btn(set(array('type' => 'ghost', 'icon' => 'plus',  'data-index' => $index, 'class' => 'search-add'))),
            btn(set(array('type' => 'ghost', 'icon' => 'minus', 'data-index' => $index, 'class' => array('search-remove', 'hidden' => $isFirst))))
        )
    );
};

$fnGenerateCustomSearch = function () use ($lang, $config, $settings, $fnGenerateCustomSearchItem)
{
    $this->loadModel('product');
    $this->loadModel('search');
    $items = array();
    $condition = zget($settings, 'condition', '');
    $isCustom  = $condition === 'customSearch';

    foreach($settings['field'] as $index => $field)
    {
        $andor    = $settings['andor'][$index];
        $operator = $settings['operator'][$index];
        $value    = $settings['value'][$index];
        $items[]  = $fnGenerateCustomSearchItem($index, array($andor, $field, $operator, $value));
    }

    return div
    (
        setID('customSearchContent'),
        setClass('flex col gap-y-2', array('hidden' => !$isCustom)),
        $items,
        on::click('#customSearchContent .search-add')->do("updateCustomSearchItem(\$this, 'add')"),
        on::click('#customSearchContent .search-remove')->do("updateCustomSearchItem(\$this, 'remove')")
    );
};
