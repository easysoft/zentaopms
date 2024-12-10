<?php
declare(strict_types=1);
namespace zin;

$fnGenerateCustomSearchItem = function ($index, $searchConfig, $form) use ($lang, $config)
{
    list($andor, $field, $operator, $value) = $form;
    $isFirst = $index === 0;

    $searchFields = $searchConfig['fields'];
    $searchParams = $searchConfig['params'];

    $fields     = array_keys($searchFields);
    $field      = empty($field) ? reset($fields) : $field;
    $operator   = empty($operator) ? $searchParams[$field]['operator'] : $operator;
    $control    = $searchParams[$field]['control'];
    $valueItems = $searchParams[$field]['values'];

    $isPicker = false;
    if($control === 'select')
    {
        $isPicker = true;
        $control  = 'picker';
    }

    return formRow
    (
        setClass('flex gap-x-2'),
        setID('row' . $index),
        formGroup
        (
            setID('andor' . $index),
            setClass(array('search-andor', 'hidden' => $isFirst)),
            set::width('22'),
            set::name('andor[]'),
            set::control(array('required' => true)),
            set::items($lang->search->andor),
            set::value($andor)
        ),
        formGroup
        (
            setID('field' . $index),
            setClass('search-field'),
            set::width($isFirst ? '56' : '32'),
            set::label($isFirst ? $lang->doc->customSearch : null),
            set::name("field[]"),
            set::control(array('required' => true)),
            set::items($searchFields),
            set::value($field)
        ),
        formGroup
        (
            setID('operator' . $index),
            setClass('search-operator'),
            set::width('20'),
            set::name("operator[]"),
            set::control(array('required' => true)),
            set::items($lang->search->operators),
            set::value($operator)
        ),
        formGroup
        (
            setID('value' . $index . $control),
            setClass('search-value'),
            set::width('60'),
            set::name("value[]"),
            set::control(array('type' => $control)),
            set::value($value),
            $isPicker ? set::items($valueItems) : null
        ),
        btnGroup
        (
            btn(set(array('type' => 'ghost', 'icon' => 'plus',  'data-index' => $index, 'class' => 'search-add'))),
            btn(set(array('type' => 'ghost', 'icon' => 'minus', 'data-index' => $index, 'class' => array('search-remove', 'hidden' => $isFirst))))
        )
    );
};

$fnGenerateCustomSearch = function ($searchConfig) use ($lang, $config, $settings, $fnGenerateCustomSearchItem)
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
        $items[]  = $fnGenerateCustomSearchItem($index, $searchConfig, array($andor, $field, $operator, $value));
    }

    return div
    (
        setID('customSearchContent'),
        setClass('flex col gap-y-2', array('hidden' => !$isCustom)),
        $items,
        on::click('#customSearchContent .search-add')->do("updateCustomSearchItem(\$this, 'add')"),
        on::click('#customSearchContent .search-remove')->do("updateCustomSearchItem(\$this, 'remove')"),
        on::change('#customSearchContent .search-field')->do("updateCustomSearch(\$this)")
    );
};
