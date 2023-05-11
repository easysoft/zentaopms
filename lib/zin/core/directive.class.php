<?php
/**
 * The directive class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'zin.class.php';

class directive
{
    public $type;

    public $data;

    public $options;

    public $parent = null;

    /**
     * Construct a directive object
     * @param  string $type
     * @param  mixed  $data
     * @param  array  $options
     * @access public
     */
    public function __construct($type, $data, $options = null)
    {
        $this->type    = $type;
        $this->data    = $data;
        $this->options = $options;

        zin::renderInGlobal($this);
    }

    public function __debugInfo()
    {
        return array(
            'type'    => $this->type,
            'data'    => $this->data,
            'options' => $this->options
        );
    }

    public static function is($item, $type = null)
    {
        return is_object($item) && $item instanceof directive && ($type === null || $item->type === $type);
    }
}

function directive($type, $data, $options = null)
{
    return new directive($type, $data, $options);
}

function isDirective($item, $type = null)
{
    return directive::is($item, $type);
}
