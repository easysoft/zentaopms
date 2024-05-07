<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';
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

        if(!empty($item['isOther'])) return div
        (
            setClass('w-full relative cursor-pointer item-input'),
            new checkbox
            (
                set($props),
                set($item),
                isset($item['checked']) && $item['checked'] ? set::rootClass('checked') : null,
                on::change('handleInput')
            ),
            new input(set(array(
                'disabled' => !isset($item['checked']) || !$item['checked'],
                'name'     => $item['value'],
                'class'    => 'absolute top-2',
                'style'    => array('width' => '80%', 'height' => '32px', 'right' => '45px')
            ))),
        );
        return new checkbox(set($props), set($item), isset($item['checked']) && $item['checked'] ? set::rootClass('checked') : null, on::change('handleInput'));
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
