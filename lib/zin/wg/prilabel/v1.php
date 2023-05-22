<?php
declare(strict_types=1);
namespace zin;

class priLabel extends wg
{
    protected static $defineProps = array(
        'pri:int|string'
    );

    protected function onAddChild(mixed $child): mixed
    {
        if(is_string($child) && !$this->props->has('pri'))
        {
            $this->props->set('pri', $child);
            return false;
        }
    }

    protected function build(): wg
    {
        $pri = (int)$this->prop('pri');

        return span
        (
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            setClass("pri-$pri"),
            $pri
        );
    }
}
