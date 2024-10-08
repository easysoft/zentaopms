<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'sqlbuildercontrol' . DS . 'v1.php';

class sqlBuilderEmptyContent extends wg
{
    protected static array $defineProps = array(
        'btnClass?: string',
        'btnText?: string',
        'emptyText?: string',
        'onClick?: function'
    );

    protected function build()
    {
        list($btnClass, $btnText, $emptyText, $onClick) = $this->prop(array('btnClass', 'btnText', 'emptyText', 'onClick'));
        return div
        (
            setClass('h-full flex col items-center justify-center'),
            div
            (
                setClass('flex row justify-center items-center gap-x-4'),
                span
                (
                    setClass('text-gray-500'),
                    html($emptyText)
                ),
                btn
                (
                    setClass($btnClass),
                    set::type('secondary'),
                    set::icon('plus'),
                    set('data-index', -1),
                    $btnText,
                    on::click()->do($onClick)
                )
            )
        );
    }
}
