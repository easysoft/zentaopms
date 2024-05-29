<?php
declare(strict_types=1);
namespace zin;
class thinkPffa extends wg
{
    protected static array $defineProps = array(
        'item: object'
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function build()
    {
        global $lang;
        return div
        (
            setClass('flex items-center'),
            div
            (
                setClass('w-2/7'),
                span(setClass('text-gray-400 text-sm'),$lang->thinkwizard->block . $lang->thinkwizard->blockList[2]),
                div
                (
                    setClass('flex items-center mt-1'),
                    div
                    (
                        setClass('bg-white w-full px-2 py-2.5 border border-gray-200 h-28 overflow-auto'),
                        span(setClass('text-success text-sm'),$lang->thinkwizard->unAssociated),
                        div
                        (
                            setClass('flex flex-wrap'),
                            div(setClass('w-8 h-8 bg-success bg-opacity-20 mt-1 mr-2')),
                            div(setClass('w-8 h-8 bg-success bg-opacity-20 mt-1 mr-2'))
                        ),
                        div(setClass('text-center text-sm leading-tight text-gray-300 mt-1'),$lang->thinkwizard->pffaGroundText[2])
                    ),
                    div
                    (
                        setClass('triangle triangle-right')
                    )
                )
            ),
            div
            (
                setClass('w-3/7'),
                div
                (
                    span(setClass('text-gray-400 text-sm'),$lang->thinkwizard->block . $lang->thinkwizard->blockList[1]),
                    div
                    (
                        setClass('flex justify-center flex-wrap mt-1'),
                        div
                        (
                            setClass('bg-white w-full px-2 py-2.5 border border-gray-200 h-28 overflow-auto'),
                            span(setClass('text-blue text-sm'),$lang->thinkwizard->unAssociated),
                            div
                            (
                                setClass('flex flex-wrap'),
                                div(setClass('w-8 h-8 bg-blue bg-opacity-20 mt-1 mr-2')),
                                div(setClass('w-8 h-8 bg-blue bg-opacity-20 mt-1 mr-2')),
                                div(setClass('w-8 h-8 bg-blue bg-opacity-20 mt-1 mr-2')),
                                div(setClass('w-8 h-8 bg-blue bg-opacity-20 mt-1 mr-2'))
                            ),
                            div(setClass('text-center text-sm leading-tight text-gray-300 mt-1'),$lang->thinkwizard->pffaGroundText[1])
                        ),
                        div
                        (
                            setClass('triangle triangle-down')
                        )
                    )
                ),
                div
                (
                    span(setClass('text-gray-400 text-sm'),$lang->thinkwizard->block . $lang->thinkwizard->blockList[5]),
                    div
                    (
                        setClass('flex justify-center flex-wrap mt-1'),
                        div
                        (
                            setClass('bg-white w-full px-2 py-2.5 border border-gray-200 h-28 overflow-auto'),
                            span(setClass('text-warning text-sm'),$lang->thinkwizard->unAssociated),
                            div
                            (
                                setClass('flex flex-wrap'),
                                div(setClass('w-8 h-8 bg-warning bg-opacity-20 mt-1 mr-2')),
                                div(setClass('w-8 h-8 bg-warning bg-opacity-20 mt-1 mr-2')),
                                div(setClass('w-8 h-8 bg-warning bg-opacity-20 mt-1 mr-2')),
                                div(setClass('w-8 h-8 bg-warning bg-opacity-20 mt-1 mr-2')),
                            ),
                            div(setClass('text-center text-sm leading-tight text-gray-300 mt-1'),$lang->thinkwizard->pffaGroundText[5])
                        )
                    )
                ),
                div
                (
                    setClass('relative pt-3.5'),
                    span(setClass('absolute text-gray-400 text-sm'),$lang->thinkwizard->block . $lang->thinkwizard->blockList[4]),
                    div
                    (
                        setClass('flex justify-center flex-wrap mt-1'),
                        div
                        (
                            setClass('triangle triangle-up')
                        ),
                        div
                        (
                            setClass('bg-white w-full px-2 py-2.5 border border-gray-200 h-28 overflow-auto'),
                            span(setClass('text-important text-sm'),$lang->thinkwizard->unAssociated),
                            div
                            (
                                setClass('flex flex-wrap'),
                                div(setClass('w-8 h-8 bg-important bg-opacity-20 mt-1 mr-2')),
                                div(setClass('w-8 h-8 bg-important bg-opacity-20 mt-1 mr-2')),
                                div(setClass('w-8 h-8 bg-important bg-opacity-20 mt-1 mr-2')),
                                div(setClass('w-8 h-8 bg-important bg-opacity-20 mt-1 mr-2')),
                            ),
                            div(setClass('text-center text-sm leading-tight text-gray-300 mt-1'),$lang->thinkwizard->pffaGroundText[4])
                        )
                    )
                )
            ),
            div
            (
                setClass('w-2/7'),
                span(setClass('text-gray-400 text-sm ml-4'),$lang->thinkwizard->block . $lang->thinkwizard->blockList[3]),
                div
                (
                    setClass('flex items-center mt-1'),
                    div
                    (
                        setClass('triangle triangle-left')
                    ),
                    div
                    (
                        setClass('bg-white w-full px-2 py-2.5 border border-gray-200 h-28 overflow-auto'),
                        span(setClass('text-special text-sm'),$lang->thinkwizard->unAssociated),
                        div
                        (
                            setClass('flex flex-wrap'),
                            div(setClass('w-8 h-8 bg-special bg-opacity-20 mt-1 mr-2')),
                            div(setClass('w-8 h-8 bg-special bg-opacity-20 mt-1 mr-2'))
                        ),
                        div(setClass('text-center text-sm leading-tight text-gray-300 mt-1'),$lang->thinkwizard->pffaGroundText[3])
                    )
                )
            )
        );
    }
}
