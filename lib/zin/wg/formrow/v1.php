<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'formgroup' . DS . 'v1.php';

class formRow extends wg
{
    protected static array $defineProps = array(
        'width?: string',
        'items?: array',
        'hidden?: boolean'
    );

    public function onBuildItem($item): wg
    {
        if(!($item instanceof item))
        {
            if($item instanceof wg) return $item;
            $item = item(set($item));
        }

        return new formGroup(inherit($item));
    }

    protected function build(): wg
    {
        list($width, $items, $hidden) = $this->prop(['width', 'items', 'hidden']);

        return div
        (
            set::className('form-row', empty($width) ? null : 'grow-0', $hidden ? 'hidden' : ''),
            zui::width($width),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : null,
            set($this->getRestProps()),
            $this->children()
        );
    }
}
