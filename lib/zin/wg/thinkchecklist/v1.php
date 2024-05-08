<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'textarea' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'checkbox' . DS . 'v1.php';

class thinkCheckList extends wg
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

    public static function getPageCSS(): string
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
        if($item instanceof item) $item = $item->props->toJSON();

        if(!isset($item['checked']))
        {
            $value     = isset($item['value']) ? $item['value'] : '';
            $valueList = $this->getValueList();

            $item['checked']  = in_array($value, $valueList);
            $item['disabled'] = $this->prop('disabled');
        }

        $props = $this->props->pick(['primary', 'type', 'name', 'disabled']);
        if(!empty($props['name']) && !empty($item['value'])) $props['id'] = $props['name'] . $item['value'];

        $itemClass = $this->prop('type') === 'checkbox' ? 'gap-4 px-4' : 'gap-3 px-3';
        $text      = $item['text'];
        unset($item['text']);
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
                    div(setStyle(array('min-width' => '60px')), $text),
                    new textarea
                    (
                        set(array(
                            'rows'     => 1,
                            'disabled' => !isset($item['checked']) || !$item['checked'],
                            'name'     => isset($item['value']) ? $item['value'] : 'other',
                            'value'    => isset($item['showText']) ? $item['showText'] : ''
                        )),
                        on::input('inputOther'),
                        on::click('stopPropagation')
                    ),
                ),
                new checkbox
                (
                    set($props),
                    set($item),
                    $this->prop('type') == 'radio' ? on::click('stopPropagation') : null,
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
                $this->prop('type') == 'radio' ? on::click('stopPropagation') : null,
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
                if(!isset($item['checked'])) $item['checked'] = in_array($item['value'], $valueList);
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
