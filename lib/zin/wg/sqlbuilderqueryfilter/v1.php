<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'sqlbuilderpicker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'sqlbuilderinput' . DS . 'v1.php';

class sqlBuilderQueryFilter extends wg
{
    protected static array $defineProps = array(
        'querys?: array',
        'tables?: array',
        'fields?: array',
        'defaultItems?: array|string',
        'onChange?: function',
        'onAdd?: function',
        'onRemove?: function',
        'error?: array'
    );

    protected static array $controls = array(
        'table'   => array('type' => 'picker', 'items' => 'tables', 'width' => '32'),
        'field'   => array('type' => 'picker', 'items' => 'fields', 'width' => '32'),
        'name'    => array('type' => 'input', 'width' => '32'),
        'type'    => array('type' => 'picker', 'items' => 'typeList', 'width' => '56'),
        'default' => array('type' => 'input', 'width' => '32')
    );

    protected function buildFormHeader()
    {
        global $lang;
        $headers = array();

        foreach($lang->bi->queryFilterFormHeader as $name => $text)
        {
            $config = static::$controls[$name];
            $headers[] = formGroup
            (
                setClass('form-header-item font-bold align-middle'),
                set::width($config['width']),
                set::label($text)
            );
        }

        return $headers;
    }

    protected function buildFormGroup($index, $name, $rowValue)
    {
        global $lang;
        list($tables, $fields, $defaultItems, $onChange, $error) = $this->prop(array('tables', 'fields', 'defaultItems', 'onChange', 'error'));
        $fields     = \zget($fields, $rowValue['table'], array());
        $typeList   = $lang->dataview->varFilter->requestTypeList;
        $selectList = $lang->dataview->varFilter->selectList;

        $config = static::$controls[$name];
        extract($config);

        $items    = $type == 'picker' ? $$items : array();
        $value    = $rowValue[$name];
        $isSelect = $name == 'type' && strpos($value, 'select') !== false;
        $errorKey = "query_{$name}_{$index}";

        if($isSelect) $width = (string)(int)$width / 2;

        if($name == 'default')
        {
            $type = $rowValue['type'];
            if($type == 'select' || $type == 'multipleselect') $type = 'picker';
            $items = \zget($defaultItems, $rowValue['typeOption'], array());
        }

        return div
        (
            setClass('flex row'),
            sqlBuilderControl
            (
                set::type($type),
                set::name("{$name}_{$index}"),
                set::items($items),
                set::width($width),
                set::value($value),
                set::required($name == 'type'),
                set::error(isset($error[$errorKey])),
                set::onChange($onChange),
                set::multiple($name == 'default' && $rowValue['type'] == 'multipleselect')
            ),
            $isSelect ? sqlBuilderPicker
            (
                set::name("typeOption_$index"),
                set::items($selectList),
                set::width($width),
                set::value($rowValue['typeOption']),
                set::required(true),
                set::onChange($onChange)
            ) : null
        );
    }

    protected function buildFormRows()
    {
        list($querys, $onAdd, $onRemove) = $this->prop(array('querys', 'onAdd', 'onRemove'));
        $formRows = array();
        foreach($querys as $index => $query)
        {
            $items = array();
            $names = array_keys($query);
            foreach($names as $name) if(isset(static::$controls[$name])) $items[] = $this->buildFormGroup($index, $name, $query);

            $items[] = formGroup
            (
                btn
                (
                    setClass('add-query'),
                    set('data-index', $index),
                    set::type('ghost'),
                    set::icon('plus'),
                    on::click()->do($onAdd)
                ),
                btn
                (
                    setClass('remove-query'),
                    set('data-index', $index),
                    set::type('ghost'),
                    set::icon('minus'),
                    on::click()->do($onRemove)
                )
            );

            $formRows[] = formRow
            (
                setClass('flex form-body justify-start items-end max-h-16 query-filter-row gap-x-4'),
                set('data-index', $index),
                $items
            );
        }
        return $formRows;
    }

    protected function build()
    {
        return div
        (
            setClass('gap-4 flex col'),
            formRow
            (
                setClass('flex form-header justify-start h-10 gap-x-4 bg-gray-100 items-center'),
                $this->buildFormHeader()
            ),
            $this->buildFormRows()
        );
    }
}
