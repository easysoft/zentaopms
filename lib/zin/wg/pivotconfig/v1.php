<?php
declare(strict_types=1);
namespace zin;

class pivotConfig extends wg
{
    protected static array $defineProps = array(
        'title?: string',
        'titleTip?: node',
        'saveText?: string',
        'nextText?: string',
        'onSave?: function',
        'onNext?: function'
    );

    protected static array $defineBlocks = array(
        'heading'     => array()
    );

    protected function build()
    {
        global $lang;
        list($title, $titleTip, $saveText, $nextText, $onSave, $onNext) = $this->prop(array('title', 'titleTip', 'saveText', 'nextText', 'onSave', 'onNext'));
        return div
        (
            setClass('config-content bg-canvas'),
            div
            (
                setClass('pl-3 pr-4 py-4 max-height'),
                div
                (
                    setClass('flex text-base pl-1 pb-3 border-b h-8 items-center justify-between'),
                    div
                    (
                        setClass('text-base font-bold justify-start leading-4 items-center flex'),
                        $title,
                        div
                        (
                            setClass('flex items-center pl-1 text-gray', array('hidden' => empty($titleTip))),
                            $titleTip
                        )

                    ),
                    $this->block('heading')
                ),
                $this->children()
            ),
            div
            (
                setClass('config-content-bottom p-4'),
                toolbar
                (
                    setClass('gap-3'),
                    btn
                    (
                        setClass(array('hidden' => empty($saveText))),
                        set::type('primary'),
                        $saveText,
                        on::click()->do($onSave)
                    ),
                    btn
                    (
                        setClass('next-step'),
                        empty($nextText) ? $lang->pivot->nextStep : $nextText,
                        on::click()->do($onNext)
                    )
                )
            )
        );
    }
}
