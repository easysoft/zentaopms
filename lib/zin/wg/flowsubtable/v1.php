<?php
declare(strict_types=1);
/**
 * The flowSubTable widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

class flowSubTable extends wg
{
    protected static array $defineProps = [
        'items?: array',
        'value?: array'
    ];

    protected function build()
    {
        global $app;
        $common = $app->loadCommon();
        $common->loadModel('flow');

        list($fields, $dataList) = $this->prop(['items', 'value']);
        if(!$fields) return div('No fields found');

        $module       = current($fields)['module'];
        $dittoControl = ['select', 'multi-select', 'radio', 'checkbox', 'date', 'datetime'];
        $notEmptyRule = $common->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');

        $items = [];
        foreach($fields as $field)
        {
            $field = (object)$field;
            if(!$field->show) continue;

            $items[] = [
                'name'         => "children[{$module}][{$field->field}]",    // 子表的字段可以重名，所以需要加上 module
                'label'        => $field->name,
                'control'      => $common->flow->buildFormControl($field, 'batch'),
                'items'        => array_filter($field->options),
                'width'        => $field->width == 'auto' ? '160px' : $field->width,
                'required'     => $notEmptyRule && strpos(",{$field->layoutRules},", ",{$notEmptyRule->id},") !== false,
                'ditto'        => in_array($field->control, $dittoControl),
                'defaultDitto' => $dataList ? 'off' : 'on'
            ];
        }

        /**
         * 子表的 id 字段在 $fields 中不存在。添加 id 字段用来区分是新增还是编辑。
         * The id field of the sub-table does not exist in $fields. Add the id field to distinguish between new and edit.
         */
        $items[] = [
            'name'  => "children[{$module}][id]",
            'label' => 'id',
            'hidden' => true
        ];

        /**
         * formBatchItem 要求 item 的 name 属性的值和数据的属性必须一致，所以需要将数据的属性名转换为 name 属性
         * The name attribute of the item must be consistent with the data attribute, so the data attribute name needs to be converted to the name attribute
         */
        $rows = [];
        foreach($dataList as $key => $data)
        {
            foreach($fields as $field)
            {
                $field = (object)$field;
                $newKey = "children[{$module}][{$field->field}]";
                $rows[$key][$newKey] = $data->{$field->field};
            }

            $newKey = "children[{$module}][id]";
            $rows[$key][$newKey] = $data->id;
        }

        return formBatch
        (
            set::tagName('div'),
            set::mode('add'),
            set::actions([]),
            set::maxRows($rows ? count($rows) : 1),
            set::items($items),
            set::data(array_values($rows))
        );
    }
}
