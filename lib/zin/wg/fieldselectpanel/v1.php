<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'panel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'checklist' . DS . 'v1.php';

class fieldSelectPanel extends wg
{
    protected static array $defineProps = array(
        'table?: string',
        'alias?: string',
        'fields?: array',
        'values?: array',
        'col?: int'
    );

    protected function getFieldList()
    {
        list($alias, $fields, $values) = $this->prop(array('alias', 'fields', 'values'));

        $fieldList = array();
        foreach($fields as $key => $text)
        {
            $field = array('text' => "$text({$key})", 'value' => $key, 'data-alias' => $alias);
            $field['checked'] = in_array($key, $values);
            $field['class']   = 'select-field-checkbox';
            $fieldList[] = $field;
        }

        return $fieldList;
    }

    protected function build()
    {
        list($table, $alias, $fields, $values, $col) = $this->prop(array('table', 'alias', 'fields', 'values', 'col'));

        $panelClass = $col == 1 ? "w-full" : 'w-1/' . min($col, 6) . '-gap-4';
        $checkClass = 'checkbox-col-' . max(floor(6 / $col), 1);

        return panel
        (
            setID("selectFields$alias"),
            setClass("h-full $panelClass flex-none"),
            set::title("$table({$alias})"),
            set::headingClass('bg-gray-100 relative'),
            set::bodyClass('h-64 overflow-y-auto'),
            /*to::heading
            (
                div
                (
                    setClass('absolute right-4'),
                    checkbox
                    (
                        setClass('select-field-checkbox'),
                        set::name("{$alias}all"),
                        set::text($lang->bi->allFields),
                        set::value('*'),
                        set('data-alias', $alias)
                    )
                )
            ),*/
            checkList
            (
                setClass("flex justify-start gap-x-0 checkbox-col $checkClass"),
                set::primary(true),
                set::inline(true),
                set::name($alias),
                set::title('text'),
                set::items($this->getFieldList())
            )
        );
    }
}
