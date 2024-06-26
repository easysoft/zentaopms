<?php

namespace zin;
/**
 * 下拉菜单形式的选择器。
 * The dropPicker widget class.
 *
 * @author Hao Sun
 */
class dropPicker extends wg
{
    /**
     * Define the properties.
     *
     * @var array
     * @access protected
     */
    protected static array $defineProps = array
    (
        'text'  => 'string',
        'value' => 'string',
        'name'  => 'string',
        'items' => 'array'
    );
    protected function build()
    {
        list($items, $text, $value, $name) = $this->prop(array('items', 'text', 'value', 'name'));
        $btnID = $this->gid;

        return new dropdown
        (
            new btn
            (
                $text,
                setClass('w-full justify-between'),
                setID($btnID),
                h::formHidden($name, $value)
            ),
            set::items($items),
            set($this->getRestProps())
        );
    }
}
