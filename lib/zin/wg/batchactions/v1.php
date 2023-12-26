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
                    setClass('ghost add-btn'),
                    set::icon('plus icon-lg'),
                    on::click('addItem')
                ),
                btn
                (
                    setClass('ghost del-btn'),
                    set::icon('close icon-lg'),
                    on::click('removeItem')
                )
            )
        );
    }
}
