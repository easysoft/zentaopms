<?php
namespace zin;

class checkbox extends wg
{
    protected static $defineProps =
    [
        'text?: string',
        'checked?: bool',
        'name?: string',
        'primary: bool=true',
        'id: string',
        'disabled?: bool',
        'type: string="checkbox"',
        'value?: string',
        'typeClass?: string',
        'rootClass?: string'
    ];

    public function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('text'))
        {
            $this->props->set('text', $child);
            return false;
        }
    }

    protected function buildPrimary()
    {
        list($id, $text, $name, $checked, $disabled, $type, $typeClass, $rootClass, $value) = $this->prop(array('id', 'text', 'name', 'checked', 'disabled', 'type', 'typeClass', 'rootClass', 'value'));

        if(empty($typeClass)) $typeClass = $type;
        if(empty($id))        $id        = $name . '_' . $value;

        return div
        (
            setClass("$typeClass-primary", $rootClass, array('disabled' => $disabled)),
            h::input
            (
                set::type($type),
                set::id($id),
                set::name($name),
                set($this->props->skip('text,primary,typeClass,rootClass,id')),
            ),
            h::label
            (
                set::for($id),
                $text,
            ),
            $this->children()
        );
    }

    protected function build()
    {
        if($this->prop('primary')) return $this->buildPrimary();
        list($text, $type, $typeClass) = $this->prop(array('text', 'type', 'typeClass'));

        return h::label
        (
            setClass(empty($typeClass) ? $type : $typeClass),
            h::input
            (
                set::type($type),
                set($this->props->skip('text,primary,typeClass')),
            ),
            is_string($text) ? span($text, set::class('text')) : $text,
            $this->children()
        );
    }
}
