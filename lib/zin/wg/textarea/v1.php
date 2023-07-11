<?php
declare(strict_types=1);
namespace zin;

class textarea extends wg
{
    protected static array $defineProps = array(
        'name: string',
        'id?: string',
        'class?: string',
        'required?: bool',
        'placeholder?: string',
        'rows?: int',
        'cols?: int',
    );

    protected static array $defaultProps = array(
        'class' => 'form-control',
        'rows' => 10
    );

    protected function build(): wg
    {
        return h::textarea(set($this->props), $this->children());
    }
}
