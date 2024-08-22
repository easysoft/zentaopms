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
        "type?: string",
        "name?: string",
        "label?: string",
        "items?: array",
        "value?: string",
        "placeholder?: string",
        'labelWidth?: string="80px"',
        'width?: string="60"',
        "suffix?: string",
        "error?: bool=false"
    );

    protected function buildControl()
    {
        list($type, $name, $items, $value, $placeholder, $error) = $this->prop(array('type', 'name', 'items', 'value', 'placeholder', 'error'));

        if($type == 'picker') return picker
        (
            setID("builderPicker_$name"),
            setClass('builder-picker', array('has-error' => $error)),
            set::name($name),
            set::items($items),
            set::placeholder($placeholder),
            set::disabled(empty($items)),
            !empty($value) ? set::value($value) : null
        );

        if($type == 'input') return input
        (
            setID("builderInput_$name"),
            setClass('builder-input', array('has-error' => $error)),
            set::name($name),
            set::placeholder($placeholder),
            !empty($value) ? set::value($value) : null
        );

        return null;
    }

    protected function build()
    {
        global $lang;
        list($label, $labelWidth, $width, $suffix, $error) = $this->prop(array('label', 'labelWidth', 'width', 'suffix', 'error'));

        return formGroup
        (
            set::label($label),
            set::labelWidth($labelWidth),
            set::labelClass('bg-gray-100 ring ring-border-strong font-bold px-1-important justify-center-important'),
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
                    $lang->bi->emptyError
                )
            )
        );
    }
}
