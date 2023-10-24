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
        'value?: string',
    );

    protected static array $defaultProps = array(
        'class' => 'form-control',
        'rows' => 10
    );

    protected function onAddChild(array|string|wg|directive $child)
    {
        if(is_string($child) && !$this->props->has('value'))
        {
            $this->setProp('value', $child);
            return false;
        }

        return $child;
    }

    protected function build(): wg
    {
        return h::textarea
        (
            set($this->props->pick(array('name', 'id', 'class', 'required', 'placeholder', 'rows', 'cols', 'disabled'))),
            $this->prop('value'),
            $this->children()
        );
    }
}
