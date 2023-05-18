<?php
declare(strict_types=1);
namespace zin;

class priLabel extends wg
{
    protected static $defineProps = array(
        'pri:int',
    );

    protected function onAddChild(mixed $child): mixed
    {
        if(is_string($child) && !$this->props->has('pri'))
        {
            $this->props->set('pri', $child);
            return false;
        }
    }

    private function getStyleClass(int $pri): string
    {
        if($pri == 1) return 'danger-outline';
        if($pri == 2) return 'warning-outline';
        if($pri == 3) return 'secondary-outline';
        if($pri == 4) return 'teal-outline';
        return 'gray-outline';
    }

    protected function build(): wg
    {
        $pri = $this->prop('pri');
        $className = $this->getStyleClass($pri);

        return span
        (
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            setClass('center', 'rounded-full', 'aspect-square', 'bg', 'h-4', 'w-4', $className),
            $pri
        );
    }
}
