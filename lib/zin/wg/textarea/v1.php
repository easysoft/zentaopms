<?php
namespace zin;

class textarea extends wg
{
    static $defineProps = array(
        'name: string',
        'id?: string',
        'class?: string',
        'required?: bool',
        'placeholder?: string',
        'rows?: int',
        'cols?: int',
    );

    static $defaultProps = array(
        'class' => 'form-control',
        'rows' => 10
    );

    protected function build()
    {
        return h::textarea(set($this->props), $this->children());
    }
}
