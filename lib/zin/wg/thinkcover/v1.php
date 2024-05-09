<?php
declare(strict_types=1);
namespace zin;

class thinkCover extends wg
{
    protected static array $defineProps = array(
        'item: object',      // 模型信息
        'actionUrl: string', // 开始按钮链接
    );

    protected function buildCoverContentBlock()
    {
        global $lang;
        list($item, $actionUrl) = $this->prop(array('item', 'actionUrl'));
        return div
        (
            setClass('bg-white px-8 w-full relative'),
            div
            (
                setClass('flex items-center w-full'),
                div
                (
                    setClass('w-3/5 px-4'),
                    div
                    (
                        setClass('text-2xl'),
                        $item->name
                    ),
                    div
                    (
                        setClass('mb-4 text-lg'),
                        setStyle(array('margin-top' => '-18px')),
                        section
                        (
                            setClass('break-words'),
                            set::content($item->desc),
                            set::useHtml(true)
                        )
                    ),
                    div
                    (
                        setClass('text-md text-black leading-6.5'),
                        $lang->thinkwizard->run->expect,
                        $item->duration,
                        $lang->thinkwizard->run->minTime
                    )
                ),
                div
                (
                    setClass('w-2/5 pr-4'),
                    img
                    (
                        set::src($item->thumbnail)
                    )
                )
            ),
            a
            (
                setClass('toolbar-item btn primary px-8 py-2 absolute bottom-8'),
                setStyle(array('left' => '50%', 'margin-left' => '-45px')),
                set::href($actionUrl),
                $lang->thinkwizard->run->start
            )
        );
    }

    protected function build():array
    {

        return array
        (
            $this->buildCoverContentBlock(),
        );
    }
}
