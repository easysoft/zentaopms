<?php
namespace zin;

class tabPane extends wg
{
    protected static $defineProps = array(
        'key: string',
        'title: string',
        'active?: bool=false',
    );

    protected function build()
    {
        $key    = $this->prop('key');
        $active = $this->prop('active');

        return div
        (
            setID($key),
            setClass('tab-pane', $active ? 'active' : null),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->children()
        );
    }
}
