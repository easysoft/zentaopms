<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'sqlbuilderpicker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'sqlbuilderinput' . DS . 'v1.php';

class sqlBuilderWhereItem extends wg
{
    protected static array $defineProps = array(
        'index?: string',
        'first?: bool=false',
        'tables?: array',
        'fields?: array',
        'value?: array',
        'onChange?: function',
        'onAdd?: function',
        'onRemove?: function',
        'tableError?:bool=false',
        'fieldError?:bool=false',
        'valueError?:bool=false'
    );

    protected function buildTable()
    {
        global $lang;
        list($index, $items, $value, $onChange, $error) = $this->prop(array('index', 'tables', 'value', 'onChange', 'tableError'));

        $value = $value[0];
        return sqlBuilderPicker
        (
            setClass('ml-4'),
            set::name("0_$index"),
            set::items($items),
            set::value($value),
            set::placeholder($lang->bi->selectTableTip),
            set::width('48'),
            set::onChange($onChange),
            set::error($error)
        );
    }

    protected function buildField()
    {
        global $lang;
        list($index, $items, $value, $onChange, $error) = $this->prop(array('index', 'fields', 'value', 'onChange', 'fieldError'));

        $value = $value[1];
        return sqlBuilderPicker
        (
            set::name("1_$index"),
            set::items($items),
            set::value($value),
            set::placeholder($lang->bi->selectFieldTip),
            set::width('48'),
            set::onChange($onChange),
            set::error($error)
        );
    }

    protected function buildOperator()
    {
        global $lang;
        list($index, $value, $onChange) = $this->prop(array('index', 'value', 'onChange'));

        $value = $value[2];
        return sqlBuilderPicker
        (
            setClass('ml-4'),
            set::name("2_$index"),
            set::items($lang->bi->whereItemOperatorList),
            set::value($value),
            set::required(true),
            set::width('24'),
            set::onChange($onChange)
        );
    }

    protected function buildValue()
    {
        global $lang;
        list($index, $value, $onChange, $error) = $this->prop(array('index', 'value', 'onChange', 'valueError'));

        $value = $value[4];
        return sqlBuilderInput
        (
            setClass('ml-4'),
            set::name("4_$index"),
            set::value($value),
            set::placeholder($lang->bi->selectInputTip),
            set::width('48'),
            set::onChange($onChange),
            set::error($error)
        );
    }

    protected function buildConditionOperator()
    {
        global $lang;
        list($index, $value, $onChange, $isFirst) = $this->prop(array('index', 'value', 'onChange', 'first'));

        $value = $value[5];
        return formGroup
        (
            set::width('20'),
            picker
            (
                setID("builderPicker_5_$index"),
                setClass('builder-picker', array('hidden' => $isFirst)),
                set::name("5_$index"),
                set::value($value),
                set::items($lang->bi->whereOperatorList),
                set::required(true),
                on::change()->do($onChange)
            )
        );
    }

    protected function build()
    {
        list($index, $onAdd, $onRemove) = $this->prop(array('index', 'onAdd', 'onRemove'));
        return formRow
        (
            $this->buildConditionOperator(),
            $this->buildTable(),
            $this->buildField(),
            $this->buildOperator(),
            $this->buildValue(),
            formGroup
            (
                btn
                (
                    setClass('add-where-item'),
                    set('data-index', $index),
                    set::type('ghost'),
                    set::icon('plus'),
                    on::click()->do($onAdd)
                ),
                btn
                (
                    setClass('remove-where-item'),
                    set('data-index', $index),
                    set::type('ghost'),
                    set::icon('minus'),
                    on::click()->do($onRemove)
                )
            )
        );
    }
}
