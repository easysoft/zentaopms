<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'formgroup' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'inputgroup' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'picker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';

class sqlBuilderControl extends wg
{
    protected static array $defineProps = array(
        "type?: string",                // 控件类型。
        "class?: string",
        "name?: string",                // 控件名。
        "label?: string",               // 控件标签。
        "items?: array",                // 控件下拉选项，仅type=picker时有效。
        "value?: string",               // 控件值。
        'required?: bool=false',        // 控件是否可清空，仅type=picker时有效。
        "placeholder?: string",         // 提示文本。
        'labelWidth?: string="80px"',   // 标签宽度。
        'labelAlign?: string="center"', // 标签对齐方式。
        'width?: string="60"',          // 控件宽度。
        "suffix?: string",              // 后缀内容。
        'onChange?: function',
        "error?: bool=false",           // 是否存在错误。
        'errorText?: string'            // 错误提示。
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    /**
     * 构建控件。
     * Build control.
     *
     * @access protected
     * @return node|null
     */
    protected function buildControl(): node|null
    {
        list($type, $name, $items, $value, $required, $placeholder, $onChange, $error) = $this->prop(array('type', 'name', 'items', 'value', 'required', 'placeholder', 'onChange', 'error'));

        if($type == 'picker')
        {
            if(!isset($items[$value])) $value = null;
            return picker
            (
                setID("builderPicker_$name"),
                setClass('builder-picker', array('has-error' => $error)),
                set::name($name),
                set::items($items),
                set::placeholder($placeholder),
                set::disabled(empty($items)),
                set::required($required),
                on::change()->do($onChange),
                !empty($value) ? set::value($value) : null
            );
        }

        if($type == 'input') return input
        (
            setID("builderInput_$name"),
            setClass('builder-input', array('has-error' => $error)),
            set::name($name),
            set::placeholder($placeholder),
            on::change()->do($onChange),
            set::value($value)
        );

        if($type == 'date') return datePicker
        (
            setID("builderDate$name"),
            setClass('builder-date', array('has-error' => $error)),
            set::name($name),
            set::placeholder($placeholder),
            on::change()->do($onChange),
            set::value($value)
        );

        if($type == 'datetime') return datetimePicker
        (
            setID("builderDatetime$name"),
            setClass('builder-datetime', array('has-error' => $error)),
            set::name($name),
            set::placeholder($placeholder),
            on::change()->do($onChange),
            set::value($value)
        );

        return null;
    }

    protected function build()
    {
        global $lang;
        list($class, $label, $labelWidth, $labelAlign, $width, $suffix, $error, $errorText) = $this->prop(array('class', 'label', 'labelWidth', 'labelAlign', 'width', 'suffix', 'error', 'errorText'));

        $padding = $labelAlign == 'center' ? 1 : 3;
        return formGroup
        (
            setClass($class),
            set::label($label),
            set::labelWidth($labelWidth),
            set::labelClass('bg-gray-100 ring ring-border-strong font-bold', "justify-$labelAlign-important px-{$padding}-important" ),
            set::width($width),
            inputGroup
            (
                setClass('flex col'),
                div
                (
                    setClass('flex row'),
                    $this->buildControl(),
                    !empty($suffix) ? label($suffix) : null
                ),
                span
                (
                    setClass('text-danger', array('hidden' => !$error)),
                    empty($errorText) ? $lang->bi->emptyError : $errorText
                )
            )
        );
    }
}
