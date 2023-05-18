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

    private function setThemeStyle(int $pri): \zin\directive
    {
        if($pri == 1) return setClass('danger-outline');
        if($pri == 2) return setClass('warning-outline');
        if($pri == 3) return setClass('secondary-outline');
        if($pri == 4) return setStyle(array('color' => '#95D3E0', 'box-shadow' => 'rgb(255, 255, 255) 0px 0px 0px 0px, rgb(149, 211, 224) 0px 0px 0px 1px, rgba(0, 0, 0, 0) 0px 0px 0px 0px'));
        return setClass('gray-outline');
    }

    protected function build(): wg
    {
        $pri = (int)$this->prop('pri');

        return span
        (
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            setClass('center', 'rounded-full', 'aspect-square', 'bg', 'h-4', 'w-4'),
            $this->setThemeStyle($pri),
            $pri
        );
    }
}
