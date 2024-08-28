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
        'table'   => array('type' => 'picker', 'items' => 'tables'),
        'field'   => array('type' => 'picker', 'items' => 'fields'),
        'name'    => array('type' => 'input'),
        'type'    => array('type' => 'picker', 'items' => 'typeList'),
        'default' => array('type' => 'input')
    );

    protected function buildFormHeader()
    {
        global $lang;
        $headers = array();

        foreach($lang->bi->queryFilterFormHeader as $text)
        {
            $headers[] = div
            (
                setClass('form-header-item font-bold text-center'),
                $text
            )
        }

        return $headers;
    }

    protected function buildFormGroup($name, $rowValue)
    {
        list($tables, $fields) = $this->prop(array('tables', 'fields'));
        $typeList   = $lang->dataview->varFilter->requestTypeList;
        $selectList = $lang->dataview->varFilter->selectList;

        $config = static::$controls[$name];
        extract($config);
        if($type == 'picker') $items = $$items;
        $value = $rowValue;

        return formGroup
        (
            setClass('self-start'),
            div
            (
                setClass('flex justify-between'),
                $type == 'picker' ? picker
                (
                    set::name($name),
                    set::items($items),
                    set::value($value)
                ) : null,
                $type == 'input' ? input
                (
                    set::name($name),
                    set::value($value)
                ) : null,
                $name == 'type' ? picker
                (
                    setClass('w-36'),
                    set::name('typeOption'),
                    set::items($selectList),
                    set::value($rowValue['typeOption'])
                )
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
            foreach($names => $name) $items[] = $this->buildFormGroup($name, $query);

            $fromRows[] = formRow
            (
                setClass('flex form-body justify-between items-end max-h-16 query-filter-row'),
                set('data-index', $index),
                $items
            )
        }
        return $formRows;
    }

    protected function build()
    {
        return formBase
        (
            set::actions(array()),
            div
            (
                setClass('flex form-header justify-between h-10'),
                $this->buildFormHeader()
            )
            $this->buildFormRows()
        );
    }
}
