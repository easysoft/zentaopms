<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'sqlbuilderpicker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formgroup' . DS . 'v1.php';

class joinCondition extends wg
{
    protected static array $defineProps = array(
        'index?: int',
        'name?: string',
        'values?: array',
        'tables?: array',
        'fieldAList?: array',
        'fieldBList?: array',
        'columnAError?: bool=false',
        'fieldAError?: bool=false',
        'fieldBError?: bool=false'

    );

    protected function buildColumnA()
    {
        global $lang;
        list($name, $items, $values, $error) = $this->prop(array('name', 'tables', 'values', 'columnAError'));

        return sqlBuilderPicker
        (
            set::name("{$name}_table"),
            set::label($lang->bi->joinCondition),
            set::items($items),
            set::value($values[0]),
            set::placeholder($lang->bi->selectTableTip),
            set::error($error)
        );
    }

    protected function buildFieldA()
    {
        global $lang;
        list($name, $items, $values, $error) = $this->prop(array('name', 'fieldAList', 'values', 'fieldAError'));

        return sqlBuilderPicker
        (
            set::name("{$name}_fieldA"),
            set::label($lang->bi->of),
            set::items($items),
            set::value($values[1]),
            set::placeholder($lang->bi->selectFieldTip),
            set::labelWidth('40px'),
            set::width('50'),
            set::error($error)
        );
    }

    protected function buildFieldB()
    {
        global $lang;
        list($name, $tables, $items, $values, $error) = $this->prop(array('name', 'tables', 'fieldBList', 'values', 'fieldBError'));
        $columnB = \zget($tables, $values[3]);

        return sqlBuilderPicker
        (
            set::name("{$name}_fieldB"),
            set::label(sprintf($lang->bi->joinTable, $columnB)),
            set::items($items),
            set::value($values[4]),
            set::placeholder($lang->bi->selectFieldTip),
            set::labelWidth('100px'),
            set::error($error)
        );
    }

    protected function build()
    {
        list($index) = $this->prop(array('index'));
        return formGroup
        (
            $this->buildColumnA(),
            $this->buildFieldA(),
            $this->buildFieldB(),
            formGroup
            (
                btn
                (
                    setClass('add-row-join-table'),
                    set('data-index', $index),
                    set::type('ghost'),
                    set::icon('plus')
                ),
                btn
                (
                    setClass('remove-row-join-table'),
                    set('data-index', $index),
                    set::type('ghost'),
                    set::icon('minus')
                )
            )
        );
    }
}
