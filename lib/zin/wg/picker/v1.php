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
    protected static array $defineProps = array
    (
        'id?: string',                      // 组件根元素的 ID。
        'className?: string|array',         // 类名。
        'style?: array',                    // 样式。
        'tagName?: string',                 // 组件根元素的标签名。
        'attrs?: array',                    // 附加到组件根元素上的属性。
        'clickType?: "toggle"|"open"',      // 点击类型，`toggle` 表示点击按钮时切换显示隐藏，`open` 表示点击按钮时只打。
        'afterRender?: function',           // 渲染完成后的回调函数。
        'beforeDestroy?: function',         // 销毁前的回调函数。
        'name?: string',                    // 作为表单项的名称。
        'defaultValue?: string|string[]',   // 默认值。
        'onChange?: function',              // 值变更回调函数。
        'disabled?: boolean',               // 是否禁用。
        'multiple?: boolean|number=false',  // 是否允许选择多个值，如果指定为数字，则限制多选的数目，默认 `false`。
        'required?: boolean',               // 是否必选（不允许空值，不可以被清除）。
        'placeholder?: string',             // 选择框上的占位文本。
        'valueSplitter?: string',           // 多个值的分隔字符串，默认为 `,`。
        'items: array|function',            // 列表项或表项获取方法。
        'menu?: array',                     // 附加的菜单选项。
        'hotkey?: boolean',                 // 是否启用快捷键。
        'search?: boolean|number',          // 是否启用搜索。
        'searchDelay?: number',             // 搜索延迟时间，单位：毫秒。
        'searchHint?: string',              // 搜索提示文本。
        'onDeselect?: function',            // 当取消选择值时的回调函数。
        'onSelect?: function',              // 当选择值时的回调函数。
        'onClear?: function',               // 当清空值时的回调函数。
        'popContainer?: string',            // 下拉面板容器元素。
        'popWidth: number|"auto"|"100%"',   // 菜单宽度，如果设置为 `'100%'` 则与选择框宽度一致，默认 `'100%'`。
        'popHeight: number|"auto"',         // 菜单高度，默认 `'auto'`。
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
        'onPopHidden?: function',           // 菜单隐藏后的回调函数。
    );

    /**
     * Get picker component properties.
     *
     * @access protected
     * @return array
     */
    protected function getPickerProps():array
    {
        $props = $this->props->toJsonData();
        $items = $props['items'];
        $pickerItems  = array();
        $hasEmptyItem = false;
        if(!empty($items))
        {
            foreach($items as $key => $item)
            {
                if(!is_array($item))           $item = array('text' => $item, 'value' => $key);
                if(!is_string($item['value'])) $item['value'] = strval($item['value']);

                if(empty($item['value'])) $hasEmptyItem = true;
                else                      $pickerItems[] = $item;
            }
        }

        $props['items'] = $pickerItems;
        if(!isset($props['required'])) $props['required'] = !$hasEmptyItem;
        return $props;
    }

    /**
     * Build the widget.
     *
     * @access protected
     * @return wg
     */
    protected function build(): wg
    {
        return zui::picker
        (
            set::_class('form-group-wrapper'),
            set($this->getPickerProps())
        );
    }
}
