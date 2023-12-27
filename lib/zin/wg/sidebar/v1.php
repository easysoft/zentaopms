<?php
declare(strict_types=1);
namespace zin;

class sidebar extends wg
{
    protected static array $defineProps = array(
        'side?:string="left"',
        'width?:string|number=40',
        'maxWidth?:string|number=360',
        'minWidth?:string|number=160',
        'showToggle?:bool=true',
        'parent?:string',
        'preserve?:string',
        'dragToResize?:bool',
        'toggleBtn?:bool',
        'onToggle?:function'
    );

    protected function build(): wg
    {
        list($side, $showToggle, $width, $preserve, $parent, $maxWidth, $minWidth, $dragToResize, $onToggle, $toggleBtn) = $this->prop(array('side', 'showToggle', 'width', 'preserve', 'parent', 'maxWidth', 'minWidth', 'dragToResize', 'onToggle', 'toggleBtn'));
        if($preserve === null)
        {
            global $app;
            $preserve = $app->getModuleName() . '-' . $app->getMethodName();
        }
        return div
        (
            setClass('sidebar'),
            setData(array('zui' => 'sidebar', 'side' => $side, 'toggleBtn' => $showToggle, 'preserve' => $preserve, 'parent' => $parent, 'maxWidth' => $maxWidth, 'minWidth' => $minWidth, 'width' => $width, 'dragToResize' => $dragToResize, 'onToggle' => $onToggle, 'toggleBtn' => $toggleBtn)),
            set($this->getRestProps()),
            $this->children()
        );
    }
}
