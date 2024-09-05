<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'panel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'checklist' . DS . 'v1.php';

class fieldSelectPanel extends wg
{
    protected static array $defineProps = array(
        'table?: string', // 表名。
        'alias?: string', // 表别名。
        'fields?: array', // 表字段列表。
        'values?: array', // 选中值。
        'col?: int',      // 列数。
        'onChange?: function',
        'onSelectAll?: function'
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function getFieldList(): array
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
        global $lang;
        list($table, $alias, $fields, $values, $col, $onChange, $onSelectAll) = $this->prop(array('table', 'alias', 'fields', 'values', 'col', 'onChange', 'onSelectAll'));

        $isCheckedAll = count($fields) == count($values);
        $panelClass   = $col == 1 ? "w-full" : 'w-1/' . min($col, 6) . '-gap-4';
        $checkClass   = 'checkbox-col-' . max(floor(6 / $col), 1);

        return panel
        (
            setID("selectFields$alias"),
            setClass("h-full $panelClass flex-none"),
            set::title("$table({$alias})"),
            set::headingClass('bg-gray-100 relative'),
            set::bodyClass('h-70 overflow-y-auto'),
            to::heading
            (
                div
                (
                    setClass('absolute right-4 flex gap-x-2'),
                    btn
                    (
                        setClass('p-0 check-all'),
                        set('data-alias', $alias),
                        set('data-checked', $isCheckedAll),
                        set::type('ghost'),
                        $isCheckedAll ? $lang->bi->cancelAll : $lang->bi->checkAll,
                        on::click()->do($onSelectAll)
                    )
                )
            ),
            checkList
            (
                setClass("flex justify-start gap-x-0 checkbox-col $checkClass"),
                set::primary(true),
                set::inline(true),
                set::name($alias),
                set::title('text'),
                set::items($this->getFieldList()),
                on::change()->do($onChange)
            )
        );
    }
}
