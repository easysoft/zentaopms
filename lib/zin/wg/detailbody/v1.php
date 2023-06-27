<?php
declare(strict_types=1);
namespace zin;

class detailBody extends wg
{
    protected static $defineProps = array(
        'isForm?: bool=false'
    );

    protected static $defineBlocks = array(
        'main' => array('map' => 'sectionList'),
        'side' => array('map' => 'detailSide'),
        'bottom' => array('map' => 'history,fileList'),
        'floating' => array('map' => 'floatToolbar'),
        'fixedActions' => array(),
    );

    protected function build()
    {
        $main     = $this->block('main');
        $side     = $this->block('side');
        $bottom   = $this->block('bottom');
        $floating = $this->block('floating');
        $isForm   = $this->prop('isForm');

        if(!$isForm)
        {
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

        return form
        (
            set::actionsClass('h-14 flex flex-auto items-center justify-center shadow'),
            setClass('detail-body rounded col of-y-hidden bg-white'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            setStyle('height', 'calc(100vh - 120px)'),
            div
            (
                setClass('flex flex-auto of-y-auto'),
                div
                (
                    setClass('col grow border-r-4'),
                    setStyle('border-color', 'var(--zt-page-bg)'),
                    $main,
                    $bottom,
                ),
                $side,
            ),
        );
    }
}
