<?php
declare(strict_types=1);
namespace zin;

class sidebar extends wg
{
    protected static array $defineProps = array(
        'side?:string="left"',
        'width?:string|number=40',
        'maxWidth?:string|number=400',
        'minWidth?:string|number',
        'showToggle?:bool=true',
        'parent?:string',
        'preserve?:string'
    );

    protected function build(): wg
    {
        list($side, $showToggle, $width, $preserve, $parent, $maxWidth, $minWidth) = $this->prop(array('side', 'showToggle', 'width', 'preserve', 'parent', 'maxWidth', 'minWidth'));
        if($preserve === null)
        {
            global $app;
            $preserve = $app->getModuleName() . '-' . $app->getMethodName();
        }
        return div
        (
            setClass('sidebar'),
            width($width),
            setData(array('zui' => 'sidebar', 'side' => $side, 'toggleBtn' => $showToggle, 'preserve' => $preserve, 'parent' => $parent, 'maxWidth' => $maxWidth, 'minWidth' => $minWidth)),
            set($this->getRestProps()),
            $this->children(),
        );
    }
}
