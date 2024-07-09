<?php
declare(strict_types=1);
namespace zin;

class row extends wg
{
    protected static array $defineProps = array(
        'gap?: string|number',
        'justify?: string',
        'align?: string'
    );

    protected function build()
    {
        $classList = 'row';
        list($justify, $align, $gap) = $this->prop(array('justify', 'align', 'gap'));
        if(!empty($justify)) $classList .= ' justify-' . $justify;
        if(!empty($align))   $classList .= ' items-' . $align;

        return div
        (
            setClass($classList),
            is_numeric($gap) ? setClass("gap-$gap") : setStyle('gap', $gap),
            set($this->getRestProps()),
            $this->children()
        );
    }
}
