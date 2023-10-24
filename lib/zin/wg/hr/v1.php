<?php
declare(strict_types=1);
namespace zin;

class hr extends wg
{
    protected function build(): wg
    {
        return h::hr(setClass('my-5'));
    }
}
