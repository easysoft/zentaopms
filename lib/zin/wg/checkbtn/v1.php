<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'checkbox' . DS . 'v1.php';

class checkBtn extends checkbox
{
    public static function getPageCSS(): ?string
    {
        return <<<CSS
        .check-btn > input + label {color: var(--color-gray-500)}
        .check-btn > input:checked + label {background-color: var(--color-primary-50); color:var(--color-primary-500); --tw-ring-color: var(--color-primary-400); z-index: 1}
        .check-btn > input:checked + label > svg {opacity: 1; top: -1px; right: -1px}
        CSS;
    }

    protected function buildPrimary()
    {
        list($id, $text, $name, $checked, $disabled, $type, $typeClass, $rootClass, $labelClass, $labelStyle, $value) = $this->prop(array('id', 'text', 'name', 'checked', 'disabled', 'type', 'typeClass', 'rootClass', 'labelClass', 'labelStyle', 'value'));

        if(empty($typeClass)) $typeClass = $type;
        if(empty($id))        $id        = empty($name) ? $this->gid : ($name . '_' . $value);

        return div
        (
            setClass("$typeClass-btn check-btn", $rootClass, array('disabled' => $disabled)),
            h::input
            (
                set::type($type),
                set::id($id),
                set::name($name),
                set::checked($checked),
                setClass('hidden'),
                set($this->props->skip('text,primary,typeClass,rootClass,id,labelClass'))
            ),
            h::label
            (
                set('for', $id),
                setClass($labelClass, 'btn'),
                setStyle($labelStyle ? $labelStyle : array()),
                html($text),
                html('<svg class="opacity-0 absolute top-0 right-0 transition-all" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" xmlns:v="https://vecta.io/nano"><path d="M0 0h16a2 2 0 0 1 2 2v16L9.818 9.818 0 0z" fill="currentColor"/><path d="M11.307 7.2L9 4.96l.631-.613 1.676 1.628L14.369 3l.631.613L11.307 7.2h0z" fill="#fff" stroke="#fff" stroke-width=".16"/></svg>'),
            ),
            $this->children()
        );
    }
}
