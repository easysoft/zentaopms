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
}
