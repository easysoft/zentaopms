<?php
declare(strict_types=1);
namespace zin;

class idLabel extends wg
{
    protected static array $defineProps = array
    (
        'id?: int|string'
    );

    public function onAddChild($child)
    {
        if((is_string($child) || is_int($child)) && !$this->props->has('id'))
        {
            $this->props->set('id', $child);
            return false;
        }
    }

    protected function build()
    {
        $id = $this->prop('id');
        return span
        (
            setClass('label label-id gray-300-outline size-sm rounded-full flex-none ml-1'),
            set($this->getRestProps()),
            $id,
            $this->children()
        );
    }

    public static function create(string|int|array $idOrProps, ?array $props = null, mixed ...$children): static
    {
        $props = $props ? $props : array();
        if(is_array($idOrProps))
        {
            $props = array_merge($idOrProps, $props);
        }
        else
        {
            $props['id'] = $idOrProps;
        }
        return new static(set($props), ...$children);
    }
}
