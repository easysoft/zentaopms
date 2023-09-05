<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'pagebase' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'header' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'heading' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'main' . DS . 'v1.php';

class page extends pageBase
{
    protected static array $defaultProps = array('zui' => true);

    protected static array $defineBlocks = array(
        'head'     => array(),
        'header'   => array('map' => 'header'),
        'heading'  => array('map' => 'heading'),
        'dropmenu' => array('map' => 'dropmenu'),
        'main'     => array('map' => 'main'),
        'footer'   => array(),
    );

    protected function buildHeader(): array|wg
    {
        if($this->hasBlock('header')) return $this->block('header');

        $headingBlock = $this->block('heading');
        if(!empty($headingBlock)) return new header($headingBlock);

        $dropmenuBlock = $this->block('dropmenu');
        return new header(new heading($dropmenuBlock));
    }

    protected function buildBody(): array
    {
        if($this->hasBlock('main'))
        {
            return array
            (
                $this->buildHeader(),
                $this->block('main'),
                $this->children(),
                $this->block('footer')
            );
        }

        return array
        (
            $this->buildHeader(),
            new main($this->children()),
            $this->block('footer'),
        );
    }
}
