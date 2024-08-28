<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'sqlbuilderpicker' . DS . 'v1.php';

class sqlBuilderQueryFilter extends wg
{
    protected static array $defineProps = array(
        'querys?: array',
        'tables?: array',
        'fields?: array'
    );

    protected static array $controls = array(
        'table'   => array('type' => 'picker', 'items' => 'tables', 'width' => 'w-32'),
        'field'   => array('type' => 'picker', 'items' => 'fields', 'width' => 'w-32'),
        'name'    => array('type' => 'input', 'width' => 'w-32'),
        'type'    => array('type' => 'picker', 'items' => 'typeList', 'width' => 'w-56'),
        'default' => array('type' => 'input', 'width' => 'w-32')
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
                setClass('form-header-item font-bold align-middle', $config['width']),
                set::label($text)
            );
        }

        return $headers;
    }

    protected function buildFormGroup($name, $rowValue)
    {
        global $lang;
        list($tables, $fields) = $this->prop(array('tables', 'fields'));
        $fields     = zget($fields, $rowValue['table'], array());
        $typeList   = $lang->dataview->varFilter->requestTypeList;
        $selectList = $lang->dataview->varFilter->selectList;

        $config = static::$controls[$name];
        extract($config);
        if($type == 'picker') $items = $$items;
        $value = $rowValue[$name];

        return formGroup
        (
            setClass($width),
            inputGroup
            (
                setClass('flex justify-start'),
                $type == 'picker' ? picker
                (
                    setClass('flex-auto', array('basis-24' => $name == 'type')),
                    set::name($name),
                    set::items($items),
                    set::disabled(empty($items)),
                    set::value($value)
                ) : null,
                $type == 'input' ? input
                (
                    setClass('flex-auto'),
                    set::name($name),
                    set::value($value)
                ) : null,
                $name == 'type' ? picker
                (
                    setClass('basis-24'),
                    set::name('typeOption'),
                    set::items($selectList),
                    set::value($rowValue['typeOption'])
                ) : null
            )
        );
    }

    protected function buildFormRows()
    {
        list($querys) = $this->prop(array('querys'));
        $formRows = array();
        foreach($querys as $index => $query)
        {
            $items = array();
            $names = array_keys($query);
            foreach($names as $name) if(isset(static::$controls[$name])) $items[] = $this->buildFormGroup($name, $query);

            $items[] = formGroup
            (
                btn
                (
                    setClass('add-query'),
                    set('data-index', $index),
                    set::type('ghost'),
                    set::icon('plus')
                ),
                btn
                (
                    setClass('remove-query'),
                    set('data-index', $index),
                    set::type('ghost'),
                    set::icon('minus')
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
