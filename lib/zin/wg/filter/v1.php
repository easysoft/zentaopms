<?php
declare(strict_types=1);
/**
 * The filter widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

class filter extends wg
{
    protected static array $defineProps = array(
        'class?: string',                   // 样式。
        'title?: string',                   // 控件标题。
        'type?: string',                    // 控件类型。
        'name?: string',                    // 控件名称。
        'value?: string',                   // 控件默认值。
        'items?: array',                    // picker 列表项或表项获取方法。
        'menu?: array',                     // picker 附加的菜单选项。
        'multiple?: boolean|number=false',  // picker 是否允许选择多个值，如果指定为数字，则限制多选的数目，默认 `false`。
        'layout?: string="horz"',             // 使用的方式，默认是水平使用，还可以指定为normal正常布局。
        'onChange?: function'
    );

    protected function buildPicker(): picker
    {
        list($name, $value, $items, $menu, $multiple, $onChange) = $this->prop(array('name', 'value', 'items', 'menu', 'multiple', 'onChange'));

        return picker
        (
            setClass('w-full'),
            set::name($name),
            set::value($value),
            set::items($items),
            set::menu($menu),
            set::multiple($multiple),
            on::change("$onChange(e, '$name')")
        );
    }

    protected function buildDatePicker(): datePicker|array
    {
        list($name, $value, $onChange) = $this->prop(array('name', 'value', 'onChange'));

        return datePicker
        (
            setClass('w-full'),
            set::name($name),
            set::value($value),
            on::change("$onChange(e, '$name')")
        );
    }

    protected function buildDatetimePicker(): datetimePicker|array
    {
        list($name, $value, $onChange) = $this->prop(array('name', 'value', 'onChange'));

        return datetimePicker
        (
            setClass('w-full'),
            set::name($name),
            set::value($value),
            on::change("$onChange(e, '$name')")
        );
    }

    protected function buildInput(): input
    {
        list($name, $value, $onChange) = $this->prop(array('name', 'value', 'onChange'));

        return input
        (
            set::name($name),
            set::value($value),
            on::change("$onChange(e, '$name')")
        );
    }

    protected function buildControl(string $type): node|array
    {
        if($type == 'select')   return $this->buildPicker();
        if($type == 'date')     return $this->buildDatePicker();
        if($type == 'datetime') return $this->buildDatetimePicker();
        return $this->buildInput();
    }

    protected function build()
    {
        list($type, $layout) = $this->prop(array('type', 'layout'));
        $class = $this->prop('class', $layout === 'horz' ? 'w-1/2' : '');

        return inputGroup
        (
            setClass("filter filter-{$type} {$class}" . ($layout === 'horz' ? ' mb-2 pr-4' : '')),
            $this->prop('title'),
            $this->buildControl($type)
        );
    }
}
