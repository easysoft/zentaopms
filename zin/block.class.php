<?php
/**
 * The v1 file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Jinyong Zhu <zhujinyong@easycorp.ltd>
 * @package     block
 * @version     $Id
 * @link        https://www.zentao.net
 */
class block
{
    /**
     * Menu list.
     *
     * @var    array
     * @access public
     */
    public $menus = array();

    /**
     * Block type.
     *
     * @var    string vertical(v)|horizon(h)
     * @access private
     */
    private $type = 'v';

    /**
     * Global language.
     *
     * @var    object
     * @access private
     */
    private $lang;

    /**
     * Global config.
     *
     * @var    object
     * @access private
     */
    private $config;

    /**
     * Global app.
     *
     * @var    object
     * @access private
     */
    private $app;

    /**
     * Construct function, init block data.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function __construct($type)
    {
        global $app, $config, $lang;

        $this->lang   = $lang;
        $this->config = $config;
        $this->app    = $app;
        $this->type   = $type;
    }

    /**
     * Set menus.
     *
     * @param  string $attr
     * @param  object $value
     * @access public
     * @return void
     */
    public function __set($attr, $value)
    {
        $this->menus[$attr] = $value;
    }

    /**
     * Print menu.
     *
     * @access public
     * @return void
     */
    public function x()
    {
        foreach($this->menus as $key => $menu) echo $menu->toString();
    }
}

/**
 * Get block object.
 *
 * @param  string $type vertical(v)|horizon(h)
 * @access public
 * @return object
 */
function block($type = 'v')
{
    return new block($type);
}
