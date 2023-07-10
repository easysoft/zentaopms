<?php
declare(strict_types=1);
namespace zin;

class priLabel extends wg
{
    protected static array $defineProps = array(
        'pri: int|string'
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
        $pri = $this->prop('pri');

        return span
        (
            set($this->getRestProps()),
            setClass("pri-$pri"),
            $pri
        );
    }
}
