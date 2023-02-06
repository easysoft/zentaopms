<?php
namespace zin\wg;

use \zin\core\h5;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(__DIR__) . DS . 'zuinav' . DS . 'v1.php';

class pagenavbar extends \zin\wg\zuinav
{
    static $tag = 'nav';

    static $defaultProps = array('id' => 'navbar');

    // static $customProps = 'text,menus';

    // /**
    //  * Accept child node.
    //  *
    //  * @param  object    $child
    //  * @param  bool      $strAsHtml
    //  * @access protected
    //  * @return mixed     object|string
    //  */
    // protected function acceptChild($child, $strAsHtml = false)
    // {
    //     /* Invoke parent method; */
    //     $child = parent::acceptChild($child, $strAsHtml);

    //     if(!$strAsHtml && is_string($child) && !$this->props->has('text'))
    //     {
    //         $this->prop('text', $child);
    //         return NULL;
    //     }

    //     return $child;
    // }

    // /**
    //  * Build builder.
    //  *
    //  * @param  bool      $isPrint
    //  * @param  object    $parent
    //  * @access protected
    //  * @return object
    //  */
    // protected function build($isPrint = false, $parent = NULL)
    // {
    //     $builder = parent::build($isPrint, $this);

    //     $menu = h5::menu(\zin\setClass('nav'));
    //     foreach($this->prop('menus') as $item) $menu->append(h5::li(\zin\setClass('nav-item'), $item));
    //     $builder->append($menu);

    //     return $builder;
    // }
}
