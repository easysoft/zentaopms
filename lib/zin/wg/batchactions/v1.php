<?php
declare(strict_types=1);
namespace zin;

class batchActions extends wg
{
    protected static array $defineProps = array(
        'actionClass?: string=""',
    );

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build(): wg
    {
        return formGroup
        (
            setClass('ml-2'),
            div
            (
                setClass($this->prop('actionClass')),
                btn
                (
                    icon('plus', set::size('lg')),
                    setClass('bg-white ring-0 rounded bg-opacity-20 add-btn'),
                    on::click('addItem'),
                ),
                btn
                (
                    icon('close', set::size('lg')),
                    setClass('bg-white ring-0 rounded bg-opacity-20 del-btn'),
                    on::click('removeItem'),
                ),
            )
        );
    }
}
