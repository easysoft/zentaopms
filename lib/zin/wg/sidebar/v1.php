<?php
declare(strict_types=1);
namespace zin;

class sidebar extends wg
{
    protected static array $defineProps = array(
        'side?:string="left"',
        'width?:string|number=40',
        'showToggle?:bool=true',
        'parent?:string',
        'preserve?:string'
    );

    protected function build(): wg
    {
        list($side, $showToggle, $width, $preserve, $parent) = $this->prop(array('side', 'showToggle', 'width', 'preserve', 'parent'));
        if($preserve === null)
        {
            global $app;
            $preserve = $app->getModuleName() . '-' . $app->getMethodName();
        }
        return div
        (
            setClass('sidebar'),
            width($width),
            setData(array('zui' => 'sidebar', 'side' => $side, 'toggleBtn' => $showToggle, 'preserve' => $preserve, 'parent' => $parent)),
            set($this->getRestProps()),
            $this->children(),
        );
    }
}
