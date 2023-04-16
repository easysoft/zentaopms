<?php
namespace zin;

class select extends wg
{
    static $defineProps =
    [
        'name: string',
        'id?: string',
        'class?: string="form-control"',
        'value?: string',
        'required?: bool',
        'disabled?: bool',
        'multiple?: bool',
        'items?: array',
        'options?: array',
        'size?: number',
    ];

    public function onBuildItem($item)
    {
        if($item instanceof item) $item = $item->props->toJsonData();

        $text  = isset($item['text']) ? $item['text'] : '';
        unset($item['text']);

        if(!isset($item['selected']))
        {
            $value     = isset($item['value']) ? $item['value'] : '';
            $valueList = $this->getValueList();

            $item['selected'] = in_array($value, $valueList);
        }

        $item = array_filter($item, function($v) {return $v !== false;});
        return h::option(set($item), $text);
    }

    public function getValueList()
    {
        $value = $this->prop('value');
        if($this->prop('multiple')) return is_array($value) ? $value : explode(',', $value);
        return [$value];
    }

    protected function build()
    {
        list($items, $options) = $this->prop(['items', 'options']);
        if(is_array($options)) $items = $options;

        if(!empty($items))
        {
            $valueList = $this->getValueList();
            foreach($items as $key => $item)
            {
                if(!is_array($item)) $item = ['text' => $item, 'value' => $key];
                if(!isset($item['selected'])) $item['selected'] = in_array($item['value'], $valueList);
                $items[$key] = $this->onBuildItem($item);
            }
        }

        return h::select
        (
            setClass('form-control'),
            set($this->props->skip(['items', 'options', 'value'])),
            $items,
            $this->children()
        );
    }
}
