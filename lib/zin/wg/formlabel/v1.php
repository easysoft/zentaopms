<?php
declare(strict_types=1);
namespace zin;

class formLabel extends wg
{
    protected static array $defineProps = array(
        'text?: string',
        'required?: bool',
        'for?: string',
        'hint?: string',
        'hintIcon?: string',
        'hintClass?: string',
        'hintProps?: array',
        'actions?: array',
        'actionsClass?: string',
        'actionsProps?: array',
        'checkbox?: bool|array'
    );

    protected function build(): wg
    {
        list($text, $required, $for, $hint, $hintClass, $hintProps, $hintIcon, $actions, $actionsClass, $actionsProps, $checkbox) = $this->prop(array('text', 'required', 'for', 'hint', 'hintClass', 'hintProps', 'hintIcon', 'actions', 'actionsClass', 'actionsProps', 'checkbox'));

        if(!empty($hint))
        {
            $hint = btn
            (
                set::size('sm'),
                set::icon(is_null($hintIcon) ? 'help' : $hintIcon),
                setClass('ghost form-label-hint text-gray-300', $hintClass),
                toggle::tooltip(array('title' => $hint)),
                set($hintProps)
            );
        }

        if(is_array($checkbox)) $checkbox = checkbox(set($checkbox));

        if(is_array($actions))
        {
            $actions = toolbar
            (
                set::size('sm'),
                setClass('form-label-actions', $actionsClass),
                set::items($actions),
                set($actionsProps)
            );
        }


        return h::label
        (
            setClass('form-label', $required ? 'required' : null),
            set('for', $for),
            set($this->getRestProps()),
            $text,
            $this->children(),
            $checkbox,
            $hint,
            $actions
        );
    }
}
