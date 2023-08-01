<?php
declare(strict_types=1);
namespace zin;

class tabPane extends wg
{
    protected static array $defineProps = array(
        'key: string',
        'title?: string',
        'active?: bool=false',
        'param?: string',
    );

    protected static array $defineBlocks = array(
        'prefix'  => array(),
        'suffix'  => array(),
        'divider' => false,
    );

    protected function build(): wg
    {
        $key    = $this->prop('key');
        $active = $this->prop('active');

        return div
        (
            setID($key),
            setClass('tab-pane', $active ? 'active' : null),
            set($this->getRestProps()),
            $this->children()
        );
    }
}
