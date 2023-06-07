<?php
declare(strict_types=1);
namespace zin;

class detailBody extends wg
{
    protected static $defineBlocks = array(
        'main' => array('map' => 'sectionList'),
        'side' => array('map' => 'detailSide'),
        'bottom' => array('map' => 'history'),
        'floating' => array('map' => 'floatToolbar'),
    );

    protected function build()
    {
        $main     = $this->block('main');
        $side     = $this->block('side');
        $bottom   = $this->block('bottom');
        $floating = $this->block('floating');

        return div
        (
            setClass('detail-body rounded flex gap-1'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            div
            (
                setClass('col gap-1 grow'),
                $main,
                $bottom,
                center(setClass('pt-6'), $floating),
            ),
            $side
        );
    }
}
