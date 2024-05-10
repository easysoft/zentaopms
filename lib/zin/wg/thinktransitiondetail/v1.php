<?php
declare(strict_types=1);
namespace zin;

/**
 * 思引师前台节点和过渡页详情部件类。
 * thinmory front node and transition detail widget class.
 */
class thinkTransitionDetail extends wg
{
    protected static array $defineProps = array(
        'item: object',
    );
    protected function build()
    {
        $item = $this->prop('item');
        return div
        (
            setClass('flex bg-white px-8 w-full items-center w-full justify-center pt-10 pb-10 mb-4'),
            div
            (
                setClass('px-4 mt-10'),
                setStyle(array('max-width' => '878px')),
                $item->type == 'question' ? setStyle(array('min-width' => '643px')) : null,
                div
                (
                    setClass('text-2xl'),
                    $item->title
                ),
                div
                (
                    setClass('text-lg'),
                    setStyle(array('margin-top' => '-18px')),
                    section
                    (
                        setClass('break-words'),
                        set::content(htmlspecialchars_decode($item->desc)),
                        set::useHtml(true)
                    )
                ),
                $this->children()
            )
        );
    }
}
