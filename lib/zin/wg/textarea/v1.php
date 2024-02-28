<?php
declare(strict_types=1);
namespace zin;

class textarea extends wg
{
    protected static array $defineProps = array(
        'name?: string',
        'id?: string',
        'class?: string',
        'required?: bool',
        'placeholder?: string',
        'rows?: int',
        'cols?: int',
        'value?: string'
    );

    protected static array $defaultProps = array(
        'class' => 'form-control',
        'rows' => 10
    );

    protected function onAddChild(mixed $child)
    {
        if(is_string($child) && !$this->props->has('value'))
        {
            $this->setProp('value', $child);
            return false;
        }

        return $child;
    }

    protected function build()
    {
        return h::textarea
        (
            set($this->props->pick(array('name', 'id', 'class', 'placeholder', 'rows', 'cols', 'disabled'))),
            $this->prop('required') ? setClass('is-required') : null,
            $this->prop('value'),
            $this->children()
        );
    }
}
