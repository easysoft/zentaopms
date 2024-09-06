<?php
declare(strict_types=1);
namespace zin;

requireWg('textarea');
requireWg('checkbox');

class thinkBaseCheckbox extends wg
{
    protected static array $defineProps = array(
        'primary: bool=true',
        'type: string="checkbox"',
        'name?: string',
        'value?: string|array',
        'items?: array',
        'inline?: bool',
        'disabled?: bool'
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    public function getValueList()
    {
        $value = $this->prop('value');
        if(is_null($value)) return array();

        if($this->prop('type') === 'checkbox') return is_array($value) ? $value : explode(',', $value);
        return [$value];
    }

    public function onBuildItem($item): wg|node
    {
        global $lang;

        if($item instanceof item) $item = $item->props->toJSON();
        $disabled = $this->prop('disabled');

        if(!isset($item['checked']))
        {
            $value     = isset($item['value']) ? $item['value'] : '';
            $valueList = $this->getValueList();

            $item['checked']  = !empty($value) && in_array($value, $valueList);
            $item['disabled'] = $this->prop('disabled');
        }

        $props = $this->props->pick(['primary', 'type', 'name', 'disabled']);
        if(!empty($props['name']) && !empty($item['value'])) $props['id'] = $props['name'] . $item['value'];

        $itemClass = '';
        $text      = $item['text'];
        unset($item['text']);
        if(isset($item['checked']) && $item['checked']) $itemClass = 'is-checked';

        if(!empty($item['isOther']))
        {
            return div
            (
                setClass('item-control has-input w-full py-2 px-3 flex gap-3 items-center justify-between border cursor-pointer ' . $itemClass),
                setData('type', $this->prop('type')),
                !$disabled ? on::click('toggleChecked') : null,
                div
                (
                    setClass('flex items-start text-md gap-1.5 flex-1'),
                    div(setStyle(array('min-width' => '60px')), setClass('mt-1'), $text),
                    new textarea
                    (
                        set(array(
                            'rows'        => 1,
                            'class'       => isset($item['checked']) && $item['checked'] ? 'run-other' : 'hidden',
                            'name'        => 'other',
                            'value'       => isset($item['other']) ? $item['other'] : '',
                            'placeholder' => $lang->thinkrun->placeholder->otherOption,
                            'disabled'    => $disabled
                        )),
                        on::input('inputOther'),
                        on::click("event.stopPropagation(); if($('.run-other-error')) $('.run-other-error').removeClass('run-other-error');")
                    ),
                ),
                new checkbox
                (
                    set($props),
                    set($item),
                    $this->prop('type') == 'radio' ? on::click('e.stopPropagation()') : null,
                    isset($item['checked']) && $item['checked'] ? set::rootClass('checked') : null
                )
            );
        }
        return div
        (
            setData('type', $this->prop('type')),
            !$disabled ? on::click('toggleChecked') : null,
            setClass('item-control w-full py-2 px-3 flex gap-3 items-center justify-between border cursor-pointer ' . $itemClass),
            div(setClass('text-md flex-1 break-all'), $text),
            new checkbox
            (
                set($props),
                set($item),
                $this->prop('type') == 'radio' ? on::click('e.stopPropagation()') : null,
                isset($item['checked']) && $item['checked'] ? set::rootClass('checked') : null
            )
        );
    }

    protected function build()
    {
        list($items, $inline, $disabled) = $this->prop(['items', 'inline', 'disabled']);

        if(!empty($items))
        {
            $valueList = $this->getValueList();
            foreach($items as $key => $item)
            {
                $prefix = '';
                $index  = $key;
                while($index >= 0)
                {
                    $prefix = chr(65 + ($index % 26)) . $prefix;
                    $index  = floor($index / 26) - 1;
                }

                if(!is_array($item))         $item = array('text' => $item, 'value' => $key);
                if(!isset($item['checked'])) $item['checked'] = !empty($item['value']) && in_array($item['value'], $valueList);
                $item['text'] = !empty($item['disabledPrefix']) ? $item['text'] : $prefix . '. ' . $item['text'];
                $items[$key]  = $this->onBuildItem($item);
            }
        }

        return div
        (
            setClass($inline ? 'think-check-list check-list-inline' : 'think-check-list check-list'),
            set($this->getRestProps()),
            $disabled ? set('disabled', 'disabled') : '',
            $items,
            $this->children()
        );
    }
}
