<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'formgroup' . DS . 'v1.php';

class formRow extends wg
{
    static $defineProps = 'width?: string, items?: array';

    public function onBuildItem($item)
    {
        if(!($item instanceof item))
        {
            if($item instanceof wg) return $item;
            $item = item(set($item));
        }

        return new formGroup(inherit($item));
    }

    protected function build()
    {
        list($width, $items) = $this->prop(['width', 'items']);

        return div
        (
            set::class('form-row', empty($width) ? NULL : 'grow-0'),
            zui::width($width),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : null,
            set($this->getRestProps()),
            $this->children()
        );
    }
}
