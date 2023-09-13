<?php
declare(strict_types=1);

namespace zin;

class formItemDropdown extends wg
{
    protected static array $defineProps = array(
        'items?:array',
        'value?:array',
        'method?: string',
        'url?: string',
        'actions?: array',
        'target?: string',
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function buildFormPanel(): wg
    {
        global $lang;

        $props = $this->prop(['method', 'url', 'actions', 'target']);

        return form
        (
            p
            (
                $lang->customField,
                setClass('text-base font-bold mb-1')
            ),
            set($props),
            setID('custom'),
            setClass('dropdown-menu p-5 bg-white'),
            formGroup
            (
                setClass('not-hide-menu w-20 mb-1'),
                checkList
                (
                    set
                    (
                        array(
                            'class'   => 'h-full flex-wrap gap-y-5',
                            'primary' => true,
                            'inline'  => true,
                            'value'   => $this->prop('value'),
                            'items'   => $this->prop('items')
                        )
                    )
                )
            ),
            set::actions
            (
                array(
                    array('text' => $lang->save, 'class' => 'primary'),
                    array('text' => $lang->cancel),
                    array('text' => $lang->restore, 'class' => 'ghost text-primary', 'btnType' => 'reset')
                )
            )
        );
    }

    protected function build(): wg
    {
        return div
        (
            setClass('form-item-dropdown'),
            btn
            (
                set
                (
                    array(
                       'class'       => 'ghost',
                       'icon'        => 'cog',
                       'data-target' => 'custom',
                       'data-toggle' => 'dropdown'
                    )
                )
            ),
            $this->buildFormPanel()
        );
    }
}
