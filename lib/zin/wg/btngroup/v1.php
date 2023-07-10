<?php
declare(strict_types=1);
namespace zin;

class btnGroup extends wg
{
    protected static array $defineProps = array(
        'items?:array',
        'disabled?:bool',
        'size?:string',
    );

    public function onBuildItem($item): btn
    {
        if(!($item instanceof item)) $item = item(set($item));
        return btn(inherit($item));
    }

    private function getClassName(): string
    {
        $disabled = $this->prop('disabled');
        $size     = $this->prop('size');

        $className = 'btn-group';
        if(!empty($disabled)) $className .= ' disabled';
        if(!empty($size))     $className .= " size-$size";

        return $className;
    }

    protected function build(): wg
    {
        $items     = $this->prop('items');
        $className = $this->getclassName();

        return div
        (
            setClass($className),
            set($this->getRestProps()),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : null,
            $this->children()
        );
    }
}
