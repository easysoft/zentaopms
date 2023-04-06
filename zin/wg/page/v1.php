<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'pagebase' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'header' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'main' . DS . 'v1.php';

class page extends pageBase
{
    static $defaultProps = array('zui' => true);

    static $defineBlocks =
    [
        'head' => array(),
        'header' => array('map' => 'header'),
        'main' => array('map' => 'main'),
        'footer' => array(),
    ];

    protected function buildBody()
    {
        $header = $this->hasBlock('header') ? $this->block('header') : new header();

        if($this->hasBlock('main'))
        {
            return array
            (
                $header,
                $this->block('main'),
                $this->children(),
                $this->block('footer')
            );
        }

        return array
        (
            $header,
            new main($this->children()),
            $this->block('footer'),
        );
    }
}
