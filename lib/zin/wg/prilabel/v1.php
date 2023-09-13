<?php
declare(strict_types=1);
namespace zin;

class priLabel extends wg
{
    protected static array $defineProps = array(
        'pri: int|string',      // 优先级的值。
        'text?: string|array'   // 优先级显示的文本或优先级文本映射对象。
    );

    protected function onAddChild(mixed $child): mixed
    {
        if(is_string($child) || is_int($child))
        {
            if(!$this->props->has('pri'))
            {
                $this->props->set('pri', $child);
                return false;
            }
            if(!$this->props->has('text') && is_string($child))
            {
                $this->props->set('text', $child);
                return false;
            }
        }
        return $child;
    }

    protected function build(): wg
    {
        $pri  = $this->prop('pri', 0);
        $text = $this->prop('text');

        if(is_array($text)) $text = isset($text[$pri]) ? $text[$pri] : null;

        $pri = trim("$pri");
        if($text === null)  $text = ($pri === '0' || $pri === '') ? '' : $pri;

        return span
        (
            set($this->getRestProps()),
            setClass("pri-$pri"),
            $text
        );
    }
}
