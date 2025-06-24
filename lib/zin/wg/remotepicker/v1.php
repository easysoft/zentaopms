<?php
namespace zin;

class remotePicker extends wg
{
    protected static array $defineProps = array(
        'label?: string',                   // 控件标签。
        'id?: string',                      // 控件 ID。
        'name?: string="users[]"',          // 控件名称。
        'value?: string',                   // 控件默认值。
        'type?: string="user"',             // 控件类型。
        'params?: string=""',               // 额外参数，如user的pofirst。
        'items?: string|array',             // picker 列表项或列表项获取方法。
        'menu?: array',                     // picker 附加的菜单选项。
        'toolbar?: boolean|array',          // picker 列表工具栏。
        'multiple?: boolean|number=false',  // picker 是否允许选择多个值，如果指定为数字，则限制多选的数目，默认 `false`。
        'inputGroupClass?: string=""'       // inputGroup 的 class 属性。
    );

    protected function created()
    {
        $items = $this->prop('items');
        if(!$items)
        {
            $type   = $this->prop('type');
            $params = $this->prop('params') ? $this->prop('params') : 'noclosed|nodeleted';
            switch($type)
            {
                case 'user':
                    $items = createLink('user', 'ajaxGetItems', 'params=' . $params);
                    break;
            }
            $this->setProp('items', $items);
        }

        if(!$this->prop('id')) $this->setProp('id', '_' . $this->prop('name'));
    }

    protected function build()
    {
        return zui::picker
        (
            set::_class('form-group-wrapper picker-box'),
            set::_map(array('value' => 'defaultValue', 'formID' => 'id')),
            set($this->props->pick(array('id', 'name', 'value', 'items', 'toolbar', 'menu', 'multiple'))),
        );
    }
}