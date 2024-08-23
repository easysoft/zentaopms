<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'sqlbuilderpicker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'sqlbuilderinput' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formrow' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formgroup' . DS . 'v1.php';

class sqlBuilderFuncRow extends wg
{
    protected static array $defineProps = array(
        'index?: int',
        'tables?: array',
        'fields?: array',
        'value?: array',
        'tableError?:bool=false',
        'fieldError?:bool=false',
        'functionError?:bool=false',
        'aliasError?:bool=false'
    );

    protected function buildTable()
    {
        global $lang;
        list($index, $items, $value, $error) = $this->prop(array('index', 'tables', 'value', 'tableError'));

        $value = $value['table'];
        return sqlBuilderPicker
        (
            set::name("table_$index"),
            set::label($lang->bi->do),
            set::items($items),
            set::value($value),
            set::placeholder($lang->bi->selectTableTip),
            set::width('48'),
            set::labelWidth('30px'),
            set::error($error)
        );
    }

    protected function buildField()
    {
        global $lang;
        list($index, $items, $value, $error) = $this->prop(array('index', 'fields', 'value', 'fieldError'));

        $value = $value['field'];
        return sqlBuilderPicker
        (
            set::name("field_$index"),
            set::items($items),
            set::value($value),
            set::placeholder($lang->bi->selectFieldTip),
            set::width('40'),
            set::error($error)
        );
    }

    protected function buildFunction()
    {
        global $lang;
        list($index, $value, $error) = $this->prop(array('index', 'value', 'functionError'));

        $value = $value['function'];
        return sqlBuilderPicker
        (
            set::name("function_$index"),
            set::label($lang->bi->set),
            set::items($lang->bi->funcList),
            set::value($value),
            set::placeholder($lang->bi->selectFuncTip),
            set::error($error)
        );
    }

    protected function buildAlias()
    {
        global $lang;
        list($index, $value, $error) = $this->prop(array('index', 'value', 'aliasError'));

        $value = $value['alias'];
        return sqlBuilderInput
        (
            set::name("alias_$index"),
            set::label($lang->bi->funcAs),
            set::value($value),
            set::placeholder($lang->bi->selectInputTip),
            set::width('80'),
            set::labelWidth('160px'),
            set::error($error)
        );
    }

    protected function build()
    {
        list($index) = $this->prop(array('index'));
        return formRow
        (
            setClass('mb-4'),
            $this->buildTable(),
            $this->buildField(),
            $this->buildFunction(),
            $this->buildAlias(),
            formGroup
            (
                btn
                (
                    setClass('add-function'),
                    set('data-index', $index),
                    set::type('ghost'),
                    set::icon('plus')
                ),
                btn
                (
                    setClass('remove-function'),
                    set('data-index', $index),
                    set::type('ghost'),
                    set::icon('minus')
                )
            )
        );
    }
}
