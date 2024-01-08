<?php
declare(strict_types=1);
/**
 * The formBatch widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'control' . DS . 'v1.php';

/**
 * 批量编辑表单项（formBatchItem）部件类。
 * The batch edit form item widget class.
 *
 * @author Hao Sun
 */
class formBatchItem extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array(
        'name: string',                 // 表单项名称，无需包含 `[]`。
        'label: string|bool',           // 列标题。
        'labelClass?: string',          // 列标题类名。
        'labelProps?: string',          // 列标题属性，例如 `array('data-toggle' => 'tooltip', 'data-title' 。=> 'This is a tip')`
        'required?:bool|string="auto"', // 是否必填，如果设置为 `"auto"`，则自动从当前模块 config 中查询。
        'control?: array|string|false', // 控件类型或控件配置。
        'width?: number|string',        // 列宽度，如果设置为 `"auto"` 则自动填充剩余宽度。
        'minWidth?: number|string',     // 列最小宽度。
        'value?: string|array',         // 默认值。
        'disabled?: bool',              // 是否禁用。
        'items?: array',                // 选项，当控件类型为下拉菜单时使用此属性指定下拉菜单项。
        'placeholder?: string',         // 占位文本。
        'tip?: string',                 // 显示在列标题上的提示文本。
        'tipClass?: string',            // 列标题上的提示触发按钮类名。
        'tipIcon?: string="info-sign"', // 列标题上的提示触发按钮图标。
        'tipProps?: string',            // 列标题上的提示触发按钮其他属性。
        'ditto?: bool',                 // 是否显示同上按钮。
        'defaultDitto?:string="on"',    // 同上按钮的默认值。
        'hidden?: bool=false',          // 是否隐藏
        'readonly?: bool=false'         // 是否只读
    );

    /**
     * Define default properties.
     *
     * @access protected
     */
    protected function build(): array
    {
        list($name, $label, $labelClass, $labelProps, $required, $tip, $tipClass, $tipProps, $tipIcon, $control, $width, $strong, $value, $disabled, $items, $placeholder, $ditto, $defaultDitto, $hidden, $readonly, $multiple) = $this->prop(array('name', 'label', 'labelClass', 'labelProps', 'required', 'tip', 'tipClass', 'tipProps', 'tipIcon', 'control', 'width', 'strong', 'value', 'disabled', 'items', 'placeholder', 'ditto', 'defaultDitto', 'hidden', 'readonly', 'multiple'));

        if($required === 'auto') $required = isFieldRequired($name);

        if($control !== false)
        {
            if(is_string($control))  $control = array('type' => $control, 'name' => $name);
            else if(empty($control)) $control = array();

            if(!isset($control['required']) && $required !== null) $control['required']    = $required;
            if(!isset($control['type']))                           $control['type']        = 'text';
            if($name !== null)                                     $control['name']        = $name;
            if($value !== null)                                    $control['value']       = $value;
            if($disabled !== null)                                 $control['disabled']    = $disabled;
            if($multiple !== null)                                 $control['multiple']    = $multiple;
            if($items !== null)                                    $control['items']       = $items;
            if($placeholder !== null)                              $control['placeholder'] = $placeholder;
            if($readonly !== null)                                 $control['readonly']    = $readonly;
        }

        $asIndex = $control['type'] === 'index';
        if($asIndex) $control['type'] = 'static';
        if($control['type'] == 'static') $name .= '_static';
        if($control['type'] === 'colorInput' && !isset($control['syncColor'])) $control['syncColor'] = '#' . $name . '_{GID}';

        return array(
            h::th
            (
                setClass('form-batch-head'),
                $hidden ? setClass('hidden') : null,
                zui::width($width),
                set('data-required', $required),
                set('data-ditto', $ditto),
                set('data-name', $name),
                $ditto ? set('data-default-ditto', $defaultDitto) : null,
                $asIndex ? set('data-index', $asIndex) : null,
                set($this->getRestProps()),
                span
                (
                    set::className('form-label form-batch-label', $labelClass, $strong ? 'font-bold' : null, $required ? 'required' : null),
                    set($labelProps),
                    $label
                ),
                empty($tip) ? null : new btn
                (
                    set::className('form-batch-tip state text-gray', $tipClass),
                    set::size('sm'),
                    set::type('ghost'),
                    toggle('tooltip', array('title' => $tip)),
                    set($tipProps),
                    set::icon($tipIcon)
                )
            ),
            h::td
            (
                setClass('form-batch-control'),
                $hidden ? setClass('hidden') : null,
                set('data-name', $name),
                empty($control) ? null : new control(set($control)),
                $this->children()
            )
        );
    }
}
