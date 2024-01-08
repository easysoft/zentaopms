<?php
declare(strict_types=1);
/**
 * The colorPicker widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

/**
 * 颜色选择器（colorPicker）部件类
 * The colorPicker widget class
 */
class colorPicker extends wg
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
        'tagName?: string',                 // 组件根元素的标签名。
        'attrs?: array',                    // 附加到组件根元素上的属性。
        'clickType?: "toggle"|"open"',      // 点击类型，`toggle` 表示点击按钮时切换显示隐藏，`open` 表示点击按钮时只打。
        'afterRender?: function',           // 渲染完成后的回调函数。
        'beforeDestroy?: function',         // 销毁前的回调函数。
        'name?: string',                    // 作为表单项的名称。
        'value?: string|string[]',          // 默认值。
        'onChange?: function',              // 值变更回调函数。
        'disabled?: boolean',               // 是否禁用。
        'multiple?: boolean|number=false',  // 是否允许选择多个值，如果指定为数字，则限制多选的数目，默认 `false`。
        'required?: boolean',               // 是否必选（不允许空值，不可以被清除）。
        'items?: string | string[]',        // 颜色选项列表。
        'icon?: string|array="color"',      // 将触发按钮显示为图标。
        'syncValue?: string',               // 指定选择器同步颜色值作为文本到的元素。
        'syncColor?: string',               // 指定选择器同步文字颜色到的元素。
        'syncBackground?: string',          // 指定选择器同步背景颜色到的元素。
        'syncBorder?: string',              // 指定选择器同步边框颜色到的元素。
        'hint?: string',                    // 提示文字。
        'closeBtn?: boolean',               // 是否在弹出面板上显示关闭按钮。
        'heading?: ComponentChildren'       // 弹出面板的标题。
    );

    /**
     * Build widget.
     *
     * @access protected
     */
    protected function build(): wg
    {
        list($props, $restProps) = $this->props->split(array_keys(static::definedPropsList()));
        if(isset($props['id']))
        {
            $props['_id'] = $props['id'];
            unset($props['id']);
        }

        if(!isset($props['items']))
        {
            global $app, $lang;
            $moduleName = $app->getModuleName();
            if(isset($lang->$moduleName->colorList)) $props['items'] = $lang->$moduleName->colorList;
        }
        return zui::colorPicker
        (
            set::_class('form-group-wrapper center'),
            set::_map(array('value' => 'defaultValue', 'items' => 'colors', 'formID' => 'id')),
            set::_props($restProps),
            set($props),
            $this->children()
        );
    }
}
