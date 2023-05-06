<?php
namespace zin;

class dataGroup extends wg
{
    protected static $defineProps = array(
        'label:string',
        'labelClass?:string'
    );

    private function label(string $text)
    {
        $className = $this->prop('labelClass');

        return div
        (
            setClass($className, 'text-gray', 'text-right', 'w-16'),
            $text
        );
    }

    protected function build()
    {
        $text = $this->prop('label');

        return div
        (
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            setClass('data-group flex justify-start gap-2 my-4'),
            $this->label($text),
            $this->children(),
        );
    }
}
