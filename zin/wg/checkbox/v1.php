<?php
namespace zin;

class checkbox extends wg
{
    protected static $defineProps = 'text,checked';

    protected function build()
    {
        $input = h::checkbox();
        if ($this->prop('checked')) $input->prop('checked', 'true');
        return h::label(
            setClass('checkbox'),
            $input,
            $this->prop('text'),
        );
    }
}
