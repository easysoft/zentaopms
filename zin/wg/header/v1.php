<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'heading' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'navbar' . DS . 'v1.php';

class header extends wg
{
    static $defineBlocks = array
    (
        'heading' => array('map' => 'toolbar'),
        'navbar' => array('map' => 'nav'),
        'toolbar' => array('map' => 'btn')
    );

    protected function buildHeading()
    {
        $heading = $this->block('heading');
        if(empty($heading)) $heading = new heading();
        return $heading;
    }

    protected function buildNavbar()
    {
        $navbar = $this->block('navbar');
        if(empty($navbar)) $navbar = new navbar();
        return $navbar;
    }

    /**
     * Build.
     *
     * @access protected
     * @return object
     */
    protected function build()
    {
        return h::header
        (
            setId('header'),
            h::div
            (
                setClass('container'),
                $this->buildHeading(),
                $this->buildNavbar(),
                $this->block('toolbar')
            )
        );
    }
}
