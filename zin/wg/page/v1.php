<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'pagebase' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'header' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'main' . DS . 'v1.php';

class page extends pagebase
{
    static $defaultProps = array('zui' => true);

    static $defineBlocks = array
    (
        'head' => array(),
        'header' => array('map' => 'header'),
        'main' => array('map' => 'main'),
        'footer' => array(),
    );

    protected function buildHead()
    {
        $pageCSS = data('pageCSS');

        return array
        (
            empty($pageCSS) ? NULL : css($pageCSS),
            $this->block('head')
        );
    }

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

        $pageJS  = data('pageJS');

        return array
        (
            $header,
            new main($this->children()),
            $this->block('footer'),
            empty($pageJS) ? NULL : js($pageJS)
        );
    }

    protected function created()
    {
        $this->setDefaultProps(array('title' => data('title')));
        parent::created();
    }
}
