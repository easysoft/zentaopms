<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';

class datetimePicker extends wg
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
        'icon?: string|array="calendar"',   // 在输入框右侧显示的图标。
        'weekNames?: string[]',             // 星期名称，索引为 0 表示周日。
        'monthNames?: string[]',            // 月份名称，索引为 0 表示一月份。
        'yearText?: string',                // 用于显示年份的格式化文本。
        'todayText?: string',               // 用于显示“今天”的文本。
        'clearText?: string',               // 用于显示“清除”的文本。
        'weekStart?: int',                  // 一周从星期几开始，默认 1。
        'minDate?: string|int',             // 最小可选的日期。
        'maxDate?: string|int',             // 最大可选的日期。
        'menu?: array',                     // 左侧显示的菜单设置。
        'actions?: array',                  // 底部工具栏设置。
        'onInvalid?: function',             // 日期值无效时的回调函数。
        'dateFormat?: string',              // 日期格式，默认 yyyy-MM-dd。
        'timeFormat?: string',              // 时间格式，默认 hh:mm
        'joiner?: string'                   // 日期与时间的连接符，默认为单个空格
    );

    /**
     * Build the widget.
     *
     * @access protected
     * @return wg
     */
    protected function build(): wg
    {
        list($props, $restProps) = $this->props->split(array_keys(static::definedPropsList()));
        if(isset($props['id']))
        {
            $props['_id'] = $props['id'];
            unset($props['id']);
        }

        return zui::datetimePicker
        (
            set::_class('form-group-wrapper'),
            set::_map(array('value' => 'defaultValue', 'formID' => 'id')),
            set($props),
            set::_props($restProps),
            $this->children(),
        );
    }
}
