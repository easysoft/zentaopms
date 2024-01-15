<?php
declare(strict_types=1);
/**
 * The picker widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

/**
 * 下拉选择器（picker）部件类。
 * The picker widget class.
 *
 * @author Hao Sun
 */
class picker extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array(
        'id?: string="$GID"',               // 组件根元素的 ID。
        'formID?: string',                  // 组件隐藏的表单元素 ID。
        'className?: string|array',         // 类名。
        'style?: array',                    // 样式。
        'width?: string|number',            // 宽度。
        'boxClass?: string|array',          // 根节点类名。
        'boxStyle?: string|array',          // 根节点样式。
        'tagName?: string',                 // 组件根元素的标签名。
        'attrs?: array',                    // 附加到组件根元素上的属性。
        'clickType?: "toggle"|"open"',      // 点击类型，`toggle` 表示点击按钮时切换显示隐藏，`open` 表示点击按钮时只打。
        'afterRender?: function',           // 渲染完成后的回调函数。
        'beforeDestroy?: function',         // 销毁前的回调函数。
        'name?: string',                    // 作为表单项的名称。
        'value?: string|string[]',          // 默认值。
        'emptyValue?: string',              // 允许的空值，使用逗号分隔多个允许的空值。
        'onChange?: function',              // 值变更回调函数。
        'disabled?: boolean',               // 是否禁用。
        'readonly?: boolean',               // 是否只读。
        'multiple?: boolean|number=false',  // 是否允许选择多个值，如果指定为数字，则限制多选的数目，默认 `false`。
        'toolbar?: boolean|array',          // 设置工具栏。
        'required?: boolean',               // 是否必选（不允许空值，不可以被清除）。
        'placeholder?: string',             // 选择框上的占位文本。
        'valueSplitter?: string',           // 多个值的分隔字符串，默认为 `,`。
        'items: string|array|function',     // 列表项或表项获取方法。
        'menu?: array',                     // 附加的菜单选项。
        'hotkey?: boolean',                 // 是否启用快捷键。
        'search?: boolean|number',          // 是否启用搜索。
        'searchDelay?: number',             // 搜索延迟时间，单位：毫秒。
        'searchHint?: string',              // 搜索提示文本。
        'onDeselect?: function',            // 当取消选择值时的回调函数。
        'onSelect?: function',              // 当选择值时的回调函数。
        'onClear?: function',               // 当清空值时的回调函数。
        'popContainer?: string',            // 下拉面板容器元素。
        'popWidth?: number|"auto"|"100%"',  // 菜单宽度，如果设置为 `'100%'` 则与选择框宽度一致，默认 `'100%'`。
        'popHeight?: number|"auto"',        // 菜单高度，默认 `'auto'`。
        'popMaxHeight?: number',            // 菜单最大高度，默认 `300`。
        'popMinHeight?: number',            // 菜单最小高度，默认 `32`。
        'popMaxWidth?: number',             // 菜单最大宽度，当宽度设置为 `'auto'` 时生效。
        'popMinWidth?: number',             // 菜单最小宽度，当宽度设置为 `'auto'` 时生效，默认 50。
        'popPlacement?: "auto"|"bottom"|"top"|"bottom-start"|"top-end"',  // 菜单方向，默认 `'auto'`。
        'popClass?: string|array',          // 菜单类名。
        'popStyle?: array',                 // 菜单样式。
        'onPopShow?: function',             // 菜单显示时的回调函数。
        'onPopShown?: function',            // 菜单显示后的回调函数。
        'onPopHide?: function',             // 菜单隐藏时的回调函数。
        'onPopHidden?: function'            // 菜单隐藏后的回调函数。
    );

    /**
     * Get picker component properties.
     *
     * @access protected
     * @return array
     */
    protected function getPickerProps(): array
    {
        list($pickerProps, $restProps) = $this->props->split(array_keys(static::definedPropsList()));
        $items = $pickerProps['items'];
        $pickerItems  = is_array($items) ? array() : $items;
        $hasZeroValue = false;
        $defaultValue = isset($pickerProps['value']) ? $pickerProps['value'] : (isset($pickerProps['defaultValue']) ? $pickerProps['defaultValue'] : '');
        if(is_array($defaultValue)) $defaultValue = implode($this->prop('valueSplitter', ','), $defaultValue);
        if(is_array($items) && !empty($items))
        {
            foreach($items as $key => $item)
            {
                if(!is_array($item))           $item = array('text' => $item, 'value' => $key);
                if(!is_string($item['value'])) $item['value'] = strval($item['value']);

                if($item['value'] === '0') $hasZeroValue  = true;
                $pickerItems[] = $item;
            }
        }

        if(!isset($pickerProps['emptyValue'])) $pickerProps['emptyValue'] = ($hasZeroValue || "$defaultValue" !== '0') ? '' : '0,';

        if(isset($pickerProps['id']))
        {
            $pickerProps['_id'] = $pickerProps['id'];
            unset($pickerProps['id']);
        }
        else
        {
            $pickerProps['_id'] = $this->gid;
        }

        if(!isset($pickerProps['style'])) $pickerProps['style'] = array();
        if(!isset($pickerProps['class'])) $pickerProps['class'] = array();
        if(isset($pickerProps['width']))
        {
            $width = $pickerProps['width'];
            unset($pickerProps['width']);

            if(is_numeric($width))               $restProps['style']['width'] = "{$width}px";
            elseif(str_ends_with($width, 'px'))  $restProps['style']['width'] = $width;
            else                                 $restProps['class'][]        = "w-$width";
        }
        if(isset($pickerProps['boxStyle']))
        {
            $restProps['style'] = array_merge($restProps['style'], $pickerProps['boxStyle']);
            unset($pickerProps['boxStyle']);
        }
        if(isset($pickerProps['boxClass']))
        {
            $restProps['class'][] = $pickerProps['boxClass'];
            unset($pickerProps['class']);
        }

        $pickerProps['_props']        = $restProps;
        $pickerProps['items']         = $pickerItems;
        $pickerProps['defaultValue']  = $defaultValue;

        return $pickerProps;
    }

    /**
     * Build the widget.
     *
     * @access protected
     * @return wg
     */
    protected function build(): zui
    {
        return zui::picker
        (
            set::_class('form-group-wrapper picker-box'),
            set::_map(array('value' => 'defaultValue', 'formID' => 'id')),
            set($this->getPickerProps()),
            $this->children()
        );
    }
}
