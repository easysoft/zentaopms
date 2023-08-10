<?php
declare(strict_types=1);
/**
 * The timePicker widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';

/**
 * 日期选择器（timePicker）部件类
 * The timePicker widget class
 */
class timePicker extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array
    (
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
        'placeholder?: string',             // 选择框上的占位文本。
        'format?: string',                  // 日期格式，默认 hh:mm。
        'icon?: string|array="time"',       // 在输入框右侧显示的图标。
        'onInvalid?: function',             // 日期值无效时的回调函数。
    );
    /**
     * Build the widget.
     *
     * @access protected
     * @return wg
     */
    protected function build(): zui
    {
        list($props, $restProps) = $this->props->split(array_keys(static::definedPropsList()));
        if(isset($props['id']))
        {
            $props['_id'] = $props['id'];
            unset($props['id']);
        }

        return zui::timePicker
        (
            set::_class('form-group-wrapper'),
            set::_map(array('value' => 'defaultValue', 'formID' => 'id')),
            set($props),
            set::_props($restProps),
            $this->children(),
        );
    }
}
