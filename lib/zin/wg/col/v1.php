<?php
declare(strict_types=1);
namespace zin;

class col extends wg
{
    protected static array $defineProps = array(
        'gap?: string|number',
        'justify?:string',
        'align?:string'
    );

    protected function build()
    {
        $classList = 'col';
        list($justify, $align) = $this->prop(array('justify', 'align'));
        if(!empty($justify)) $classList .= ' justify-' . $justify;
        if(!empty($align))   $classList .= ' items-' . $align;

        return div
        (
            setClass($classList),
            zui::gap($this->prop('gap')),
            set($this->getRestProps()),
            $this->children()
        );
    }
}
