<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'nav' . DS . 'v1.php';

class featureBar extends wg
{
    static $defineProps = 'items?:array';

    static $defineBlocks = array
    (
        'nav' => array('map' => 'nav'),
        'leading' => array(),
        'trailing' => array(),
    );

    protected function buildNav()
    {
        $nav = $this->block('nav');
        if(!empty($nav) && $nav[0] instanceof nav) return $nav;
        return new nav
        (
            set::class('nav-feature'),
            set::items($this->prop('items')),
            divorce($this->children())
        );
    }

    protected function build()
    {
        return div
        (
            set::id('featureBar'),
            $this->block('leading'),
            $this->buildNav(),
            $this->block('trailing')
        );
    }
}
