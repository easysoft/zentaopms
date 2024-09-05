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
        'index?: int',                // 索引。
        'tables?: array',             // 表名下拉数据。
        'fields?: array',             // 字段名下拉数据。
        'value?: array',              // 值。
        'onChange?: function',
        'onAdd?: function',
        'onRemove?: function',
        'tableError?:bool=false',     // 表名选择是否存在错误。
        'fieldError?:bool=false',     // 字段名选择是否存在错误。
        'functionError?:bool=false',  // 函数名选择是否存在错误。
        'aliasError?:bool=false',      // 别名是否存在错误。
        'duplicateError?:bool=false' // 别名是否存在重复。
    );

    protected function buildTable(): node
    {
        global $lang;
        list($index, $items, $value, $onChange, $error) = $this->prop(array('index', 'tables', 'value', 'onChange', 'tableError'));

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
            set::onChange($onChange),
            set::error($error)
        );
    }

    protected function buildField(): node
    {
        global $lang;
        list($index, $items, $value, $onChange, $error) = $this->prop(array('index', 'fields', 'value', 'onChange', 'fieldError'));

        $value = $value['field'];
        return sqlBuilderPicker
        (
            set::name("field_$index"),
            set::items($items),
            set::value($value),
            set::placeholder($lang->bi->selectFieldTip),
            set::width('40'),
            set::onChange($onChange),
            set::error($error)
        );
    }

    protected function buildFunction(): node
    {
        global $lang;
        list($index, $value, $onChange, $error) = $this->prop(array('index', 'value', 'onChange', 'functionError'));

        $value = $value['function'];
        return sqlBuilderPicker
        (
            set::name("function_$index"),
            set::label($lang->bi->set),
            set::items($lang->bi->funcList),
            set::value($value),
            set::placeholder($lang->bi->selectFuncTip),
            set::onChange($onChange),
            set::error($error)
        );
    }

    protected function buildAlias(): node
    {
        global $lang;
        list($index, $value, $onChange, $emptyError, $duplicateError) = $this->prop(array('index', 'value', 'onChange', 'aliasError', 'duplicateError'));

        $value = $value['alias'];
        $errorText = $duplicateError ? $lang->bi->duplicateError : null;
        return sqlBuilderInput
        (
            set::name("alias_$index"),
            set::label($lang->bi->funcAs),
            set::value($value),
            set::placeholder($lang->bi->selectInputTip),
            set::width('80'),
            set::labelWidth('160px'),
            set::onChange($onChange),
            set::error($emptyError || $duplicateError),
            set::errorText($errorText)
        );
    }

    protected function build()
    {
        list($index, $onAdd, $onRemove) = $this->prop(array('index', 'onAdd', 'onRemove'));
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
                    set::icon('plus'),
                    on::click()->do($onAdd)
                ),
                btn
                (
                    setClass('remove-function'),
                    set('data-index', $index),
                    set::type('ghost'),
                    set::icon('minus'),
                    on::click()->do($onRemove)
                )
            )
        );
    }
}
