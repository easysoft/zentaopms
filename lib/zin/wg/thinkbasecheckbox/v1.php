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
        return <<<CSS
        .think-check-list .item-control.is-checked {border: 1px solid var(--color-primary-500); color: var(--color-primary-500);}
        .think-check-list .item-control:hover {border: 1px solid var(--color-primary-500);}
        .think-check-list .checkbox-primary {--checkbox-size: 16px; width: 16px;}
        .think-check-list .checkbox-primary>input[type=checkbox]:checked+label:after {left: 1px; --tw-content: "\\e5ca"; color: var(--color-primary-500); font-weight: 600;}
        .think-check-list .checkbox-primary.checked>label:before, .think-check-list .checkbox-primary>input[type=checkbox]:checked+label:before {background-color: unset; border-color: var(--color-primary-500); color: var(--color-primary-500);}
        .think-check-list .checkbox-primary.checked>label, .think-check-list .radio-primary>label {font-family: ZentaoIcon !important;}
        .think-check-list .radio-primary>label:before, .think-check-list .radio-primary.checked>label:before, .think-check-list .radio-primary>input[type=radio]:checked+label:before {display: none;}
        .think-check-list .radio-primary>label:after {--tw-content: "\\e5ca"; color: var(--color-primary-500); font-size: 16px; background-color: unset; top: -2px; left: 2px; font-weight: 600;}
        CSS;
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

        if(!isset($item['checked']))
        {
            $value     = isset($item['value']) ? $item['value'] : '';
            $valueList = $this->getValueList();

            $item['checked']  = !empty($value) && in_array($value, $valueList);
            $item['disabled'] = $this->prop('disabled');
        }

        $props = $this->props->pick(['primary', 'type', 'name', 'disabled']);
        if(!empty($props['name']) && !empty($item['value'])) $props['id'] = $props['name'] . $item['value'];

        $itemClass = $this->prop('type') === 'checkbox' ? 'gap-4 px-4' : 'gap-3 px-3';
        $text      = $item['text'];
        unset($item['text']);
        if(isset($item['checked']) && $item['checked']) $itemClass .= ' is-checked';

        if(!empty($item['isOther']))
        {
            return div
            (
                setClass('item-control has-input w-full py-3 flex items-center justify-between border cursor-pointer ' . $itemClass),
                setData('type', $this->prop('type')),
                on::click('toggleChecked'),
                div
                (
                    setClass('flex items-start text-lg gap-1.5 flex-1'),
                    div(setStyle(array('min-width' => '60px')), setClass('mt-1'), $text),
                    new textarea
                    (
                        set(array(
                            'rows'        => 1,
                            'class'       => isset($item['checked']) && $item['checked'] ? '' : 'hidden',
                            'name'        => 'other',
                            'value'       => isset($item['other']) ? $item['other'] : '',
                            'placeholder' => $lang->thinkrun->placeholder->otherOption
                        )),
                        on::input('inputOther'),
                        on::click('e.stopPropagation()')
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
            on::click('toggleChecked'),
            setClass('item-control w-full py-3 flex items-center justify-between border cursor-pointer ' . $itemClass),
            div(setClass('text-lg flex-1'), $text),
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
            $letters   = range('A', 'Z');
            foreach($items as $key => $item)
            {
                $index = $key;
                do
                {
                    $remainder = $index % 26;
                    $text      = $letters[$remainder];
                    $index     = floor($index / 26) - 1;
                } while ($index >= 0);

                if(!is_array($item))         $item = array('text' => ($text . '. ' . $item), 'value' => $key);
                if(!isset($item['checked'])) $item['checked'] = !empty($item['value']) && in_array($item['value'], $valueList);
                $item['text'] = $text . '. ' . $item['text'];
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
